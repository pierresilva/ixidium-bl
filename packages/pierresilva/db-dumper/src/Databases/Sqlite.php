<?php

namespace pierresilva\DbDumper\Databases;

use pierresilva\DbDumper\DbDumper;
use Symfony\Component\Process\Process;

class Sqlite extends DbDumper
{
    /**
     * Dump the contents of the database to a given file.
     *
     * @param string $dumpFile
     *
     * @throws \pierresilva\DbDumper\Exceptions\DumpFailed
     */
    public function dumpToFile(string $dumpFile)
    {
        $command = $this->getDumpCommand($dumpFile);

        $process = Process::fromShellCommandline($command, null, null, null, $this->timeout);

        $process->run();

        $this->checkIfDumpWasSuccessFul($process, $dumpFile);
    }

    /**
     * Get the command that should be performed to dump the database.
     *
     * @param string $dumpFile
     *
     * @return string
     */
    public function getDumpCommand(string $dumpFile): string
    {
        $command = sprintf(
            "echo 'BEGIN IMMEDIATE;\n.dump' | '%ssqlite3' --bail '%s'",
            $this->dumpBinaryPath,
            $this->dbName
        );

        return $this->echoToFile($command, $dumpFile);
    }
}
