<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{

    #[Route('/', name:'app_homepage')]
    public function homepage(ProductRepository $productRepository)
    {
        $queryBuilder = $productRepository->getProductsAndDescription()->getQuery()->getResult();
        return $this->render('homepage.html.twig',[
            'products' => $queryBuilder
        ]);
    }
}