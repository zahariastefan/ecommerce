<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class CartController extends AbstractController
{

    #[Route('/cart', name:'app_cart')]
    public function cart()
    {
        return $this->render('cart.html.twig');
    }

}