<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

define('POST_LIMIT', 12);
define('POST_LIMIT_MOST_POPULAR', 4);

class BlogController extends AbstractController
{
    private $encoder;
    private $normalizer;
    private $serializer;

    private $entityManager;
    private $authorRepository;
    private $postRepository;
    private $categoryRepository;
    private $tagRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->encoder = new JsonEncoder();
        $this->normalizer = new ObjectNormalizer();
        $this->normalizer->setIgnoredAttributes(array('posts'));
        $this->serializer = new Serializer(array($this->normalizer), array($this->encoder));

        $this->entityManager = $entityManager;
        $this->authorRepository = $entityManager->getRepository('App:Author');
        $this->postRepository = $entityManager->getRepository('App:Post');
        $this->categoryRepository = $entityManager->getRepository('App:Category');
        $this->tagRepository = $entityManager->getRepository('App:Tag');
    }

    /**
     * @Route("/")
     * 
     * @Route("/index", name="index")
     * @Route("/index/{page}/{category_id}", name="index_with_params")
     */
    public function indexAction($page = 1, $category_id = -1)
    {
        return $this->render('index.html.twig', [
            'posts' => $this->get_all_posts($page, POST_LIMIT, $category_id),
            'most_popular_posts' => $this->get_all_posts(1, POST_LIMIT_MOST_POPULAR, $category_id),
            'pages' => count($this->postRepository->findAll()) / POST_LIMIT,
            'tags' => $this->categoryRepository->findAll(),
            'categories' => $this->categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/filter_by_category", name="filter_by_category", methods={"GET","HEAD"})
     */
    public function filterByCategoryAction()
    {
        $request = Request::createFromGlobals();
        $page = $request->query->get('page');
        $category_id = $request->query->get('category_id');

        $jsonContent = $this->serializer->serialize($this->get_all_posts($page, POST_LIMIT, $category_id), 'json');
        $response = new JsonResponse();
        $response->setData($jsonContent);
        
        return $response;
    }

    public function get_all_posts($page, $limit, $category_id)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('bp')
            ->from('App:Post', 'bp')
            ->orderBy('bp.id', 'DESC')
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        if ($category_id >= 0 && $queryBuilder != null) {
            $queryBuilder->where('bp.category = :category');
            $queryBuilder->setParameter('category', $category_id);
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
