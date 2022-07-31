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
    #[Route('/profile', name: 'app_profile')]
    public function profile(CartRepository $cartRepository, UserRepository $userRepository, ProductRepository $productRepository): Response
    {
        $user = $userRepository->findBy([
            'email' => $this->getUser()->getUserIdentifier()
        ]);
        $ordersStatus1 = $cartRepository->createQueryBuilder('c')
            ->where('c.user = '.$this->getUser()->getId())
            ->andWhere('c.status = 1');
        $ordersStatus2 = $cartRepository->createQueryBuilder('c')
            ->where('c.user = '.$this->getUser()->getId())
            ->andWhere('c.status = 2');

        $orderedItems = $this->loopOrders($ordersStatus1);
        $deliveredItems = $this->loopOrders($ordersStatus2);
        return $this->render('profile.html.twig', [
            'orderedItems' => $orderedItems,
            'deliveredItems' => $deliveredItems
        ]);
    }

    public function loopOrders($orderObjs)
    {
        $dateAddedAt = [];
        $orderObj = $orderObjs->getQuery()->getResult();
        foreach ($orderObj as $order) {
            $dateObj = (array) $order->getAddedAt();
            $dateAddedAt[] =$dateObj['date'];
        }
        $uniqueDates = array_unique($dateAddedAt);
        $arrayForTwigByDate=[];
        foreach ($uniqueDates as $uniqueDate) {
            $formattedDate = substr($uniqueDate,0,19);
            $orders = $orderObjs
                ->where('c.added_at LIKE :date')
                ->setParameter('date', $formattedDate)
                ->orderBy('c.product', 'ASC')
                ->getQuery()
                ->getResult()
            ;
            $titleOrders=[];
            $dateAddedAt=[];
            foreach ($orders as $key=>$order) {
                $date =substr(((array) $order->getAddedAt())['date'],0,19);//this change
                $dateAddedAt[]=$formattedDate;//this is fixed in this loop
                if($formattedDate == $date){
                    $arrayForTwigByDate[$formattedDate][] =
                        [
                            'title' => $order->getProduct()->getTitle(),
                            'address' => $order->getAddress(),
                            'phone' => '0' . $order->getPhone()
                        ];
                    $titleOrders[] = $order->getProduct()->getTitle();
                }
            }
        }

        $finalArray = [];
        foreach ($arrayForTwigByDate as $date=>$listOfArrays) {

            $unique = self::unique_multi_array($listOfArrays);
            $uniqueWithData = [$date=>$unique];
            $finalArray[]=$uniqueWithData;
        }
        return $finalArray;
    }

    function unique_multi_array($array) {
        $temp_array = array();
        $i = 0;
        $final_array = array();
        $prodTtile = [];
        foreach ($array as $item) {
            $prodTtile[]=$item['title'];
        }
        foreach($array as $key=>$val) {
            /**ATTACH TO EACH $VAL THE QUANTITY**/
            foreach (array_count_values($prodTtile) as $title=>$quantity) {
                if($val['title'] == $title){
                    $val['quantity'] = $quantity;
                }
            }
            /**END ATTACH**/

            /**FILL WITH ARRAY WITHOUT DUPLICATES IN $final_array**/
            if(count($temp_array) > 0){
                if(count(array_diff($temp_array[$i],$val)) > 0){
                    $i+=1;
                    $temp_array[]=$val;
                    $final_array[]=$val;
                };
            }else{
                $temp_array[]=$val;
                $final_array[]=$val;
            }
            /**END FILLING**/
        }
        return $final_array;
    }

    #[Route('/cancel-order', name:'app_cancel_order')]
    public function cancelOrder(UserRepository $userRepository, Request $request): Response
    {

        $user = $request->request->get('user');

        return new Response();
    }

}
