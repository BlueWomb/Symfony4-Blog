<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    private $entityManager;
    private $authorRepository;
    private $postRepository;
    private $categoryRepository;
    private $tagRepository;
    private $userActivityRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->authorRepository = $entityManager->getRepository('App:Author');
        $this->postRepository = $entityManager->getRepository('App:Post');
        $this->categoryRepository = $entityManager->getRepository('App:Category');
        $this->tagRepository = $entityManager->getRepository('App:Tag');
        $this->userActivityRepository = $entityManager->getRepository('App:UserActivity');
    }

    /**
     * @Route("/", name="dashboard")
     */
    public function indexAction()
    {
        $data = ['categories' => $this->categoryRepository->findAll(),
                    'tags' => $this->tagRepository->findAll()];

        return $this->render('admin/index.html.twig', $data);
    }

    /**
     * @Route("/create_post", name="create_post")
     */
    public function createPostAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request); 
        if($form->isSubmitted() && $form->isValid()) {
            $slug = str_replace(' ', '-', $post->getTitle());
            $post->setSlug($slug);
            $post->setAuthor($this->getUser());
            $this->entityManager->persist($post);
            $this->entityManager->flush();
        }   

        return $this->render('admin/create_post.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
