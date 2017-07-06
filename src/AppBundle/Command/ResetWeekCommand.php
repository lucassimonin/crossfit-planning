<?php

namespace AppBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 05/07/2017
 * Time: 22:46
 */
class ResetWeekCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:reset-week')

            // the short description shown while running "php bin/console list"
            ->setDescription('Reset planning for the next week.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command reset all session and user hours for the next week.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $container = $this->getContainer();

        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.orm.entity_manager');
        $sessions = $entityManager->getRepository('AppBundle:Session')->findAll();
        foreach ($sessions as $session) {
            foreach ($session->getUsers() as $user) {
                $user->removeSession($session);
            }
            $entityManager->persist($session);
            $entityManager->flush();
        }
        $io->success('Week reset !');
    }
}
