<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Product;
use App\Entity\UrlImage;
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
        UserFactory::createOne([
            'email' => 'abraca_admin@example.com',
            'roles'=>['ROLE_ADMIN', 'ROLE_USER'],
            'is_verified' => 1
        ]);

        self::factoryUserProductComment($manager, 20);

        $manager->flush();
    }

    public function factoryUserProductComment($manager, $length)
    {
        for($x=0;$x<$length;$x++){
            $user = UserFactory::createOne();
            $urlImage = new UrlImage();
            $urlImage->setUrl('/tablou.jpg');

            $product = new Product();
            $product->setDescription(ProductFactory::faker()->text(100));
            $product->setTitle(ProductFactory::faker()->firstName());
            $product->setSlug(ProductFactory::faker()->slug(6));
            $product->setUrlImage($urlImage);
            $product->setAddedAt(\DateTimeImmutable::createFromMutable(ProductFactory::faker()->dateTimeBetween('-1 year')));
            $product->setUpdatedAt(\DateTimeImmutable::createFromMutable(ProductFactory::faker()->dateTimeBetween('-1 year')));
            $product->setPrice(ProductFactory::faker()->numberBetween(20,500));
            $urlImage->setProduct($product);


            $comment = new Comment();
            $comment->setContent(CommentFactory::faker()->text(30));
            $comment->setAddedAt(\DateTimeImmutable::createFromMutable(CommentFactory::faker()->dateTimeBetween('-1 year')));
            $comment->setUpdatedAt(\DateTimeImmutable::createFromMutable(CommentFactory::faker()->dateTimeBetween('-1 year')));
            $comment->addUser($user->object());
            $comment->addProduct($product);



            $manager->persist($user->object());
            $manager->persist($urlImage);
            $manager->persist($product);
            $manager->persist($comment);
        }


    }
}
