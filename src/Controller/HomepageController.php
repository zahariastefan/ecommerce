<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
}