<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Evenement;
use App\Entity\Recursion;
use App\Repository\RecursionRepository;
use App\Entity\Comment;
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
        for($i = 1; $i <= mt_rand(4,6); $i++){
            $event = new Evenement();
            $event->setTitle($faker->sentence($nbWords = 6))
                    ->setDescription($faker->paragraph())
                    ->setImage($faker->imageUrl($width = 640, $height = 480))
                    ->setDate($faker->dateTimeBetween($startDate = 'now', $endDate = '+1 year'))
                    ->setPrice($faker->randomNumber(2))
                    ->setRecursion($recursion);
                
            $manager->persist($event);
            

        $manager->flush(); 
    }
}
}