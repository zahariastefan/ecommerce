<?php

namespace App\Controller;

use App\Repository\CartRepository;
use App\Repository\UserRepository;
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
    public function thanks(Request $request)
    {
        $data = $request->query->all();
        dd($data);
        return $this->render('thanks.html.twig');
    }
}