<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/index", name="index")
     * 
     * @Route("/index/{type}/{page}/{category_id}/{search_key}", name="index_with_params_json", options = { "expose" = true })
     */
    public function indexAction($type = 'default', $page = 1, $category_id = -1, $search_key = null)
    {
        if(strcmp($type, "default") === 0) {
            return $this->makeTemplateResponse($page, $category_id, $search_key);
        }

        if (strcmp($type, "json") === 0) {
            return $this->makeJsonResponse($page, $category_id, $search_key);
        }
    }

    /**
     * @Route("/single/{id}", name="single")
     * 
     * @Route("/index/{type}/{id}", name="single_json", options = { "expose" = true })
     */
    public function singleAction($type = 'default', $id)
    {
        if(strcmp($type, "default") === 0) {
            return $this->render('single.html.twig', [
                'post' => $this->postRepository->findById($id),
                'most_popular_posts' => $this->get_all_posts(1, POST_LIMIT_MOST_POPULAR, -1, null),
                'related_posts' => $this->get_all_posts(1, POST_LIMIT_MOST_POPULAR, -1, null),
                'tags' => $this->tagRepository->findAll(),
                'categories' => $this->categoryRepository->findAll(),
            ]);
        }
    }

    public function makeTemplateResponse($page, $category_id, $search_key) {
        return $this->render('index.html.twig', [
            'posts' => $this->get_all_posts($page, POST_LIMIT, $category_id, $search_key),
            'most_popular_posts' => $this->get_all_posts(1, POST_LIMIT_MOST_POPULAR, $category_id, $search_key),
            'pages' => count($this->postRepository->findAll()) / POST_LIMIT,
            'tags' => $this->tagRepository->findAll(),
            'categories' => $this->categoryRepository->findAll(),
        ]);
    }

    public function makeJsonResponse($page, $category_id, $search_key)
    {
        $data = $this->get_all_posts($page, POST_LIMIT, $category_id, $search_key);
        $jsonContent = $this->serializer->serialize($data, 'json');
        $response = new JsonResponse();
        $response->setData(['data' => $jsonContent, 'pages' => strval(count($data) / POST_LIMIT)]);
        
        return $response;
    }

    public function get_all_posts($page, $limit, $category_id, $search_key)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('bp')
            ->from('App:Post', 'bp')
            ->orderBy('bp.id', 'DESC')
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        if ($category_id >= 0 && $queryBuilder != null) {
            $queryBuilder->andWhere('bp.category = :category');
            $queryBuilder->setParameter('category', $category_id);
        }

        if($search_key != null && $queryBuilder != null) {
            $queryBuilder->andWhere('bp.title like :search_key');
            $queryBuilder->setParameter('search_key', '%'.addcslashes($search_key, '%_').'%');
        }
        
        return $queryBuilder->getQuery()->getResult();
    }
}
