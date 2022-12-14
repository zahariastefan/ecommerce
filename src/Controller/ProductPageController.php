<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Comment;
use App\Entity\Product;
use App\Entity\User;
use App\Factory\CommentFactory;
use App\Factory\ProductFactory;
use App\Factory\UserFactory;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductPageController extends  AbstractController
{
    #[Route('/product-page/{slug}', name:'app_product_page')]
    public function productPage($slug,ProductRepository $productRepository)
    {
        $queryBuilder = $productRepository->getProductsAndDescription($slug)->getQuery()->getResult();
        /*
        // here is to create One Comment to each refresh to see more comments
        $user = $userRepository->findBy([
            'id' => 21
        ]);

        $product = $queryBuilder[0];

        $comment = new Comment();
        $comment->setContent(CommentFactory::faker()->text(30));
        $comment->setAddedAt(\DateTimeImmutable::createFromMutable(CommentFactory::faker()->dateTimeBetween('-1 year')));
        $comment->setUpdatedAt(\DateTimeImmutable::createFromMutable(CommentFactory::faker()->dateTimeBetween('-1 year')));
        $comment->addUser($user[0]);
        $comment->addProduct($product);


        $manager->persist($user[0]);
        $manager->persist($product);
        $manager->persist($comment);
        $manager->flush();
        */
        return $this->render('product_page.html.twig',[
            'product' => $queryBuilder[0]
        ]);
    }

    #[Route('/comment', name:'app_comment')]
    public function comment()
    {
        return new Response('hello world2');
    }

}