<?php
namespace App\Command;

use App\Importer\Exception\ImporterException;
use App\Importer\Importer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends Command
{
    /**
     * @var Importer
     */
    private $importer;

    public function __construct()
    {
        parent::__construct();

        $this->importer = new Importer();
    }

    /**
     * Configure this command
     */
    protected function configure()
    {
        $this
            ->setName('import')
            ->setDescription('Runs the import')
            ->setHelp('Runs the importer, duh.')
            ->addArgument('xml', InputArgument::REQUIRED, 'The xml to interpret.')
            ->addArgument('output', InputArgument::REQUIRED, 'The name for the generated file');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $xml = $input->getArgument('xml');
        $name = $input->getArgument('output');

        try {
            $this->importer->setXmlPath($xml);
            $this->importer->setOutputName($name);

            $this->importer->run();

        } catch (ImporterException $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");

            return true;
        }

        $output->writeln("<info>Done! Check the results in {$name}.</info>");

        return true;
    }

    /**
     * Method introduced for testing purposes
     * Introducing a DIC only for this is, I believe, an overkill. Rather add this simple method
     * @param Importer $importer
     */
    public function setImporter(Importer $importer)
    {
        $this->importer = $importer;
    }
}
