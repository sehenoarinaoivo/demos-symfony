<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\PostLike;
use App\Repository\PostLikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="app_article")
     */
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    /**
     * @Route("/article/{id}/like", name="like_article")
     */
    public function like(Article $article,EntityManagerInterface $manager, PostLikeRepository $likeRepo): Response
    {
        $user = $this->getUser();

        if(!$user) return $this->json([
            'code' =>403,
            'message' => "Unauthorized"
        ], 403);

        if($article->isLikedByUser($user)){
            $like = $likeRepo->findOneBy([
                'article' => $article,
                'user' =>$user
            ]);

            $manager->remove($like);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Like bien supprimé',
                'likes' => $likeRepo->count(['article' =>$article])
            ], 200);
        }

        $like = new PostLike();
        $like->setArticle($article)
             ->setUser($user);

        return $this->json([
            'code'=>200,
            'message'=>'Like bien ajouté',
            'likes' => $likeRepo->count(['article' => $article])
        ], 200);
    }
}
