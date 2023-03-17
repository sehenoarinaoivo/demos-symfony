<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class BlogController extends AbstractController
{
    /**
     * @Route("/articles", name="blog")
     */
    public function index(ArticleRepository $ArticleRepo,CategoryRepository $catRepo, Request $request): Response
    {
        //on définit le limite
        $limit = 5;

        //On récupère le numéro de la page
        $page = (int)$request->query->get("page", 1);

        // On récupère les filtres
        $filters = $request->get("category");
        // dd($filters);
        //On récupère les annonces de la page en fonction du filtre
        $articles = $ArticleRepo->getPaginateArticle($page, $limit, $filters);
        
        //Nombre total d'Article
        $total = $ArticleRepo->getTotalArticle($filters);
        
        // On verifie si on a requete Ajax
        if($request->get('ajax')){
           
            return new JsonResponse([
                'content' =>  $this->renderView('blog/_content.html.twig',
                compact('articles', 'total','limit', 'page'))
            ]);
        }

        //On va chercher tous les catégories
        $categories = $catRepo->findAll();

        // $articles = $ArticleRepo->findAll();
        // $user = $this->getUser();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles,
            // 'articless' => $articless,
            // 'user' => $user
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('blog/home.html.twig', [
            'titre' => "Bienvenue ici les amis",
            'age' => 12
        ]);
    }

    /**
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */
    public function form(Article $article = null, Request $request, EntityManagerInterface $manager)
    {

        if (!$article) {
            $article = new Article();
        }

        // $form = $this->createFormBuilder($article)
        // ->add('title')
        // ->add('content')
        // ->add('image')
        // ->getForm();

        $form = $this->createform(ArticleType::class, $article);

        $form->handleRequest($request);

        dump($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$article->getId()) {
                $article->setCreatedAt(new \DateTime());
            }
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        }

        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null
        ]);
    }

    /**
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show(Article $article, Request $request, EntityManagerInterface $manager)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreateAt(new \DateTime())
                ->setArticle($article);
            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('blog_show', [
                'id' => $article->getId()
            ]);
        }

        return $this->render('blog/show.html.twig', [
            'article' => $article,
            'commentForm' => $form->createView()
        ]);
    }
}