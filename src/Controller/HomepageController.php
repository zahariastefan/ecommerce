<?php

namespace App\Controller;

use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{

    #[Route('/', name:'app_homepage')]
    public function homepage(ProductRepository $productRepository, Request $request)
    {
        $queryBuilder = $productRepository->getProductsAndDescription()->getQuery()->getResult();
        $alertDisabled2fa = $request->query->get('alertDisabled2fa');
        if(!isset($alertDisabled2fa)) $alertDisabled2fa =  false;

        return $this->render('homepage.html.twig',[
            'products' => $queryBuilder,
            'alertDisabled2fa' =>$alertDisabled2fa
        ]);
    }

    #[Route('/get-cart-data', name:'app_get_cart_data')]
    public function getCart(CartRepository $cartRepository)
    {
        //if user logged in
        if($this->getUser()){
            //if have products
            $cart = $cartRepository->findBy([
                'user' => $this->getUser()
            ]);
//            dd($cart);
            if(!empty($cart)){//if yes
                $cartNr = count($cart);
            }else{
                $cartNr = 0;
            }
        }else{//if not logged in check for cookies if exist
            $cartNr = 0;//temporary
        }

        return new Response($cartNr);
    }
}