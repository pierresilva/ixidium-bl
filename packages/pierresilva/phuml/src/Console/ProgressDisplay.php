<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console;

use PhUml\Generators\ProcessorProgressDisplay;
use PhUml\Processors\Processor;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;

/**
 * It provides visual feedback to the use about the progress of the current command
 *
 * @see ProcessorProgressDisplay for more details about the things that are reported by this display
 */
class ProgressDisplay implements ProcessorProgressDisplay
{
    /** @var OutputInterface */
    private $output;

    public function __construct(OutputInterface $output = null)
    {
        $this->output = $output ?? new StreamOutput(fopen('php://memory', 'w', false));
    }

    public function start(): void
    {
        $this->display('Running... (This may take some time)');
    }

    public function runningParser(): void
    {
        $this->display('Parsing codebase structure');
    }

    public function runningProcessor(Processor $processor): void
    {
        $this->display("Running '{$processor->name()}' processor");
    }

    public function savingResult(): void
    {
        $this->display('Writing generated data to disk');
    }

    private function display(string $message)
    {
        $this->output->writeln("<info>[|]</info> $message");
    }
}
