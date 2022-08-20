<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function add(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getProductsAndDescription(string $search = null, string $orderBy = null)
    {
        $queryBuilder =$this->addIsAskedQueryBuilder()
            ->orderBy('p.added_at', 'ASC')
        ;


        if(!empty($search)){
            $queryBuilder->andWhere('p.title LIKE :searchTerm OR p.description LIKE :searchTerm OR p.slug LIKE :searchTerm')
                ->setParameter('searchTerm', '%'.$search.'%');
        }


        if($orderBy != null){
            switch ($orderBy){
                case 'newest':
                    $orderBy = 'p.added_at';
                    $secParam = 'DESC';
                    break;
                case 'price_low':
                    $orderBy = 'p.price';
                    $secParam = 'ASC';
                    break;
                case 'price_high':
                    $orderBy = 'p.price';
                    $secParam = 'DESC';
                    break;
            }
            $queryBuilder->orderBy($orderBy, $secParam);
        }

        $queryBuilder->leftJoin('p.comment', 'comment')
            ->innerJoin('comment.user', 'user')
            ->addSelect(['comment','user'])
        ;
        return $queryBuilder;
    }

    private function addIsAskedQueryBuilder(QueryBuilder $qb = null): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder($qb)
            ->andWhere('p.deleted_at IS NULL');
    }


    private function getOrCreateQueryBuilder(QueryBuilder $qb = null): QueryBuilder
    {
        return $qb ?: $this->createQueryBuilder('p');
    }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
