<?php

namespace App\Controller;

use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function profile(UserRepository $userRepository): Response
    {
//        $user= $userRepository
        return $this->render('profile.html.twig');
    }

    #[Route('/orders', name: 'app_orders')]
    public function orders(CartRepository $cartRepository,
                            ProductRepository $productRepository): Response
    {
        $status1 = $this->getResultsGroupedByDate($cartRepository,$productRepository,1);
        $status2 = $this->getResultsGroupedByDate($cartRepository,$productRepository,2);
        $status3 = $this->getResultsGroupedByDate($cartRepository,$productRepository,3);
        $status4 = $this->getResultsGroupedByDate($cartRepository,$productRepository,4);
        return $this->render('orders.html.twig', [
            'orderedItems' => $status1,
            'deliveredItems' => $status2,
            'refundedItems' => $status3,
            'canceledItems' => $status4
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

    #[Route('/cancel-order', name:'app_cancel_order')]
    public function cancelOrder(UserRepository $userRepository, Request $request, CartRepository $cartRepository, EntityManagerInterface $entityManager): Response
    {

        $user = $request->request->get('user');

        $user = $userRepository->findBy([
            'email' => $user
        ])[0];
        //search in Cart For user_id and status 1  and change status to 4 and add deleted_at

        $id = $request->request->get('cartItemId');
//        dd($id);
        $carts = $cartRepository->createQueryBuilder('c')
            ->where('c.user = :user')
            ->setParameter('user', $user)
            ->andWhere('c.status = 1')
            ->getQuery()
            ->getResult();

        foreach ($carts as $cart) {
            $cart->setStatus('4');
            $cart->setDeletedAt(new \DateTimeImmutable());
            $entityManager->persist($cart);
        }

        $entityManager->flush();
        return new Response();
    }


    #[Route('/refund-product', name:'app_change_status')]
    public function refundProduct(UserRepository $userRepository,
                                  Request $request,
                                  CartRepository $cartRepository,
                                  EntityManagerInterface $entityManager
    ): Response
    {
        $productId = $request->request->get('cartItemId');
        $refundQ = $request->request->get('refundQ');
        $status = $request->request->get('status');
        $cart = $cartRepository->createQueryBuilder('c')
            ->where('c.user = :user')
            ->setParameter('user', $this->getUser()->getId())
            ->andWhere('c.product = :product')
            ->setParameter('product', $productId)
            ->andWhere('c.status = 1')
            ->getQuery()
            ->getResult()
        ;

        $x=0;

        foreach ($cart as $item) {
            $item->setStatus($status);
            $entityManager->persist($item);
            $x++;
            if($x == intval(($refundQ))) break;
        }
        $entityManager->flush();
        return new Response();
    }

    #[Route('/update-data', name:'app_update_user_data')]
    public function updateUserData(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $data = $request->query->all();
        $user = $this->getUser();
        $user = $userRepository->findBy([
            'email' => $user->getUserIdentifier()
        ])[0];

        if($user){
            foreach ($data as $key => $datum) {
                if(!empty($datum)){
                    //if name
                    if($key == 'name'){
                        $user->setName($data['name']);
                        $user->setSurname($data['surname']);
                    }
                    //if city
                    if($key == 'city'){
                        $user->setAddress($data['city'] . ' ' . $data['street'] . ' ' . $data['number'] . ' ' . $data['building']);
                        $user->setCity($data['city']);
                        $user->setStreet($data['street']);
                        $user->setStreetNumber($data['number']);
                        $user->setBuilding($data['building']);
                    }
                    //if phone
                    if($key == 'phone'){
                        $user->setPhone($data['phone']);
                    }
                    //if email
                    if($key == 'email'){
                        $user->setEmail($data['email']);
                    }
                    //if pass
                    if($key == 'pass'){
                        $user->setPlainPassword($data['pass']);
                        $user->setPassword(
                            $passwordHasher->hashPassword($user, $user->getPlainPassword())
                        );
                    }
                    $entityManager->persist($user);
                }
            }
            $entityManager->flush();
        }

        return  $this->redirectToRoute('app_profile');
    }
}
