<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console\Commands;

use PhUml\Generators\StatisticsGenerator;
use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\CodeParser;
use PhUml\Parser\NonRecursiveCodeFinder;
use PhUml\Processors\StatisticsProcessor;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This command will generate a text file with the statistics of an OO codebase
 *
 * This command has 2 required arguments
 *
 * 1. `directory`. The path where your codebase lives
 * 2. `output`. The path to where the generated `png` image will be saved
 *
 * There is 1 options
 *
 * 1. `recursive`. If present it will look recursively within the `directory` provided
 */
class GenerateStatisticsCommand extends GeneratorCommand
{
    /**
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setName('phuml:statistics')
            ->setDescription('Generate statistics about the code of a given directory')
            ->setHelp(
                <<<HELP
Example:
    php bin/phuml phuml:statistics -r  ./src statistics.txt

    This example will scan the `./src` directory recursively for php files.
    It will generate the statistics and save them to the file `statistics.txt`.
HELP
            )
            ->addOption(
                'recursive',
                'r',
                InputOption::VALUE_NONE,
                'Look for classes in the given directory recursively'
            )
            ->addArgument(
                'directory',
                InputArgument::REQUIRED,
                'The directory to be scanned to generate the statistics'
            )
            ->addArgument(
                'output',
                InputArgument::REQUIRED,
                'The file name for your statistics file'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $codebasePath = $input->getArgument('directory');
        $statisticsFilePath = $input->getArgument('output');
        $recursive = (bool)$input->getOption('recursive');

        $statisticsGenerator = new StatisticsGenerator(new CodeParser(), new StatisticsProcessor());
        $statisticsGenerator->attach($this->display);

        $codeFinder = $recursive ? new CodeFinder() : new NonRecursiveCodeFinder();
        $codeFinder->addDirectory(CodebaseDirectory::from($codebasePath));

        $statisticsGenerator->generate($codeFinder, $statisticsFilePath);

        return 0;
    }
}
