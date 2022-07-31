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

class ProfileController extends AbstractController
{
//    #[Route('/profile', name: 'app_profile')]
//    public function profile(CartRepository $cartRepository, UserRepository $userRepository, ProductRepository $productRepository): Response
//    {
//        $queryBuilder = $cartRepository->createQueryBuilder('c');
//        $orders = $queryBuilder
//            ->where('c.user = '.$this->getUser()->getId())
//        ;
//
//        $orderedItems = $this->deleteDuplicates($orders, 1);
////        $deliveredItems = $this->deleteDuplicates($orders, 2);
//
////        dd($orderedItems);
//        return $this->render('profile.html.twig', [
//            'orderedItems' => $orderedItems,
////            'deliveredItems' => $deliveredItems
//        ]);
//    }
//
//    /**This function return Array with all ordered Orders without repetition and By date **/
//    public function deleteDuplicates($orderObjs)
//    {
//        $dateAddedAt = [];//list of all dates
//        $orderObj = $orderObjs->getQuery()->getResult();
//        foreach ($orderObj as $order) {
//            $dateObj = (array) $order->getAddedAt();
//            $dateAddedAt[] =$dateObj['date'];
//        }
//        $uniqueDates = array_unique($dateAddedAt);
//        $arrayForTwigByDate=[];//date => products (with duplicates)
//        foreach ($uniqueDates as $uniqueDate) {
//            $formattedDate = substr($uniqueDate,0,19);
//            /**getting all Cart By Date**/
//            $orders = $orderObjs
//                ->where('c.added_at LIKE :date')
//                ->andWhere('c.deleted_at IS NULL')
//                ->setParameter('date', $formattedDate)
//                ->orderBy('c.product', 'ASC')
//                ->getQuery()
//                ->getResult()
//            ;
//            foreach ($orders as $key=>$order) {
//                if($order->getDeliveredAt() != null){
//                    $deliveredDate = substr(((array) $order->getDeliveredAt())['date'],0,19);
//                }else{
//                    $deliveredDate = null;
//                }
//                $date =substr(((array) $order->getAddedAt())['date'],0,19);//this change
//                if($formattedDate == $date){
//                    $arrayForTwigByDate[$formattedDate][] =
//                        [
//                            'title' => $order->getProduct()->getTitle(),
//                            'address' => $order->getAddress(),
//                            'phone' => '0' . $order->getPhone(),
//                            'delivered_at' => $deliveredDate,
//                            'status' => $order->getStatus()
//                        ];
//                }
//            }
//        }
//        $finalArray = [];
//        foreach ($arrayForTwigByDate as $date=>$listOfArrays) { //that loop , in help of other function, delete duplicate
//            /**
//             *
//            the format of $listOfArrays are:
//            array:10 [▼
//            0 => array:3 [▶]
//            1 => array:3 [▼
//                "title" => "TitleName"
//                "address" => "adress sdjasni"
//                "phone" => "0744541254"
//            ]
//            2 => array:3 [▶]
//             */
//            $unique = self::unique_multi_array($listOfArrays);
//            $uniqueWithData = [$date=>$unique];
//            $finalArray[]=$uniqueWithData;
//        }
//        return $finalArray;
//    }
//
//    /**
//     * This Function Delete Duplicates and Add Quantity
//    */
//    public function unique_multi_array($array) {
//        $temp_array = array();
//        $i = 0;
//        $final_array = array();
//        $prodTtile = [];
//        foreach ($array as $item) {
//            $prodTtile[]=$item['title'];
//        }
//        foreach($array as $key=>$val) {
//            /**ATTACH TO EACH $val THE QUANTITY that exist in array_count_values($prodTtile)**/
//            foreach (array_count_values($prodTtile) as $title=>$quantity) {
//                if($val['title'] == $title){
//                    $val['quantity'] = $quantity;
//                }
//            }
//            /**END ATTACH**/
//
//            /**FILL WITH ARRAY WITHOUT DUPLICATES IN $final_array**/
//            if(count($temp_array) > 0){
//                if(count(array_diff($temp_array[$i],$val)) > 0){//if the same array will be Empty []
//                    $i+=1;
//                    $temp_array[]=$val;
//                    $final_array[]=$val;
//                };
//            }else{
//                $temp_array[]=$val;
//                $final_array[]=$val;
//            }
//            /**END FILLING**/
//        }
//        return $final_array;
//    }

    #[Route('/profile', name: 'app_profile')]
    public function profile(CartRepository $cartRepository, ProductRepository $productRepository): Response
    {
        $status1 = $this->getResultsGroupedByDate($cartRepository,$productRepository,1);
        $status2 = $this->getResultsGroupedByDate($cartRepository,$productRepository,2);
        return $this->render('profile.html.twig', [
            'orderedItems' => $status1,
            'deliveredItems' => $status2
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
            if(count($getOrders) > 0){
                $getOrders[0]->quantity = $quantity;
                $date = (array) ($getOrders[0]->getAddedAt());
                $arrayGroupedByDate[] = [substr($date['date'],0,19) => $getOrders];
            }
        }
        return $arrayGroupedByDate;
    }








    #[Route('/cancel-order', name:'app_cancel_order')]
    public function cancelOrder(UserRepository $userRepository, Request $request): Response
    {

        $user = $request->request->get('user');

        return new Response();
    }

}
