<?php

namespace App\Repository;

use App\Entity\UserActivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method UserActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserActivity[]    findAll()
 * @method UserActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserActivity::class);
    }

    /**
     * @return UserActivity[]
     */
    public function findCommentsByPost($post) {
        $queryBuilder = $this->createQueryBuilder('ua');
        $queryBuilder->andWhere('ua.post = :post_id');
        $queryBuilder->setParameter('post_id', $post->getId());
        $queryBuilder->andWhere('ua.type = :type_enum');
        $queryBuilder->setParameter('type_enum', 'comment');
        $queryBuilder->orderBy('ua.createdAt', 'DESC');
        return $queryBuilder->getQuery()->getResult();
    }

    // /**
    //  * @return UserActivity[] Returns an array of UserActivity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserActivity
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
