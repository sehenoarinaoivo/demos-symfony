<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
//use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Article;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 1; $i <= 10; $i++){
            $article = new Article();
            $article->setTitle("Titre de l'article num $i")
                        ->setContent("<p>Content de l'article num $i </p>")
                        ->setImage("http://placehold.it/350x150")
                        ->setCreatedAt(new \DateTime());
            $manager->persist($article);
        }

        $manager->flush();
    }
}
