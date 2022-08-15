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
    public function cart(EntityManagerInterface $entityManager, UserRepository $userRepository,GuestOrdersRepository $guestOrdersRepository, CartRepository $cartRepository, ProductRepository $productRepository)
    {

        if($this->getUser()) {
            if(isset($_COOKIE['product_item'])){
                $productsId = json_decode($_COOKIE['product_item'],true);
                $formattedArrayProductsAndQuantities = [];
//                dd($_COOKIE['product_item']);
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
            return $this->render('cart.html.twig', [
                'productsInCart' => array_count_values($listOfTitles)
            ]);
        }else{
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
                return $this->render('cart.html.twig', [
                    'productsInCart' => array_count_values($listOfTitles)
                ]);
            }else{
                return $this->render('cart.html.twig', [
                    'productsInCart' => [0=>'No product']
                ]);
            }
        }
        return $this->render('cart.html.twig', [
            'productsInCart' => array_count_values($listOfTitles)
        ]);
    }

    #[Route('/checkout', name:'app_checkout')]
    public function checkout()
    {
        return $this->render('checkout.html.twig');
    }

    #[Route('/thanks', name:'app_thanks')]
    public function thanks(Request $request, ProductRepository $productRepository, UserRepository $userRepository, EntityManagerInterface $entityManager, CartRepository $cartRepository)
    {

        $data = $request->query->all();
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
        }else{
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




        return $this->render('thanks.html.twig');
    }


}