<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use App\Entity\PostLike;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * Encodeur de mot de passe
     * 
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        $users = [];
        $user = new User();

        $user->setEmail('user@symfony.com')
            ->setUsername($faker->firstName())
            ->setPassword($this->encoder->encodePassword($user, 'password'))
            ;
        
        $manager->persist($user);

        $users[] = $user;

        for($m = 0; $m < 20; $m++) {
            $user = new User();
            $user->setEmail($faker->email)
                ->setUsername($faker->firstName())
                ->setPassword($this->encoder->encodePassword($user, 'password'))
                ;
            $manager->persist($user);
            
            $users[] = $user;
        }

        //Créer 3 catégories fakées
        for($i = 1; $i <= 3; $i++) {
            $category = new Category();
            $category->setTitle($faker->sentence())
                    ->setDescription($faker->paragraph());

            $manager->persist($category);

            $content = '<p>'.join($faker->paragraphs(5)).'</p>';
            
            // Créer entre 4 et 6 articles
            for($j = 1; $j <= mt_rand(4, 6); $j++){
                $article = new Article();

                $content = '<p>'.join($faker->paragraphs(5)).'</p>';

                $article->setTitle($faker->sentence())
                            ->setContent($content)
                            ->setImage($faker->imageUrl())
                            ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                            ->setCategory($category);

                $manager->persist($article);

                //Like pur l'article
                for($l = 0; $l < mt_rand(0, 10); $l++) {
                    $like = new PostLike();
                    $like->setArticle($article)
                        ->setUser($faker->randomElement($users))
                        ;
                    $manager->persist($like);
                }

                // On donne des commentaire à l'article
                for($k = 1; $k <= mt_rand(4, 10); $k++) {
                    $comment = new Comment();
                    $content = '<p>'.join($faker->paragraphs(2)).'</p>';

                    $days = (new \DateTime())->diff($article->getCreatedAt())->days;

                    $comment->setAuthor($faker->name)
                            ->setContenu($content)
                            ->setCreateAt($faker->dateTimeBetween('-' . $days . ' days'))
                            ->setArticle($article);

                    $manager->persist($comment);
                }
            }
        }

        $manager->flush();
    }
}
