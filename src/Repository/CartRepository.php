<?php

namespace App\Repository;

use App\Entity\Cart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cart>
 *
 * @method Cart|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cart|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cart[]    findAll()
 * @method Cart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    public function add(Cart $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Cart $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getDataFiltered(string $search = null, string $category = null, string $orderedBy = null)
    {
        $queryBuilding = $this->createQueryBuilder('c')
        ->innerJoin('c.product', 'product');
        if($search){
            $queryBuilding->andWhere('product.title LIKE :searchTerm OR product.description LIKE :searchTerm')
                ->setParameter('searchTerm',  '%'.$search.'%');
        }


        if($category && $orderedBy){
            $queryBuilding->andWhere('c.status = :status')
                ->setParameter('status',  $category)
                ->orderBy('c.added_at', $orderedBy)
            ;
        }
        if($category){
            $queryBuilding->andWhere('c.status = :status')
                ->setParameter('status',  $category)
            ;
        }

        if($orderedBy){
            $queryBuilding->orderBy('c.added_at', $orderedBy);
        }
        return $queryBuilding;
    }

}
