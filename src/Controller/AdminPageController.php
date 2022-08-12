<?php

namespace App\Controller;

use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPageController extends AbstractController
{
    #[Route('/admin/page', name: 'admin_dashboard')]
    public function index(CartRepository $cartRepository, Request $request, ProductRepository $productRepository): Response
    {
        $searchTerm = $request->query->get('searchTerm');
        $category = $request->query->get('category');
        $orderedBy = $request->query->get('orderBy');
        $status1 = $this->getResultsGroupedByDate($cartRepository,$productRepository,1,$searchTerm, $category, $orderedBy);
        return $this->render('admin_page/index.html.twig', [
            'orderedItems' => $status1,
        ]);
    }
    public function getResultsGroupedByDate($cartRepository,$productRepository,$status, $searchTerm, $category, $orderedBy)
    {
        $queryBuilder = $cartRepository->getDataFiltered($searchTerm, $category, $orderedBy);
        $ordered = $queryBuilder
            ->getQuery()
            ->getResult();
        //array with all product name
        $allTitles=[];
        foreach ($ordered as $singleOrder) {
            $allTitles[] = $singleOrder->getProduct()->getTitle();
        }
        //use array_count_values
        $countUnique = array_count_values($allTitles);
        $arrayGroupedByDate=[];
        //get one product with status 1 and add to it the quantity
        foreach ($countUnique as $productTitle => $quantity) {
            $prodObject = $productRepository->createQueryBuilder('p')
                ->where('p.deleted_at IS NULL')
                ->andWhere('p.title = :product')
                ->setParameter('product', $productTitle)
                ->getQuery()
                ->getResult();
            $getOrders = $queryBuilder
                ->andWhere('c.product = :product')
                ->setParameter('product', $prodObject)
                ->groupBy('c.added_at')
                ->getQuery()
                ->getResult();
            if(!empty($getOrders)){
                foreach ($getOrders as $getOrder) {
                    $getOrder->quantity = $quantity;
                }
                $date = (array) ($getOrders[0]->getAddedAt());
                $arrayGroupedByDate[] = [substr($date['date'],0,19) => $getOrders];
            }
        }
        return $arrayGroupedByDate;
    }


    #[Route('/deliver-order', name:'app_change_status')]
    public function changeStatus(CartRepository $cartRepository, Request $request, EntityManagerInterface $entityManager)
    {
        $idProduct = $request->request->get('idProduct');
        $status = $request->request->get('status');
        $cart = $cartRepository->createQueryBuilder('c')
            ->where('c.id = :id')
            ->setParameter('id', $idProduct)
        ->getQuery()
        ->getResult();
        $cart[0]->setStatus($status);
        $entityManager->persist($cart[0]);
        $entityManager->flush();
        return new Response();
    }
}
