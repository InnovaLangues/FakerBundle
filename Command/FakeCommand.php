<?php

namespace Innova\FakerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
        
    }
}