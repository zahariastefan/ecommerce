<?php

namespace App\Controller;

use App\Repository\CartRepository;
use App\Repository\ProductRepository;
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

        $cart = $cartRepository->getDataFiltered($searchTerm)->getQuery()->getResult();

        $status1 = $this->getResultsGroupedByDate($cartRepository,$productRepository,1);
        $status2 = $this->getResultsGroupedByDate($cartRepository,$productRepository,2);
        $status3 = $this->getResultsGroupedByDate($cartRepository,$productRepository,3);
        return $this->render('admin_page/index.html.twig', [
            'all_carts' => $cart,
            'orderedItems' => $status1,
            'deliveredItems' => $status2,
            'refundedItems' => $status3
        ]);
    }
    public function getResultsGroupedByDate($cartRepository,$productRepository,$status)
    {
        $queryBuilder = $cartRepository->createQueryBuilder('c')
            ->where('c.deleted_at IS NULL')
            ->andWhere('c.status = :status')
            ->setParameter('status', $status);
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

}
