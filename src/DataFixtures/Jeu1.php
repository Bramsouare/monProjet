<?php

namespace App\DataFixtures;

use App\Entity\Disc;
use App\Entity\Artist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Jeu1 extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $artist1 = new Artist ();
        $artist1 -> setName ("Queens Of The Stone Age");
        $artist1 -> setUrl ("https://qotsa.com/");

        $disc1 = new Disc ();
        $disc1 -> setTitle ("Songs For The Deaf");
        $disc1 -> setPicture ("https://en.wikipedia.org/wiki/Songs_for_the_Deaf#/media/File:Queens_of_the_Stone_Age_-_Songs_for_the_Deaf.png");
        $disc1 -> setLabel ("Interscope Records");
        $disc1 -> setArtist ($artist1);

        $manager -> persist($artist1);
        $manager -> persist ($disc1);

        $artist2 = new Artist ();
        $artist2 -> setName ("Bob");
        $artist2 -> setUrl ("https://bob.com/");

        $disc2 = new Disc ();
        $disc2 -> setTitle ("Bob l eponge");
        $disc2 -> setPicture ("https://en.wikipedia.org/wiki/Songs_for_the_Deaf#/media/File:Queens_of_the_Stone_Age_-_Songs_for_the_Deaf.png");
        $disc2 -> setLabel ("Interscope Records");
        $disc2 -> setArtist ($artist2);

        $manager -> persist($artist2);
        $manager -> persist ($disc2);

        // $artist1 -> addDisc ($disc1);

        $manager->flush();
    }
}
