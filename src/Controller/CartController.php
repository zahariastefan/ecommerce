<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class CartController extends AbstractController
{

    #[Route('/cart', name:'app_cart')]
    public function cart(UserRepository $userRepository, CartRepository $cartRepository)
    {
        if(!$this->getUser()) return new Response('no logged in');
        $user = $userRepository->findBy([
            'id' => $this->getUser()->getId()
        ]);

        $cart = $cartRepository->findBy([
            'user' => $user
        ]);
        $listOfTitles = [];
        foreach ($cart as $singleCart){
//            dd(gettype($singleCart));
            if(gettype($singleCart) == 'object'){
//                dd($singleCart->getProduct()->toArray());
                $title = $singleCart->getProduct()->toArray();
                if(!empty($title)){
                    $listOfTitles[] = $title[0]->getTitle();
                }
            }else{
                $title = $singleCart->getProduct()->toArray()[0]->getTitle();
                if(!empty($title)){
                    $listOfTitles[] = $title;
                }
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
    public function thanks(Request $request, ProductRepository $productRepository, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $user = $userRepository->findBy([
            'email' => $this->getUser()->getUserIdentifier()
        ]);
        $data = $request->query->all();
        $allItems = [];
        //starting adding products to order
        foreach ($data as $key => $oneData) {
            $array = json_decode($oneData, true);

            if(str_contains($key, 'item') ){//if is a product
                $product = $productRepository->findBy([
                    'title' => $array['item']
                ]);
//                dd($data);
                $allItems[] = $product;
                $order = new Order();
                $order->setUser($user[0]);
                $order->setProduct($product[0]);
                $order->setQuantity($array['quantity']);
                $order->setAddress($data['street'].' '.$data['number'].' '.$data['building']);
                $order->setPhone($data['phone']);
                $entityManager->persist($order);
            }

        }
        //products added to Order



        $entityManager->flush();

//        dd($data);
        return $this->render('thanks.html.twig');
    }
}