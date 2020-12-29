<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @return Article[] Returns an array of the last 10 Article objects
     */
    
    public function find10lastArticles() : array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.published', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
    
    /**
     * @return Article[] Returns an array of the last 10 Article objects
     */
    
    public function find5lastArticles() : array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.published', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Article Returns an Article objects where slug = $url
     */
    
    public function findArticleByUrlAlias(string $url) : object
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.slug = :val')
            ->setParameter('val', $url)
            ->getQuery()
            ->getOneOrNullResult();
    }


    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
