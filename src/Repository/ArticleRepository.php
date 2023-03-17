<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
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
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Article $entity, bool $flush = false): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Article $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
    /**
     * Return all article per page
     * @return void
     */
    public function getPaginateArticle($page, $limit, $filters = null){
        $query = $this->createQueryBuilder('a');

        // On filtres les données
        if($filters != null){
            $query->Where('a.category IN(:cats)')
                ->setParameter(':cats', array_values($filters));
        }

            $query->orderBy('a.createdAt')
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit)
            ;
        return $query->getQuery()->getResult();
    }

    /**
     * Returns number of  article
     * @return void
     */
    public function getTotalArticle($filters = null){
        $query = $this->createQueryBuilder('a')
                      ->select('COUNT(a)');
            //On filtre les données
            if($filters != null){
                $query->where('a.category IN(:cats)')
                       ->setParameter(':cats', array_values($filters))
                ;
            }
        
        return $query->getQuery()->getSingleScalarResult();
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
