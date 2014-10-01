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
        $faker = Faker\Factory::create();

        $em = $this->getContainer()->get('doctrine')->getEntityManager('default');
        $users = $em->getRepository('ClarolineCoreBundle:User')->findAll();
        $excludedUsernames = array("admin");

        foreach ($users as $user) {
            if (!in_array($user->getUsername(), $excludedUsernames)) {
                $output->writeln($user->getUsername()." ".$user->getLastName()." ".$user->getFirstName()." ".$user->getMail()."\n");
                $output->writeln("->");
                
                $username = $faker->userName;
                $lastName = $faker->lastName;
                $firstName = $faker->firstName;
                $email = $faker->safeEmail;

                $user->setFirstName($firstName);
                $user->setLastName($lastName);
                $user->setUsername($username);
                $user->setMail($email);

                $em->persist($user);
                $output->writeln($username." ".$lastName." ".$firstName." ".$email."\n");
            }
        }
        $em->flush();
    }
}