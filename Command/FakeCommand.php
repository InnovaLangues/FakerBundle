<?php

namespace Innova\FakerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Faker\Factory;

class FakeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('innova:faker')
            ->setDescription('Change users personal data by randomly generated.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $faker = Factory::create();

        $em = $this->getContainer()->get('doctrine')->getEntityManager('default');
        $users = $em->getRepository('ClarolineCoreBundle:User')->findAll();
        $excludedUsernames = array("admin");

        foreach ($users as $user) {
            if (!in_array($user->getUsername(), $excludedUsernames)) {
                // generate fake infos
                $username = $faker->unique()->userName;
                $lastName = $faker->lastName;
                $firstName = $faker->firstName;
                $mail = $faker->unique()->safeEmail;
                $workspace =  $user->getPersonalWorkspace();
                $workspaceName = "Espace de ".$username;

                // get original user properties
                $usernameOrig = $user->getUsername();
                $lastNameOrig = $user->getLastName();
                $firstNameOrig = $user->getFirstName();
                $mailOrig = $user->getMail();
                if($workspace){
                    $workspaceNameOrig = $workspace->getName();
                }

                // set user properties
                $user->setFirstName($firstName);
                $user->setLastName($lastName);
                $user->setUsername($username);
                $user->setMail($mail);
                if($workspace){
                    $workspace->setName($workspaceName);
                    $workspace->setCode($username);
                    $em->persist($workspace);
                }
                $em->persist($user);

                $output->writeln($usernameOrig . " -> " . $username);
                $output->writeln($firstNameOrig . " -> " . $firstName);
                $output->writeln($lastNameOrig . " -> " . $lastName);
                $output->writeln($mailOrig . " -> " . $mail);
                if($workspace){
                    $output->writeln($workspaceNameOrig . " -> " . "Espace de ".$username);
                }
                $output->writeln("");
            }
        }
        $em->flush();
    }
}