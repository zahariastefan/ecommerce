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

        $orderedItems = self::loopOrders($ordersStatus1, $cartRepository,$productRepository, 1);
        $deliveredItems = self::loopOrders($ordersStatus1, $cartRepository,$productRepository, 2);


        /**
         * [
         * title
         * quantity
         * address
         * phone
         * created_at
         * ]
         */
        return $this->render('profile.html.twig', [
            'orderedItems' => $orderedItems,
            'deliveredItems' => $deliveredItems
        ]);
    }

    public function loopOrders($orderObjs, $cartRepository, $productRepository,$status)
    {
        $dateAddedAt = [];
        $orderObj = $orderObjs->getQuery()->getResult();
        foreach ($orderObj as $order) {
            $dateObj = (array) $order->getAddedAt();
            $dateAddedAt[] =$dateObj['date'];
        }

//        dd($dateAddedAt);
        $uniqueDates = array_unique($dateAddedAt);

        $cartByData = [];
        $listProductTitle=[];
        $address='';
        $phone='';
        $date='';

        $arrayForTwigByDate=[];
        //to fill with data $orders->getQuery()->getResult(s
        foreach ($uniqueDates as $uniqueDate) {
            $formattedDate = substr($uniqueDate,0,19);
//            dd($formattedDate);
            $orders = $orderObjs
                ->where('c.added_at LIKE :date')
                ->andWhere('c.status = '.$status)
                ->setParameter('date', $formattedDate)
                ->orderBy('c.product', 'ASC')
                ->getQuery()
                ->getResult()
            ;
            $cartByData[] = $orders;
            $titleOrders=[];
            $dateAddedAt=[];
            $quantity=0;
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
        return $arrayForTwigByDate;
    }

    #[Route('/cancel-order', name:'app_cancel_order')]
    public function cancelOrder(UserRepository $userRepository, Request $request): Response
    {

        $user = $request->request->get('user');

        dd($user);


        return new Response();
    }

}
