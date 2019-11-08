<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Evenement;
use App\Entity\Recursion;
use App\Entity\User;
use App\Repository\RecursionRepository;
use App\Entity\Comment;
use App\Entity\Center;
class EvenementFixtures extends Fixture 
{
    public function load(ObjectManager $manager)
    {

        for($i = 1; $i <= 5; $i++){
            $recursion = new Recursion();
            switch ($i) {
                case 1:
                    $recursion->setName("Unique")
                            ->setTimeInterval("0");
                    break;
                case 2:
                    $recursion->setName("Quotidien")
                            ->setTimeInterval("1");
                    break;
                case 3:
                    $recursion->setName("Hebdomadaire")
                            ->setTimeInterval("7");
                    break;
                case 4:
                    $recursion->setName("Mensuel")
                            ->setTimeInterval("30");
                    break;
                case 5:
                    $recursion->setName("Annuel")
                            ->setTimeInterval("365");
                    break;
            }
            $manager->persist($recursion);
        }  
        

        $faker = \Faker\Factory::create('fr_FR');
        for($j = 1; $j <= mt_rand(4,6); $j++){
            $event = new Evenement();
            $event->setTitle($faker->sentence($nbWords = 6))
                    ->setDescription($faker->paragraph())
                    ->setImage("https://www.placecage.com/640/360")
                    ->setDate($faker->dateTimeBetween($startDate = '-1 year', $endDate = '+1 year'))
                    ->setPrice($faker->randomNumber(2))
                    ->setRecursion($recursion);
                
            $manager->persist($event);
            /*$user = new User();
            for($k = 1; $k <= mt_rand(4,10); $k++){
                $comment = new Comment();
                $comment->setAuthor($user)
                        ->setContent($faker->paragraph())
                        ->setCreatedAt($faker->dateTimeBetween('-2 months'))
                        ->setProduit($produit);
                $manager->persist($comment);

                
            }*/
        }
        for($l = 1; $l <= 7; $l++){
            $center = new Center();
            $center->setDenomination($faker->city);
            $manager->persist($center);
        }

        $manager->flush(); 
    }
}
