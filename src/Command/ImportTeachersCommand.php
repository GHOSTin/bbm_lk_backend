<?php

namespace App\Command;

use App\Service\ImportService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportTeachersCommand extends Command
{
    protected static $defaultName = 'app:import:teachers';

    protected $importService;

    protected function configure()
    {
        $this
            ->setDescription('Import Teacher from csv files')
        ;
    }

    public function __construct(ImportService $importService)
    {
        $this->importService = $importService;
        parent::__construct();

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->importService->importTeachersFromCsv();
        $io->success('Учителя успешно зарегистрированы');
        return 0;
    }
}
