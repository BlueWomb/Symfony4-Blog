<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

define('POST_LIMIT', 12);

class BlogController extends AbstractController
{
    private $entityManager;
    private $authorRepository;
    private $postRepository;
    private $categoryRepository;
    private $tagRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->authorRepository = $entityManager->getRepository('App:Author');
        $this->postRepository = $entityManager->getRepository('App:Post');
        $this->categoryRepository = $entityManager->getRepository('App:Category');
        $this->tagRepository = $entityManager->getRepository('App:Tag');
    }

    /**
     * @Route("/index/{page}", name="index")
     * @Route("/")
     */
    public function indexAction($page = 1)
    {
        return $this->render('index.html.twig', [
            'posts' => $this->get_all_posts($page, POST_LIMIT),
            'most_popular_posts' => $this->get_all_posts(1, 4),
            'pages' => count($this->postRepository->findAll()) / POST_LIMIT,
            'tags' => $this->categoryRepository->findAll(),
            'categories' => $this->categoryRepository->findAll(),
        ]);
    }

    public function get_all_posts($page = 1, $limit = 5)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('bp')
            ->from('App:Post', 'bp')
            ->orderBy('bp.id', 'DESC')
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        return $queryBuilder->getQuery()->getResult();
    }
}
