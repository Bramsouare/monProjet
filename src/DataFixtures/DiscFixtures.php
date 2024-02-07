<?php

namespace App\DataFixtures;

use App\Entity\Disc;
use App\Entity\Artist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DiscFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        include 'record.php';

        $artistRepo = $manager -> getRepository (Artist::class);
 
        foreach ($artist as $art)
        {
            $artistDB = new Artist ();
            $artistDB

            -> setId ($art ['artist_id'] )
            -> setName ($art ['artist_name'] )
            -> setUrl ($art ['artist_url'] );

            $manager -> persist ($artistDB);

            // enpêcher l'auto incrément
            $metadata = $manager -> getClassMetadata (Artist::class);
            $metadata -> setIdGeneratorType (\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

        }

        $manager -> flush ();

        foreach ($disc as $d)
        {
            $discDB = new Disc ();
            $discDB

            -> setTitle ($d ['disc_title'] )
            -> setLabel ($d ['disc_label'] )
            -> setPicture ($d ['disc_picture'] );

            $artist = $artistRepo -> find ($d ['artist_id'] );
            $discDB -> setArtist ($artist);

            $manager -> persist ($discDB);

        }

        $manager -> flush ();
    }
}
