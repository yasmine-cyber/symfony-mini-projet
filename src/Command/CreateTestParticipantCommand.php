<?php

namespace App\Command;

use App\Entity\Participant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-test-participant',
    description: 'Creates a test Participant'
)]
class CreateTestParticipantCommand extends Command
{
    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $participant = new Participant();
        $participant->setNom('Test User');
        $participant->setEmail('test@example.com');
        $participant->setTelephone('123456789');
        $participant->setPassword($this->passwordHasher->hashPassword($participant, 'password123'));

        $this->entityManager->persist($participant);
        $this->entityManager->flush();
        $output->writeln('Participant created!');

        return Command::SUCCESS;
    }
}