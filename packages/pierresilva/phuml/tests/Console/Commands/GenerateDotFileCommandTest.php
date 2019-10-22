<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console\Commands;

use PHPUnit\Framework\TestCase;
use PhUml\Console\PhUmlApplication;
use PhUml\Console\ProgressDisplay;
use PhUml\Parser\InvalidDirectory;
use RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;

class GenerateDotFileCommandTest extends TestCase
{
    /** @before */
    function configureCommandTester()
    {
        $application = new PhUmlApplication(new ProgressDisplay());
        $this->command = $application->find('phuml:dot');
        $this->tester = new CommandTester($this->command);
        $this->dotFile = __DIR__ . '/../../resources/.output/dot.gv';
        if (file_exists($this->dotFile)) {
            unlink($this->dotFile);
        }
    }

    /** @test */
    function it_fails_to_execute_if_the_arguments_are_missing()
    {
        $this->expectException(RuntimeException::class);

        $this->tester->execute([
            'command' => $this->command->getName()
        ]);
    }

    /** @test */
    function it_fails_to_generate_a_dot_file_if_directory_with_classes_does_not_exist()
    {
        $this->expectException(InvalidDirectory::class);

        $this->tester->execute([
            'command' => $this->command->getName(),
            'directory' => 'invalid-directory',
            'output' => $this->dotFile,
        ]);
    }

    /** @test */
    function it_generates_the_dot_file_of_a_given_directory()
    {
        $status = $this->tester->execute([
            'command' => $this->command->getName(),
            'directory' => __DIR__ . '/../../resources/.code',
            'output' => $this->dotFile,
            '--associations' => true,
        ]);

        $this->assertEquals(0, $status);
        $this->assertFileExists($this->dotFile);
    }

    /** @test */
    function it_generates_a_dot_file_searching_for_classes_recursively()
    {
        $status = $this->tester->execute([
            'command' => $this->command->getName(),
            'directory' => __DIR__ . '/../../resources/.code',
            'output' => $this->dotFile,
            '--recursive' => true,
            '--associations' => true,
        ]);

        $this->assertEquals(0, $status);
        $this->assertFileExists($this->dotFile);
    }

    /** @test */
    function it_generates_a_dot_file_excluding_private_and_protected_members()
    {
        $status = $this->tester->execute([
            'command' => $this->command->getName(),
            'directory' => __DIR__ . '/../../resources/.code',
            'output' => $this->dotFile,
            '--recursive' => true,
            '--associations' => true,
            '--hide-protected' => true,
            '--hide-private' => true,
        ]);

        $this->assertEquals(0, $status);
        $this->assertFileExists($this->dotFile);
    }

    /** @var string */
    private $dotFile;

    /** @var GenerateDotFileCommand */
    private $command;

    /** @var CommandTester */
    private $tester;
}
