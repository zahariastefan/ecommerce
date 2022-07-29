<?php

namespace App\Controller;

use App\Repository\CartRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
//        dd($cart);
//        $listOfTitles = [];
//        foreach ($cart as $item) {
//            $productArray =$item->getProduct()->toArray();
//            $getID = $productArray[0]->getTitle();
////                if(!array_search($productArray[0], $listWithoutRepetition)){
////                    $listWithoutRepetition[] = $productArray[0];
////                }
//            if(count($listOfTitles) > 0){
//                foreach ($listOfTitles as $listOfTitle){
//                    if($productArray[0]->getTitle() != $listOfTitle){
//                        $listOfTitles[] = $productArray[0]->getTitle();
//                    }
//            }
//            }elseif (count($listOfTitles) <= 1){
//                $listOfTitles[] = $productArray[0]->getTitle();
//            }



//            dd($productArray[0]->getTitle());
//        }
        $listOfTitles = [];
        foreach ($cart as $singleCart){
            $title = $singleCart->getProduct()->toArray()[0]->getTitle();
            $listOfTitles[] = $title;
        }
        $uniqueTitles = array_unique($listOfTitles);
        //now get objects from list of titles


//        dd(array_count_values($listOfTitles));
        return $this->render('cart.html.twig', [
            'productsInCart' => array_count_values($listOfTitles)
        ]);
    }

}