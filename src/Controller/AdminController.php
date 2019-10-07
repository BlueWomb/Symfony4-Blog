<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Tag;

use App\Form\CategoryType;
use App\Form\PostType;
use App\Form\TagType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
     * @Route("/create_post/{id}", name="create_post")
     */
    public function createPostAction(Request $request, $id = -1) {

        if ($id === -1)
            $post = new Post();
        else
            $post = $this->postRepository->findOneById($id);

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request); 
        if($form->isSubmitted() && $form->isValid()) {
            $slug = str_replace(' ', '-', $post->getTitle());
            $post->setSlug($slug);
            $post->setAuthor($this->getUser());

            //Handle upload of preview image
            $imageFile = $form['preview']->getData();
            $originalFilename =  pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
            
            try {
                $imageFile->move($this->getParameter('preview_directory'), $newFilename);
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            $post->setPreview($newFilename);

            //Push into db
            $this->entityManager->persist($post);
            $this->entityManager->flush();

            return $this->redirectToRoute("dashboard");
        }   

        return $this->render('admin/create_post.html.twig', [
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/create_category/{id}", name="create_category")
     */
    public function createCategoryAction(Request $request, $id = -1) {

        if ($id === -1)
            $category = new Category();
        else
            $category = $this->categoryRepository->findOneById($id);

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($category);
            $this->entityManager->flush();
            return $this->redirectToRoute("dashboard");
        }

        return $this->render('admin/create_category.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/create_tag/{id}", name="create_tag")
     */
    public function createTagAction(Request $request, $id = -1) {

        if ($id === -1) $tag = new Tag();
        else $tag = $this->tagRepository->findOneById($id);

        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($tag);
            $this->entityManager->flush();
            return $this->redirectToRoute("dashboard");
        }

        return $this->render('admin/create_tag.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{type}/{id}", name="delete")
     */
    public function deleteAction($type, $id) {

        if ($type === "c") $entity = $this->categoryRepository->findOneById($id);
        else $entity = $this->tagRepository->findOneById($id);

        $this->entityManager->remove($entity);
        $this->entityManager->flush();
        return $this->redirectToRoute("dashboard");
    }
}