<?php

namespace App\Controller;

use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{

    #[Route('/{page<\d+>}', name:'app_homepage')]
    public function homepage(ProductRepository $productRepository, Request $request, int $page = 1)
    {
        $orderBy = $request->query->get('orderBy');

        $search = $request->query->get('searchTerm');
        $queryBuilder = $productRepository->getProductsAndDescription($search,  $orderBy);
        $alertDisabled2fa = $request->query->get('alertDisabled2fa');
        if(!isset($alertDisabled2fa)) $alertDisabled2fa =  false;

        $pagerfanta = new Pagerfanta(
            new QueryAdapter($queryBuilder)
        );

        $pagerfanta->setMaxPerPage(8);
        $pagerfanta->setCurrentPage($page);


        return $this->render('homepage.html.twig',[
//            'products' => $queryBuilder,
            'pager' => $pagerfanta,
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
            $cart = $cartRepository->createQueryBuilder('cart')
                ->where('cart.user = '.$this->getUser()->getId())
                ->andWhere('cart.deleted_at IS NULL')
                ->andWhere('cart.status = :status')
                ->setParameter('status','0')
                ->getQuery()
                ->getResult()
            ;
//            dd($cart);
            if(!empty($cart)){//if yes
                $cartNr = count($cart);
            }else{
                $cartNr = 0;
            }
        }else{//if not logged in check for cookies if exist
            if(isset($_COOKIE['product_item'])){
                $productsId = json_decode($_COOKIE['product_item'],true);
                $cartNr = count($productsId['productsId']);//temporary
            }else{
                $cartNr=0;
            }

        }
        setcookie('thanks', 0);

        return new Response($cartNr);
    }
}