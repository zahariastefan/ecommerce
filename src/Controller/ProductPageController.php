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
    public function addToCart(Request $request, ProductRepository $productRepository, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
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
            //check if logged in
            $cart = new Cart();
            $cart->setUser($user[0]);
            $cart->setProduct($productObject[0]);
            $cart->setAddedAt(new \DateTimeImmutable());
            $cart->setStatus(0);
            $cart->setOrderNr($user[0]->getUniqueNr());
            $entityManager->persist($cart);
            $entityManager->flush();
        } else {//if not logged in add to cookie!
            //if not exist
            //if exist
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
    public function removeToCart(Request $request, CartRepository $cartRepository, ProductRepository $productRepository, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $itemId = trim($request->request->get('itemId'));
        $productObject = $productRepository->findBy([
            'title' => $itemId
        ]);
        $productsFromCart = $productObject[0]->getCarts()->toArray();
        $productToRemove = $productsFromCart[array_rand($productsFromCart)];
        $entityManager->remove($productToRemove);
        $entityManager->flush();

        setcookie("product_item", "", time()-3600);

        return new JsonResponse(['hello' => 'world']);
    }
}