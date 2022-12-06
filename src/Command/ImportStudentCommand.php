<?php

namespace App\Command;

use League\Csv\Reader;
use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:import-students')]
class ImportStudentCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * CsvImportCommand constructor.
     *
     * @param EntityManagerInterface $em
     *
     * @throws \Symfony\Component\Console\Exception\LogicException
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
    }

    /**
     * Configure
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setName('csv:import')
            ->setDescription('Imports the mock CSV data file');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $importfilename = '%kernel.root_dir%/../public/Excel/import.csv';
        // dump($importfilename);

        $reader = Reader::createFromPath($importfilename);


        /*

        // https://github.com/thephpleague/csv/issues/208
        $results = $reader->fetchAssoc();

        foreach ($results as $row) {

            // create new athlete
            $student = new Student();
            $student->setShortname($row['Nom']);
            $student->setLastname($row['Prénom']);
            $student->setGrade($row['CLASSE']);
            $student->setGender($row['SEXE']);
            $student->setMas($row['VMA']);
            $student->setObjective($row['TEMPS']);;

            $this->em->persist($student);


            $this->em->flush();

            $io->success('Command exited cleanly!');
        }

        */
        print("derniere etape");
        return Command::SUCCESS;
    }
}
