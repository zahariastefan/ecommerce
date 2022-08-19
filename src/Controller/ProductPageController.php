<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Comment;
use App\Entity\Product;
use App\Entity\User;
use App\Factory\CommentFactory;
use App\Factory\ProductFactory;
use App\Factory\UserFactory;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductPageController extends  AbstractController
{
    #[Route('/product-page/{slug}', name:'app_product_page')]
    public function productPage($slug,ProductRepository $productRepository)
    {
        $queryBuilder = $productRepository->getProductsAndDescription($slug)->getQuery()->getResult();
        /*
        // here is to create One Comment to each refresh to see more comments
        $user = $userRepository->findBy([
            'id' => 21
        ]);

        $product = $queryBuilder[0];

        $comment = new Comment();
        $comment->setContent(CommentFactory::faker()->text(30));
        $comment->setAddedAt(\DateTimeImmutable::createFromMutable(CommentFactory::faker()->dateTimeBetween('-1 year')));
        $comment->setUpdatedAt(\DateTimeImmutable::createFromMutable(CommentFactory::faker()->dateTimeBetween('-1 year')));
        $comment->addUser($user[0]);
        $comment->addProduct($product);


        $manager->persist($user[0]);
        $manager->persist($product);
        $manager->persist($comment);
        $manager->flush();
        */
        return $this->render('product_page.html.twig',[
            'product' => $queryBuilder[0]
        ]);
    }

    #[Route('/comment', name:'app_comment')]
    public function comment()
    {
        return new Response('hello world2');
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

        } else {
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
        } else {//if not logged in add to cookie!
            $getIDFromProdObj =[];
            if ((!preg_match('~[0-9]+~', $itemId))) {
                $product = $productRepository->findBy([
                    'title' =>$itemId
                ]);
                $getIDFromProdObj[]=$product[0]->getId();
                $itemId=$getIDFromProdObj;
            }
            if(!isset($_COOKIE['product_item'])){
                setcookie('product_item','{"productsId":["'.$itemId.'"]}');
            }else{
                $products = $_COOKIE['product_item'];
                $products = json_decode($products, true);
                $newListCookie['productsId'] = [];
                if(gettype($products['productsId']) != 'integer'){
                    if(count($products['productsId']) > 0){
                        foreach ($products as $product) {
                            $newListCookie['productsId']=$product;
                        }
                        $newListCookie['productsId'][]=$itemId;
                    }
                }
                setcookie('product_item',json_encode($newListCookie));
            }
        }
        return new JsonResponse(['hello'=>'world']);
    }

    #[Route('/remove-to-cart', name:'app_remove_cart')]
    public function removeToCart(Request $request, CartRepository $cartRepository, ProductRepository $productRepository,
                                 UserRepository $userRepository, EntityManagerInterface $entityManager, int $quantity = 1)
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
//            $productObject = $productRepository->findBy([
//                'title' => $itemId
//            ]);
            $user = $userRepository->findBy([
                'email' => $this->getUser()->getUserIdentifier()
            ])[0];
            $cart = $cartRepository->findBy([
                'product' => $productObject[0],
                'user' => $user,
                'status' => 0
            ]);
            $entityManager->remove($cart[0]);
            $entityManager->flush();
        }else{
            $productsId = json_decode($_COOKIE['product_item'],true);
            $idFromGET = $productObject[0]->getId();

            $allIdWithoutItemId = [];
            $selectedId=[];
            foreach ($productsId['productsId'] as $idCookie) {
                if($idFromGET == $idCookie){
                    $selectedId[] = $idCookie;
                }else{
                    $allIdWithoutItemId[]=$idCookie;
                }
            }

            if(count($selectedId) > 0){
                unset($selectedId[array_rand($selectedId)]);
            }else{
                unset($selectedId[0]);
            }

            foreach ($selectedId as $item) {
                $allIdWithoutItemId[] = $item;
            }
            $productsId['productsId']= $allIdWithoutItemId;

            setcookie('product_item', json_encode($productsId));
        }

        return new JsonResponse(['hello' => 'world']);
    }
}