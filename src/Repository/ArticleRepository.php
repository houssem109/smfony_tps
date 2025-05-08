<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }
    public function findByNom(?string $nom): array
{
    if (!$nom) {
        return $this->findAll();
    }

    return $this->createQueryBuilder('a')
        ->where('a.nom LIKE :nom')
        ->setParameter('nom', '%' . $nom . '%')
        ->getQuery()
        ->getResult();
}
public function findByPriceRange(?float $minPrice, ?float $maxPrice): array
{
    $qb = $this->createQueryBuilder('a');

    if ($minPrice !== null) {
        $qb->andWhere('a.prix >= :minPrice')
           ->setParameter('minPrice', $minPrice);
    }

    if ($maxPrice !== null) {
        $qb->andWhere('a.prix <= :maxPrice')
           ->setParameter('maxPrice', $maxPrice);
    }

    return $qb->getQuery()->getResult();
}

    //    /**
    //     * @return Article[] Returns an array of Article objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Article
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
