<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class CartController extends AbstractController
{

    #[Route('/cart', name:'app_cart')]
    public function cart(UserRepository $userRepository, CartRepository $cartRepository, ProductRepository $productRepository)
    {
        if(!$this->getUser()) return new Response('no logged in');
        $user = $userRepository->findBy([
            'id' => $this->getUser()->getId()
        ]);
        $cart = $cartRepository->findBy([
            'user' => $user
        ]);
        $cart = $cartRepository->createQueryBuilder('c')
            ->where('c.user = '.$this->getUser()->getId())
            ->andWhere('c.status = 0')
            ->getQuery()
            ->getResult()
        ;
//        dd($cart->getQuery()->getResult());
        $listOfTitles = [];
        foreach ($cart as $singleCart){
            $productsFromCart = $productRepository->findBy([
                'id' => $singleCart->getProduct()->getId()
            ]);
            //select only cart with status 0!!
            if(gettype($singleCart) == 'object'){
                $title = $productsFromCart[0]->getTitle();
                if(!empty($title)){
                    $listOfTitles[] = $title;
                }
            }
        }
        return $this->render('cart.html.twig', [
            'productsInCart' => array_count_values($listOfTitles)
        ]);
    }

    #[Route('/checkout', name:'app_checkout')]
    public function checkout()
    {
        return $this->render('checkout.html.twig');
    }

    #[Route('/thanks', name:'app_thanks')]
    public function thanks(Request $request, ProductRepository $productRepository, UserRepository $userRepository, EntityManagerInterface $entityManager, CartRepository $cartRepository)
    {
        $user = $userRepository->findBy([
            'email' => $this->getUser()->getUserIdentifier()
        ]);
        $date = $request->request->get('dateTime');
        $data = $request->query->all();
//        dd($data);
        $allItems = [];
        //starting adding products to order
        foreach ($data as $key => $oneData) {
            $array = json_decode($oneData, true);
            if(str_contains($key, 'item') ){//if is a product
                $product = $productRepository->findBy([
                    'title' => $array['item']
                ]);
                $cart = $cartRepository
                    ->createQueryBuilder('c')
                    ->orderBy('c.added_at','DESC')
                    ->where('c.user = '.$user[0]->getId())
                    ->andWhere('c.status = 0')
                    ->getQuery()->getResult();
                $allItems[] = $product;
                foreach ($cart as $item) {
//                    dd($data);
                    //here I have to change status of cart product
                    $item->setStatus(1);
                    $item->setPhone($data['phone']);
                    $item->setAddress($data['city'].' '.$data['street'].' '.$data['number'].' '.$data['building']);
                    $item->setAddedAt(new \DateTimeImmutable());
                    $entityManager->persist($item);
                }

            }
        }
        $user[0]->setUniqueNr(rand(123456,123456789));
        $entityManager->persist($user[0]);
        $entityManager->flush();
        return $this->render('thanks.html.twig');
    }
}