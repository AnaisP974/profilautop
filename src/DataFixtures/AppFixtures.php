<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Enum\JobStatus;
use App\Entity\JobOffer;
use App\Entity\CoverLetter;
use App\Entity\LinkedInMessage;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        
        $status = ["À postuler", "En attente", "Entretien", "Refusé", "Accepté"];

        // USER 1
        $user1 = new User();
        $user1
            ->setEmail('johndoe@gmail.com')
            ->setPassword($this->hasher->hashPassword($user1, 'johndoe'))
            ->setFirstName('john')
            ->setLastName('doe')
            ->setImage('https://images.pexels.com/photos/17459730/pexels-photo-17459730/free-photo-of-homme-metal-etre-assis-porte.jpeg?auto=compress&cs=tinysrgb&w=600')
            ->setRoles(['ROLE_USER'])
        ;
        $manager->persist($user1);
        
        // USER 2
        $user2 = new User();
        $user2
            ->setEmail('anneandre@gmail.com')
            ->setPassword($this->hasher->hashPassword($user2, 'anneandre'))
            ->setFirstName('anne')
            ->setLastName('andre')
            ->setImage('https://images.pexels.com/photos/17550541/pexels-photo-17550541/free-photo-of-femme-ete-foret-modele.jpeg?auto=compress&cs=tinysrgb&w=600')
            ->setRoles(['ROLE_USER'])
        ;
        $manager->persist($user2);

        for ($i = 0; $i < 10; $i++) {
            // JOB OFFER
            $jobOffer = new JobOffer();
            $jobOffer
                ->setTitle($faker->words(3, true))
                ->setCompany($faker->words(2, true))
                ->setLocation($faker->city())
                ->setContactPerson($faker->name())
                ->setContactEmail($faker->email())
                ->setStatus(JobStatus::A_POSTULER)
                ->setAppUser($i % 2 === 0 ? $user1 : $user2)
            ;
            $manager->persist($jobOffer);
            
            // LINKEDIN MESSAGE
            $linkedInMessage = new LinkedInMessage();
            $linkedInMessage
                ->setContent('Bonjour ' . $faker->name() . ", <br>" . $faker->paragraphs(2, true))
                ->setJobOffer($jobOffer)
                ->setAppUser($i % 2 === 0 ? $user1 : $user2)
            ;
            $manager->persist($linkedInMessage);

            // COVER LETTER
            $coverLetter = new CoverLetter();
            $coverLetter
                ->setContent('Madame, Monsieur, <br><br>' . $faker->paragraphs(4, true) . "<br><br> Dans l'attente de votre retour, je vous prie d'agréer mes sincères salutations. <br><br>")
                ->setJobOffer($jobOffer)
                ->setAppUser($i % 2 === 0 ? $user1 : $user2)
            ;
            $manager->persist($coverLetter);
        }

        $manager->flush();
    }
}
