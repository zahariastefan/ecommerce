<?php

namespace App\Controller;

use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
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
//        $status1 = $this->getResultsGroupedByDate($cartRepository,$productRepository,1,$searchTerm, $category, $orderedBy);

        $cartsByUsers = $cartRepository->createQueryBuilder('c')
            ->groupBy('c.user')
            ->getQuery()
            ->getResult()
        ;

        $allCartForEachUsers = [];
        foreach ($cartsByUsers as $cartsByUser) {
            $userId = $cartsByUser->getUser()->getId();
            $allCartForEachUsers[] = $cartRepository->createQueryBuilder('c')
                ->where('c.user = :user')
                ->setParameter('user', $userId)
                ->getQuery()
                ->getResult()
            ;
        }


        $allFilteredByUserAndData = [];
        foreach ($allCartForEachUsers as $singleCartForEachUsers) {
            foreach ($singleCartForEachUsers as $singleCartForEachUser) {
                $dateArray = (array) ($singleCartForEachUser->getAddedAt());
                $date = substr($dateArray['date'],0,19);
//                $user = $singleCartForEachUser->getUser();
                $cartByUserAndByDate = $cartRepository->createQueryBuilder('c');

                $cartByUserAndByDate = $cartByUserAndByDate
                    ->andWhere('c.added_at = :date')
                    ->setParameter('date', $date);

                if(!empty($category)){
                        $cartByUserAndByDate=$cartByUserAndByDate->andWhere('c.status = :status')
                            ->setParameter('status', $category)
                        ;
                }

                if(!empty($searchTerm)){
                    $cartByUserAndByDate = $cartByUserAndByDate
                        ->innerJoin('c.user','user')
                        ->andWhere('user.email LIKE :searchTerm OR c.address LIKE :searchTerm OR c.order_nr LIKE :searchTerm ')
                        ->setParameter('searchTerm', '%'.$searchTerm.'%');
                }

                $cartByUserAndByDate = $cartByUserAndByDate
                    ->getQuery()
                    ->getResult()
                ;

                if(!empty($cartByUserAndByDate)){
                    $allFilteredByUserAndData[$date] = $cartByUserAndByDate;
                }
            }
        }

        $finalArray = [];
        foreach ($allFilteredByUserAndData as $date => $itemsByDateAndUser) {
            $groupByProducts=[];
            $arrayCount = [];
            foreach ($itemsByDateAndUser as $itemByDateAndUser) {
                $groupByProducts[$itemByDateAndUser->getUser()->getId()][]=$itemByDateAndUser->getProduct()->getId();
                $counts = array_count_values($groupByProducts[$itemByDateAndUser->getUser()->getId()]);
            }
            foreach ($counts as $productId => $quantity) {
//                dd($quantity);
                $productObj = $productRepository->findBy([
                    'id' => $productId
                ])[0];
                $arrayCount[$itemByDateAndUser->getUser()->getUserIdentifier()][] = [
                    'productId' => $productId,
                    'quantity' => $quantity,
                    'productTitle'=>$productObj->getTitle(),
                    'address' => $itemByDateAndUser->getAddress(),
                    'phone' => $itemByDateAndUser->getPhone(),
                    'status' => $itemByDateAndUser->getStatus(),
                    'orderNr' => $itemByDateAndUser->getOrderNr()
                ];
            }
            $finalArray[$date]=$arrayCount;
        }
        if(!empty($orderedBy)){
            if($orderedBy == 'DESC'){
                krsort($finalArray);//descending
            }else{
                ksort($finalArray);//ascending
            }
        }
        return $this->render('admin_page/index.html.twig', [
            'orderedItems' => $finalArray,
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
    public function changeStatus(CartRepository $cartRepository, Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $idProduct = $request->request->get('idProduct');
        $cartItemId = $request->request->get('cartItemId');
        $status = $request->request->get('status');
        $email = $request->request->get('email');
        $category = $request->request->get('category');
        $all = $request->request->get('all');
        $fromStatus = $request->request->get('fromStatus');
        $refundQ = $request->request->get('refundQ');

//        dd($from);
        if(!empty($email)){
            $user = $userRepository->findBy(['email' => $email])[0];
        }else{
            $user = $userRepository->findBy(['email' => $this->getUser()->getUserIdentifier()])[0];
        }
//        dd($user);
        $cart = $cartRepository->createQueryBuilder('c')
            ->andWhere('c.user = :userId')
            ->setParameter('userId', $user->getId());


        $cart=$cart ->andWhere('c.status != :status')
            ->setParameter('status', $status );

        if(!empty($fromStatus)){
            $cart=$cart ->andWhere('c.status = :fromStatus')
                ->setParameter('fromStatus', $fromStatus );
        }

        if(!empty($category)){
            $cart = $cart
                ->andWhere('c.status = :category')
                ->setParameter('category', $category );
        }

        if(!empty($idProduct)){
            $cart = $cart
                ->andWhere('c.product = :prodId')
                ->setParameter('prodId', $idProduct);
        }
        if(!empty($cartItemId)){
            $cart = $cart
                ->andWhere('c.product = :cartItemId')
                ->setParameter('cartItemId', $cartItemId);
        }


        $cart = $cart->getQuery()
            ->getResult();
//        dd($cart);

//        dd($cart[0]->getProduct()->getTitle());
        if(!empty($all)){
//            $cart = $cart->andWhere('')
            foreach ($cart as $item) {
                $item->setStatus($status);
                $item->setUpdatedAt(new \DateTimeImmutable());
//                if($status == 2){
//                    if(!empty($item->getDeliveredAt())){
////                        $item->setDeliveredAt(new \DateTimeImmutable());
//                    }
//                }
//                if($status == 4){
//                    if(!empty($item->getDeletedAt())) {
//                        $item->setDeletedAt(new \DateTimeImmutable());
//                    }
//                }
                $entityManager->persist($item);
            }

        }else{
//            dd(count($cart));

            if(empty($refundQ)) $refundQ = 1;
            $x=0;
            foreach ($cart as $singleCart) {
                $singleCart->setStatus($status);
                $singleCart->setUpdatedAt(new \DateTimeImmutable());
                if($status == 2){
                    if(!empty($singleCart->getDeliveredAt())){
                        $singleCart->setDeliveredAt(new \DateTimeImmutable());
                    }
                }
                if($status == 4){
                    if(!empty($singleCart->getDeletedAt())) {
                        $singleCart->setDeletedAt(new \DateTimeImmutable());
                    }
                }
                $entityManager->persist($singleCart);
                $x++;
                if($x == intval($refundQ)) break;
            }

        }

        $entityManager->flush();
        return new Response();
    }
}
