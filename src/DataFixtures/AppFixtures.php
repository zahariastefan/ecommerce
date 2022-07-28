<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Product;
use App\Entity\User;
use App\Factory\CommentFactory;
use App\Factory\ProductFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
//        // $manager->persist($product);
        UserFactory::createOne(['email' => 'abraca_admin@example.com',
            'roles'=>['ROLE_ADMIN', 'ROLE_USER']]);
//        $users = UserFactory::createMany(10);
//
//        $description = DescriptionContentFactory::createMany(100);
//
//        ProductsFactory::createMany(20, function() use ($description){
//            return [
//                'descriptionContent' => $description
//            ];
//        });
//
//       $userComments = UserCommentsFactory::createMany(20, function() use ($users){
//            return [
//                'product' => ProductsFactory::random(),
////                'user' => UserFactory::random()->addUserComment(UserCommentsFactory::random()->object())
//                'user' => $users
//            ];
//        });
//
//        $userComments = UserCommentsFactory::createMany(20, function() use ($users){
//            return [
//                'product' => ProductsFactory::random(),
////                'user' => UserFactory::createOne()
////                'user' => UserFactory::random()->addUserComment(UserCommentsFactory::random()->object())
////                'user' => UserCommentsFactory::random()->addUser(UserFactory::random()->object())
//            ];
//        });
//
////        $manager->persist($userComments)

//        $user = new User();
//        $user->setEmail(UserFactory::faker()->email());
//        $user->setFirstName(UserFactory::faker()->firstName);
//        $user->setPassword('tada');
//        $user->setRoles(['ROLE_USER']);
//
//        $product = new Product();
//        $product->setDescription(ProductFactory::faker()->text(100));
//
//
//        $comment = new Comment();
//        $comment->setContent(CommentFactory::faker()->text(30));
//        $comment->addUser($user);
//        $comment->addProduct($product);

        self::factoryUserProductComment($manager, 20);

        $manager->flush();
    }

    public function factoryUserProductComment($manager, $length)
    {
        for($x=0;$x<$length;$x++){
//            $user = new User();
//            $user->setEmail(UserFactory::faker()->email());
//            $user->setFirstName(UserFactory::faker()->firstName);
//            $user->setPassword('tada');
//            $user->setRoles(['ROLE_USER']);

            $user = UserFactory::createOne();

            $product = new Product();
            $product->setDescription(ProductFactory::faker()->text(100));
            $product->setTitle(ProductFactory::faker()->firstName());
            $product->setSlug(ProductFactory::faker()->slug(6));
            $product->setAddedAt(\DateTimeImmutable::createFromMutable(ProductFactory::faker()->dateTimeBetween('-1 year')));
            $product->setUpdatedAt(\DateTimeImmutable::createFromMutable(ProductFactory::faker()->dateTimeBetween('-1 year')));


            $comment = new Comment();
            $comment->setContent(CommentFactory::faker()->text(30));
            $comment->setAddedAt(\DateTimeImmutable::createFromMutable(CommentFactory::faker()->dateTimeBetween('-1 year')));
            $comment->setUpdatedAt(\DateTimeImmutable::createFromMutable(CommentFactory::faker()->dateTimeBetween('-1 year')));
            $comment->addUser($user->object());
            $comment->addProduct($product);


            $manager->persist($user->object());
            $manager->persist($product);
            $manager->persist($comment);
        }


    }
}
