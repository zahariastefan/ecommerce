<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\GuestOrders;
use App\Repository\CartRepository;
use App\Repository\GuestOrdersRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @param UserRepository $userRepository
     * @param GuestOrdersRepository $guestOrdersRepository
     * @param CartRepository $cartRepository
     * @param ProductRepository $productRepository
     * @return Response
     */

    /**
     * status
     * 0 = in cart
     * 1 = ordered
     * 2 = delivered
     * 3 = refund
     * 4 = canceled
     */

    #[Route('/cart', name:'app_cart')]
    public function cart(EntityManagerInterface $entityManager,
                         UserRepository $userRepository,
                         CartRepository $cartRepository,
                         ProductRepository $productRepository, Request $request)
    {
        if($this->getUser()) {
            if(isset($_COOKIE['product_item'])){
                $productsId = json_decode($_COOKIE['product_item'],true);
                $formattedArrayProductsAndQuantities = [];
                foreach ($productsId['productsId'] as $productId) {
                    $product = $productRepository->findBy([
                        'id' =>$productId
                    ])[0];
                    $user = $userRepository->findBy(['email' => $this->getUser()->getUserIdentifier()])[0];
                    $cart = new Cart();
                    $cart->setUser($user);
                    $cart->setProduct($product);
                    $cart->setAddedAt(new \DateTimeImmutable());
                    $cart->setStatus(0);
                    $cart->setOrderNr($this->getUser()->getUniqueNr());
                    $entityManager->persist($cart);
                    $formattedArrayProductsAndQuantities[] =$product->getTitle();
                }

                $entityManager->flush();
                unset($_COOKIE['product_item']);
                setcookie('product_item', '', time() - 3600);
            }
            $products = [];
            $listOfTitles = [];
            $cart = $cartRepository->createQueryBuilder('c')
                ->where('c.user = '.$this->getUser()->getId())
                ->andWhere('c.status = 0')
                ->getQuery()
                ->getResult()
            ;

            foreach ($cart as $singleCart){
                $productsFromCart = $productRepository->findBy([
                    'id' => $singleCart->getProduct()->getId()
                ]);
                //select only cart with status 0!!
                if(gettype($singleCart) == 'object'){
                    $title = $productsFromCart[0]->getTitle();
                    if(!empty($title)){
                        $listOfTitles[] = $title;
                    }
                }
            }
            $countedList = array_count_values($listOfTitles);
            ksort($countedList);
            $withQuantityProducts=[];
            foreach ($countedList as $product => $quantity) {
                $productC = $productRepository->findBy([
                    'title' => $product
                ])[0];
                $productC->quantity = $quantity;
                $withQuantityProducts[]=$productC;
            }

            return $this->render('cart.html.twig', [
                'products' =>$withQuantityProducts
            ]);
        }
        else{
//            //get products from cart
            if(isset($_COOKIE['product_item'])){
                $productsId = json_decode($_COOKIE['product_item'],true);
                $formattedArrayProductsAndQuantities = [];
                foreach ($productsId['productsId'] as $productId) {
                    $product = $productRepository->findBy([
                        'id' =>$productId
                    ]);
                    $formattedArrayProductsAndQuantities[] =$product[0]->getTitle();
                }
                $listOfTitles = $formattedArrayProductsAndQuantities;
                $countedList = array_count_values($listOfTitles);
                ksort($countedList);
                $withQuantityProducts=[];
                foreach ($countedList as $product => $quantityC) {
                    $productC = $productRepository->findBy([
                        'title' => $product
                    ])[0];
                    $productC->quantity = $quantityC;
                    $withQuantityProducts[]=$productC;
                }
                return $this->render('cart.html.twig', [
                    'products' => $withQuantityProducts
                ]);
            }else{
                return $this->render('cart.html.twig', [
                    'products' => []
                ]);
            }
        }
        return $this->render('cart.html.twig', [
            'products' => array_count_values($listOfTitles)
        ]);
    }

    #[Route('/checkout', name:'app_checkout')]
    public function checkout()
    {
        return $this->render('checkout.html.twig');
    }

    #[Route('/thanks', name:'app_thanks')]
    public function thanks(Request $request, ProductRepository $productRepository,
                           UserRepository $userRepository,
                           EntityManagerInterface $entityManager,
                           CartRepository $cartRepository)
    {

        $data = $request->query->all();
//        dd($data);
        if(!empty($data)){
            if($this->getUser()){
                $user = $userRepository->findBy([
                    'email' => $this->getUser()->getUserIdentifier()
                ]);
                $allItems = [];
                //starting adding products to order
                foreach ($data as $key => $oneData) {
                    $array = json_decode($oneData, true);
                    if(str_contains($key, 'item') ){//if is a product
                        $product = $productRepository->findBy([
                            'title' => $array['item']
                        ]);
                        $cart = $cartRepository
                            ->createQueryBuilder('c')
                            ->orderBy('c.added_at','DESC')
                            ->where('c.user = '.$user[0]->getId())
                            ->andWhere('c.status = 0')
                            ->getQuery()->getResult();
                        $allItems[] = $product;
                        foreach ($cart as $item) {
//                    dd($data);
                            //here I have to change status of cart product
                            $item->setStatus(1);
                            $item->setPhone($data['phone']);
                            $item->setAddress($data['city'].' '.$data['street'].' '.$data['number'].' '.$data['building']);
                            $item->setCity($data['city']);
                            $item->setStreet($data['street']);
                            $item->setStreetNumber($data['number']);
                            $item->setBuilding($data['building']);
                            $item->setAddedAt(new \DateTimeImmutable());
                            $entityManager->persist($item);
                        }

                    }
                }
                $user[0]->setUniqueNr(rand(123456,123456789));
                $entityManager->persist($user[0]);
                //if not logged in , take all the products and send directly order on email without save any more on DB
                $entityManager->flush();
            }
            else{
                $items = [];
                foreach ($data as $key => $oneData) {
                    $array = json_decode($oneData, true);
                    if (str_contains($key, 'item')) {//if is a product
                        $items[] = $array;
                    }
                }

                if(isset($_COOKIE['thanks'])){
                    if($_COOKIE['thanks'] == 0){
                        foreach ($items as $item) {
                            $product = $productRepository->findBy([
                                'title' => $item['item']
                            ]);
                            for ($x=0;$x<$item['quantity'];$x++){
                                $guestOrder = new GuestOrders();
                                $guestOrder->setName($data['name']);
                                $guestOrder->setSurname($data['surname']);
                                $guestOrder->setEmail($data['email']);
                                $guestOrder->setStatus(1);
                                $guestOrder->setProduct($product[0]);
                                $guestOrder->setPhone($data['phone']);
                                $guestOrder->setAddress($data['city'] . ' ' . $data['street'] . ' ' . $data['number'] . ' ' . $data['building']);
                                $guestOrder->setAddedAt(new \DateTimeImmutable());
                                $entityManager->persist($guestOrder);
                            }

                        }
                    }
                }

                $entityManager->flush();
                setcookie('thanks', 1);
            }
        }else{
//            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('thanks.html.twig');
    }

    #[Route('/add-to-cart', name:'app_add_cart')]
    public function addToCart(Request $request, ProductRepository $productRepository,
                              UserRepository $userRepository,
                              EntityManagerInterface $entityManager, int $quantity = 1)
    {
        if(!empty($request->request->get('quantity')))$quantity=$request->request->get('quantity');
        $itemId = trim($request->request->get('itemId'));
        if (empty($itemId)) {
            $itemId = trim($request->query->get('itemId'));
        }
        if ((preg_match('~[0-9]+~', $itemId))) {
            $productObject = $productRepository->findBy([
                'id' => $itemId
            ]);

        }
        else {
            $productObject = $productRepository->findBy([
                'title' => $itemId
            ]);
        }
        if ($this->getUser()) {//if logged in add to cart and also on DB
            $userId = $this->getUser()->getId();
            $user = $userRepository->findBy([
                'id' => $userId
            ]);
            for ($x=0;$x<$quantity;$x++) {
                $cart = new Cart();
                $cart->setUser($user[0]);
                $cart->setProduct($productObject[0]);
                $cart->setAddedAt(new \DateTimeImmutable());
                $cart->setStatus(0);
                $cart->setOrderNr($user[0]->getUniqueNr());
                $entityManager->persist($cart);
            }

            $entityManager->flush();
//            dd($cart);
        }
        else {//if not logged in add to cookie!
            $getIDFromProdObj =[];
            if ((!preg_match('~[0-9]+~', $itemId))) {
                $product = $productRepository->findBy([
                    'title' =>$itemId
                ]);
                $getIDFromProdObj[]=$product[0]->getId();
                $itemId=$getIDFromProdObj;
            }
            if(!isset($_COOKIE['product_item'])){
                $products = '{"productsId":[]}';
                $products = json_decode($products, true);
            }else{
                $products = $_COOKIE['product_item'];
                $products = json_decode($products, true);
            }

            $newListCookie['productsId'] = [];
            if (gettype($products['productsId']) != 'integer') {
                foreach ($products as $product) {
                    $newListCookie['productsId'] = $product;
                }
                for ($x = 0; $x < $quantity; $x++) {
                    $newListCookie['productsId'][] = $itemId;
                }
            }
            setcookie('product_item', json_encode($newListCookie));
        }
        return new JsonResponse(['hello'=>'world']);
    }

    #[Route('/remove-to-cart', name:'app_remove_cart')]
    public function removeToCart(Request $request, CartRepository $cartRepository, ProductRepository $productRepository,
                                 UserRepository $userRepository,
                                 EntityManagerInterface $entityManager, int $quantity = 1)
    {
        if(!empty($request->request->get('quantity')))$quantity=$request->request->get('quantity');

        $itemId = trim($request->request->get('itemId'));
        if ((preg_match('~[0-9]+~', $itemId))) {
            $productObject = $productRepository->findBy([
                'id' => $itemId
            ]);
        } else {
            $productObject = $productRepository->findBy([
                'title' => $itemId
            ]);
        }
        if ($this->getUser()) {
            $user = $userRepository->findBy([
                'email' => $this->getUser()->getUserIdentifier()
            ])[0];
            $carts = $cartRepository->findBy([
                'product' => $productObject[0],
                'user' => $user,
                'status' => 0
            ]);
            $x=0;
            foreach ($carts as $cart) {
                $entityManager->remove($cart);
                $entityManager->remove($cart);
                $x++;
                if($x == $quantity) break;
            }
            $entityManager->flush();
        }else{
            if(isset($_COOKIE['product_item'])){
                $productsId = json_decode($_COOKIE['product_item'],true);
                $idFromGET = $productObject[0]->getId();



                $allIdWithoutItemId = [];
                $selectedId=[];
                foreach ($productsId['productsId'] as $key =>$idCookie) {
                    if($idFromGET == $idCookie[0]){
                        $selectedId[] = $idCookie;
                    }else{
                        $allIdWithoutItemId[]=$idCookie;
                    }
                }

                if(count($selectedId) > 1){
                    for ($x=0;$x<$quantity;$x++) {
                        unset($selectedId[$x]);
                    }
                }
                else{
                    unset($selectedId[0]);
                }

                foreach ($selectedId as $item) {
                    $allIdWithoutItemId[] = $item;
                }
                $productsId['productsId']= $allIdWithoutItemId;
                setcookie('product_item', json_encode($productsId));
            }else{
                setcookie('product_item', '[]');
            }
        }

        return new JsonResponse(['hello' => 'world']);
    }


}