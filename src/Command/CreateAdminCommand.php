<?php

namespace App\Command;

use App\Entity\Admin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Creates a new admin user in the database'
)]
class CreateAdminCommand extends Command
{
    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('This command allows you to create an admin user with ROLE_ADMIN.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $name = $io->ask('Enter admin name', 'Admin User');
        $email = $io->ask('Enter admin email', 'admin1@example.com');
        $password = $io->askHidden('Enter admin password');

        if (!$name || !$email || !$password) {
            $io->error('All fields are required.');
            return Command::FAILURE;
        }

        $admin = new Admin();
        $admin->setNom($name);
        $admin->setEmail($email);
        $admin->setRoleSpecifique('ROLE_SUPER_ADMIN'); // Optionnel
        $hashedPassword = $this->passwordHasher->hashPassword($admin, $password);
        $admin->setPassword($hashedPassword);

        $this->entityManager->persist($admin);
        $this->entityManager->flush();

        $io->success('Admin user created successfully!');

        return Command::SUCCESS;
    }
}