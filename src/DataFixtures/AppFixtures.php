<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Tag;
use App\Entity\User;
use App\Entity\Offer;
use App\Entity\UserProfil;
use App\Entity\HomeSetting;
use App\Entity\ContractType;
use App\Entity\EntrepriseProfil;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {


        $imageTab = [
            "https://source.unsplash.com/random",
            "https://source.unsplash.com/featured",
            "https://source.unsplash.com/1600x900/?nature,water",
            "https://source.unsplash.com/1600x900/?car",
        ];

        // Insertion des réglages de l'application(HomeSettings)
        
        $faker = Factory::create();
        for ($i = 0; $i < 5; $i++) {
            // Récupération d'un image unsplash au hasard
            $imgUnspash = $faker->randomElement($imageTab);
            $homeSettings = new HomeSetting();
            $homeSettings->setMessage($faker->paragraph(2));
            $homeSettings->setCallToAction($faker->word());
            $homeSettings->setImage($imgUnspash);
            $manager->persist($homeSettings);
            $manager->flush();
        }


        // Insertion des tags
        $tagList = [
            'PHP',
            'Symfony',
            'Angular',
            'VueJS',
            'NodeJS',
            'Java',
            'C#',
            'C++',
            'Python',
            'Ruby',
            'Go',
            'Rust',
            'Swift',
            'Kotlin',
            'TypeScript',
            'JavaScript',
            'HTML',
            'CSS',
            'SQL',
            'NoSQL',
            'MongoDB',
            'MySQL',
            'PostgreSQL',
            'Oracle',
            'MariaDB',
            'SQLite',
            'Docker',
            'Kubernetes',
            'Git',
            'GitHub',
            'GitLab',
            'BitBucket',
            'Jenkins',
            'Travis',
            'AWS',
            'Azure',
            'Google Cloud',
            'Heroku',
            'Digital Ocean',
            'Linux',
            'Windows',
            'MacOS',
            'Android',
            'iOS',
            'React Native',
            'Flutter',
            'Ionic',
            'Xamarin',
            'React',
            'Redux',
            'Vue',
            'Vuex',
            'NestJS',
            'Express',
            'Laravel',
        ];

        foreach ($tagList as $tagName) {
            $tag = new Tag();
            $tag->setName($tagName);
            $manager->persist($tag);
        }

        // Insertion des types de contract 
        $contractList = [
            'CDI',
            'CDD',
            'INTERIM',
            'STAGE',
            'ALTERNANCE',
            'FREELANCE',
            'INDEPENDANT',
            'AUTRE'
        ];

        foreach ($contractList as $contractName) {
            $contract = new ContractType();
            $contract->setName($contractName);
            $manager->persist($contract);
        }

        // Insertion des utilisateurs

        $faker = Factory::create();
        $tabRoles = ['Candidat', 'Entreprise'];

        for ($i = 0; $i < 50; $i++) {
            // Récupération d'un rôle au hasard
            $randomRole = $faker->randomElement($tabRoles);
            $user = new User();
            $user->setEmail($faker->email);
            $user->setPassword(password_hash('password', PASSWORD_DEFAULT));
            $user->setStatus($randomRole);
            if ($randomRole == "Entreprise") {
                $user->setRoles(['ROLE_PRO']);
                $user->setUsername($faker->company);
            } else {
                $user->setUsername($faker->name());
            }

            $manager->persist($user);
        }

        $manager->flush();

        // Création des profils utilisateurs

        $users = $manager->getRepository(User::class)->findByStatus('Candidat');

        foreach ($users as $user) {
            $newUserProfil = new UserProfil();
            $newUserProfil->setUser($user);
            $newUserProfil->setFirstName($faker->firstName());
            $newUserProfil->setLastName($faker->lastName());
            $newUserProfil->setAddress($faker->streetAddress());
            $newUserProfil->setZipCode($faker->postcode());
            $newUserProfil->setCity($faker->city());
            $newUserProfil->setCountry($faker->country());
            $newUserProfil->setSlug($faker->slug());
            $newUserProfil->setPresentation($faker->text());
            $newUserProfil->setPhoneNumber($faker->phoneNumber());
            $newUserProfil->setWebsite($faker->url());
            $newUserProfil->setAvailability($faker->boolean());
            $newUserProfil->setJobSought($faker->jobTitle());
            $manager->persist($newUserProfil);
            $manager->flush();
        }

        // Création des profils entreprises

        $entreprises = $manager->getRepository(User::class)->findByStatus('Entreprise');

        foreach ($entreprises as $entreprise) {

            $generateGender =['men','women'];
            // Récupération d'une image random de userrandom.com
            $userRandom = 'https://randomuser.me/api/portraits/'. $faker->randomElement($generateGender) . '/' . $faker->numberBetween(1, 99) . '.jpg';

            $newEntrepriseProfil = new EntrepriseProfil();
            $newEntrepriseProfil->setUser($entreprise);
            $newEntrepriseProfil->setEmail($faker->email());
            $newEntrepriseProfil->setName($faker->company());
            $newEntrepriseProfil->setAddress($faker->streetAddress());
            $newEntrepriseProfil->setZipCode($faker->postcode());
            $newEntrepriseProfil->setCity($faker->city());
            $newEntrepriseProfil->setCountry($faker->country());
            $newEntrepriseProfil->setSlug($faker->slug());
            $newEntrepriseProfil->setDescription($faker->paragraph(2));
            $newEntrepriseProfil->setPhoneNumber($faker->phoneNumber());
            $newEntrepriseProfil->setWebsite($faker->url());
            $newEntrepriseProfil->setActivityArea($faker->jobTitle());
            $newEntrepriseProfil->setLogo($userRandom);
            $manager->persist($newEntrepriseProfil);
            $manager->flush();
        }

        // Création de 15 offre d'emploi aléatoire pour les entreprises random

        $recruteurs = $manager->getRepository(EntrepriseProfil::class)->findAll();
        $contractList = $manager->getRepository(ContractType::class)->findAll();
        $tags = $manager->getRepository(Tag::class)->findAll();

        
            for ($i = 0; $i < 20; $i++) {
                // Récupération d'une entreprise au hasard
                $recruteurRandom = $faker->randomElement($recruteurs);
                $newOffer = new Offer();
                $newOffer->setTitle($faker->jobTitle());
                $newOffer->setSlug($faker->slug());
                $newOffer->setShortDescription($faker->paragraph(2));
                $newOffer->setContent($faker->paragraph(50));
                $newOffer->setCreatedAt(new \DateTimeImmutable());
                $newOffer->setContractType($faker->randomElement($contractList));
                $newOffer->setSalary($faker->numberBetween(20000, 100000));
                $newOffer->setLocation($faker->city());
                $newOffer->setEntreprise($recruteurRandom);
                // Ajout entre 3 et 6 tags à l'offre
                $randomTags = $faker->randomElements($tags, $faker->numberBetween(3, 6));
                foreach ($randomTags as $tag) {
                    $newOffer->addTag($tag);
                }
                //$newOffer->addTag($faker->randomElement($tags));

                
                $manager->persist($newOffer);
                $manager->flush();
            }
        

        // Créer un admin
        $admin = new User();
        $admin->setEmail('romy@romy.com');
        $admin->setPassword(password_hash('password', PASSWORD_DEFAULT));
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setUsername('Romy admin');
        $admin->setStatus('Admin');
        $manager->persist($admin);
        $manager->flush();
    }
}