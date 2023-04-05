<?php

namespace App\Command;

use App\Entity\Teacher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateTestTeachersCommand extends Command
{
    protected static $defaultName = 'app:create:test-teachers';
    protected $userPasswordEncoder;
    protected $em;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->userPasswordEncoder = $userPasswordEncoder;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('email', InputArgument::REQUIRED, 'Email Teacher')
            ->addArgument('firstName', InputArgument::REQUIRED, 'First Name Teacher')
            ->addArgument('lastName', InputArgument::REQUIRED, 'Last Name Teacher')
            ->addArgument('password', InputArgument::OPTIONAL, 'Password (default: teacher)', 'teacher')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $firstName = $input->getArgument('firstName');
        $lastName = $input->getArgument('lastName');
        $password = $input->getArgument('password');

        $teacher = new Teacher();
        $teacher->setFirstName($firstName);
        $teacher->setLastName($lastName);
        $teacher->setEmail($email);
        $teacher->setPassword($this->userPasswordEncoder->encodePassword($teacher, $password));
        $this->em->persist($teacher);
        $this->em->flush();

        $io->success('Success created');
        return 0;
    }
}
