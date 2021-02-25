<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Room;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        # Création des categories

        $chambresuperieure = new Category();
        $chambresuperieure->setName('Chambre supérieure')->setAlias('chambresuperieure');

        $chambreprestige = new Category();
        $chambreprestige->setName('Chambre Prestige')->setAlias('chambreprestige');

        $suitedeluxe = new Category();
        $suitedeluxe->setName('Suite de luxe')->setAlias('suitedeluxe');

        $penthouse = new Category();
        $penthouse->setName('Penthouse')->setAlias('penthouse');




        # Je souhaite sauvegarder dans ma BDD les catégories
        $manager->persist( $chambresuperieure );
        $manager->persist(  $chambreprestige );
        $manager->persist( $suitedeluxe );
        $manager->persist(  $penthouse );

        # J'execute ma requete d'enregistrement
        $manager->flush();

        # Création d'un User
        $user = new User();
        $user->setFirstname('Georges')
            ->setLastName('Canin')
            ->setDogName('Moshi')
            ->setAddress('12 avenue de Montaigne')
            ->setCity('Paris')
            ->setZipcode('75008')
            ->setEmail('palacecanin@gmail.com')
            ->setTelephone('')
            ->setPassword('toutou91')
            ->setRoles(['ROLE_USER']);


        # Sauvegarde dans la BDD
        $manager->persist( $user );
        $manager->flush();

        # Création des Chambres | Chambre supérieure
        for($i = 0; $i < 25; $i++) {

            $room = new Room();
            $room->setName('Praline est la grande poupée de maman' . $i)
                ->setAlias('Praline est le gros poulet dodu de maman'. $i)
                ->setContent('<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A dolor magnam nemo numquam, quae quo repellendus. A amet doloremque fuga fugit hic libero, nobis repellendus, saepe sapiente sit ullam vel.</p>')
                ->setImage('https://via.placeholder.com/500')
                ->setCreatedAt(new \DateTime())
                ->setUser($user)
                ->setCategory($chambresuperieure);

            # On demande l'enregistrement de l'article
            $manager->persist($room);



        }

        # Création des Chambres | Prestige
        for($i = 25; $i < 50; $i++) {

            $room = new Room();
            $room->setName('Praline est la grande poupée de maman' . $i)
                ->setAlias('Praline est le gros poulet dodu de maman'. $i)
                ->setContent('<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A dolor magnam nemo numquam, quae quo repellendus. A amet doloremque fuga fugit hic libero, nobis repellendus, saepe sapiente sit ullam vel.</p>')
                ->setImage('https://via.placeholder.com/500')
                ->setCreatedAt(new \DateTime())
                ->setUser($user)
                ->setCategory($chambreprestige);

            # On demande l'enregistrement de l'article
            $manager->persist($room);



        }

        # Création des Chambres | Suite de luxe
        for($i = 50; $i < 75; $i++) {

            $room = new Room();
            $room->setName('Praline est la grande poupée de maman' . $i)
                ->setAlias('Praline est le gros poulet dodu de maman'. $i)
                ->setContent('<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A dolor magnam nemo numquam, quae quo repellendus. A amet doloremque fuga fugit hic libero, nobis repellendus, saepe sapiente sit ullam vel.</p>')
                ->setImage('https://via.placeholder.com/500')
                ->setCreatedAt(new \DateTime())
                ->setUser($user)
                ->setCategory($suitedeluxe);

            # On demande l'enregistrement de l'article
            $manager->persist($room);



        }

        # Création des Chambres | Pent-House
        for($i = 75; $i < 100; $i++) {

            $room = new Room();
            $room->setName('Praline est la grande poupée de maman' . $i)
                ->setAlias('Praline est le gros poulet dodu de maman'. $i)
                ->setContent('<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A dolor magnam nemo numquam, quae quo repellendus. A amet doloremque fuga fugit hic libero, nobis repellendus, saepe sapiente sit ullam vel.</p>')
                ->setImage('https://via.placeholder.com/500')
                ->setCreatedAt(new \DateTime())
                ->setUser($user)
                ->setCategory($penthouse);

            # On demande l'enregistrement de l'article
            $manager->persist($room);



        }
        # On execute la demande d'enregistrement dans la BDD
        $manager->flush();

    }
}












