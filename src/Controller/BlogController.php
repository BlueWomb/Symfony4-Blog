<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

use App\Entity\UserActivity;

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
    private $userActivityRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->encoder = new JsonEncoder();
        $this->normalizer = new ObjectNormalizer();
        $this->normalizer->setIgnoredAttributes(array('posts', 'userActivity'));
        $this->serializer = new Serializer(array($this->normalizer), array($this->encoder));

        $this->entityManager = $entityManager;
        $this->authorRepository = $entityManager->getRepository('App:Author');
        $this->postRepository = $entityManager->getRepository('App:Post');
        $this->categoryRepository = $entityManager->getRepository('App:Category');
        $this->tagRepository = $entityManager->getRepository('App:Tag');
        $this->userActivityRepository = $entityManager->getRepository('App:UserActivity');
    }

    /**
     * @Route("/")
     * @Route("/index", name="index", options = { "expose" = true })
     * @Route("/index/{type}/{page}/{category_id}/{search_key}", name="index_with_params", options = { "expose" = true })
     */
    public function indexAction($type = 'default', $page = 1, $category_id = -1, $search_key = null)
    {
        $posts = $this->postRepository->findByParams($page, POST_LIMIT, $category_id, $search_key);
        $pages = ($category_id === -1 && $search_key === null ? count($this->postRepository->findAll()) : count($posts)) / POST_LIMIT;

        $data = [
            'posts' => $posts, 'most_popular_posts' => $this->postRepository->findByParams(1, POST_LIMIT_MOST_POPULAR, $category_id, $search_key),
            'pages' => $pages, 'tags' => $this->tagRepository->findAll(),
            'categories' => $this->categoryRepository->findAll()
        ];

        if (strcmp($type, "default") === 0) {
            return $this->makeTemplateResponse('index.html.twig', $data);
        }

        if (strcmp($type, "json") === 0) {
            return $this->makeJsonResponse(array($data['posts'], $data['pages']));
        }
    }

    /**
     * @Route("/single/{id}", name="single", options = { "expose" = true })
     * @Route("/single/{type}/{id}", name="single_json", options = { "expose" = true })
     */
    public function singleAction($type = 'default', $id)
    {
        $post = $this->postRepository->findById($id)[0];
        $data = [
            'post' => $post,
            'most_popular_posts' => $this->postRepository->findByParams(1, POST_LIMIT_MOST_POPULAR, -1, null),
            'related_posts' => $this->postRepository->findByParams(1, POST_LIMIT_MOST_POPULAR, -1, null),
            'tags' => $this->tagRepository->findAll(),
            'categories' => $this->categoryRepository->findAll(),
            'comments' => $this->userActivityRepository->findCommentsByPost($post)
        ];

        if ($data['post'] != null)
            $this->createView(Request::createFromGlobals(), $data['post']);

        if (strcmp($type, "default") === 0) {
            return $this->makeTemplateResponse('single.html.twig', $data);
        }

        if (strcmp($type, "json") === 0) {
            return $this->makeJsonResponse(array($data['posts']));
        }
    }

    /**
     * @Route("/post_comment", name="post_comment", options = { "expose" = true })
     */
    public function postCommentAction()
    {
        $response = new Response();

        try {
            $request = Request::createFromGlobals();

            $comment = new UserActivity();
            $comment->setType('comment');
            $comment->setIp($request->getClientIp());

            $comment->setName($request->request->get('name'));
            $comment->setEmail($request->request->get('email'));
            $comment->setMessage($request->request->get('message'));
            $comment->setWebsite($request->request->get('website'));

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            $response->setContent('<html><body><h1>Comment posted!</h1></body></html>');
            $response->setStatusCode(Response::HTTP_OK);
        } catch (Exception $e) {
            $response->setContent('<html><body><h1>Something went wrong!</h1></body></html>');
            $response->setStatusCode(500);
        }

        return $response;
    }

    public function makeTemplateResponse($template, $data)
    {
        return $this->render($template, $data);
    }

    public function makeJsonResponse($data)
    {
        $jsonContent = $this->serializer->serialize($data, 'json');
        $response = new JsonResponse();
        $response->setData($jsonContent);
        return $response;
    }

    public function createView($request, $post)
    {
        $view = new UserActivity();
        $view->setIp($request->getClientIp());
        $view->setPost($post);
        $view->setType("view");
        $this->entityManager->persist($view);
        $this->entityManager->flush();
    }
}
