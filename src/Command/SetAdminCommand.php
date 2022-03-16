<?php

namespace App\Command;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:set-admin',
    description: 'Add a short description for your command',
)]
class SetAdminCommand extends Command
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    /**
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }


    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Mail of the user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if ($user !== null) {
            $user->setRoles(['ROLE_ADMIN']);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $io->success('The user ' . $email . ' now have role admin!');
            return Command::SUCCESS;
        } else {
            $io->error('The user ' . $email . ' does not exist !!');
            return Command::FAILURE;
        }
    }
}
