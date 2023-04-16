<?php

namespace App\DataFixtures;
use App\Entity\Book;
use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $listAuthor = [];

        for($i = 0; $i < 10; $i++)
        {
            $author = new Author();
            $author->setFirstName("Prénom ".$i);
            $author->setLastName("Nom ".$i);
            $manager->persist($author);
            $listAuthor[] = $author;
        }




        for($i =0 ; $i < 20 ; $i++) {
            $livre = new Book();
            $livre->setTitle('Titre du livre n°' . $i);
            $livre->setCoverText('Texte de couverture du livre n°'.$i);
            $livre->setAuthor($listAuthor[array_rand($listAuthor)]);
            $manager->persist($livre);

        }


        $manager->flush();
    }
}
