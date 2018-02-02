<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Import extends Command
{
    /**
     * Configure this command
     */
    protected function configure()
    {
        $this
            ->setName('import')
            ->setDescription('Runs the import')
            ->setHelp('Runs the importer, duh.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('User  successfully generated!');

        return true;
    }
}
