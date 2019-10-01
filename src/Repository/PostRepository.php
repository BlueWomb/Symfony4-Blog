<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @return Post[]
     */
    public function findByParams($page, $limit, $category_id, $search_key) {
        $queryBuilder = $this->createQueryBuilder('bp');
        $queryBuilder->orderBy('bp.createdAt', 'DESC');
        $queryBuilder->setFirstResult($limit * ($page - 1));
        $queryBuilder->setMaxResults($limit);

        if ($category_id >= 0 && $queryBuilder != null) {
            $queryBuilder->andWhere('bp.category = :category');
            $queryBuilder->setParameter('category', $category_id);
        }

        if($search_key != null && $queryBuilder != null) {
            $queryBuilder->andWhere('bp.title like :search_key');
            $queryBuilder->setParameter('search_key', '%'.addcslashes($search_key, '%_').'%');
        }

        $queryBuilder->andWhere('bp.isVisible = :boolean');
        $queryBuilder->setParameter('boolean', 1);
        
        return $queryBuilder->getQuery()->getResult();
    }
}
