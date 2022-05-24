<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Article;
use App\Repository\ArticleRepository;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo): Response
    {
        //$repo = $this->getDoctrine()->getRepository(Article::class);

        $articles = $repo->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home() {
        return $this->render('blog/home.html.twig', [
            'titre' => "Bienvenue ici les amis",
            'age' => 12
        ]);
    }

    /**
     * @Route("/blog/new", name="blog_create")
     */
    public function create(Request $request, EntityManagerInterface $manager) {
        // dd($request->request->count());
        //if($request->request->count() > 0) {
            $article = new Article();
            
            $form = $this->createFormBuilder($article)
                        ->add('title')
                        ->add('content')
                        ->add('image')
                        ->getForm();

            return $this->render('blog/create.html.twig', [ 
                'formArticle' => $form->createView(),
                'variable' => "tonga"
            ]);

            $manager->persist($article);
            
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
       // }

    }

    /**
     * @Route("/blog/article/{id}", name="blog_show")
     */
    public function show(Article $article){
        return $this->render('blog/show.html.twig',[
            'article' => $article
        ]);
    }

}
