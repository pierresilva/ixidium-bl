<?php

namespace pierresilva\DbDumper\Databases;

use pierresilva\DbDumper\DbDumper;
use Symfony\Component\Process\Process;
use pierresilva\DbDumper\Exceptions\CannotStartDump;

class MongoDb extends DbDumper
{
    protected $port = 27017;

    /** @var null|string */
    protected $collection = null;

    /** @var null|string */
    protected $authenticationDatabase = null;

    /**
     * Dump the contents of the database to the given file.
     *
     * @param string $dumpFile
     *
     * @throws \pierresilva\DbDumper\Exceptions\CannotStartDump
     * @throws \pierresilva\DbDumper\Exceptions\DumpFailed
     */
    public function dumpToFile(string $dumpFile)
    {
        $this->guardAgainstIncompleteCredentials();

        $command = $this->getDumpCommand($dumpFile);

        $process = Process::fromShellCommandline($command, null, null, null, $this->timeout);

        $process->run();

        $this->checkIfDumpWasSuccessFul($process, $dumpFile);
    }

    /**
     * Verifies if the dbname and host options are set.
     *
     * @throws \pierresilva\DbDumper\Exceptions\CannotStartDump
     * @return void
     */
    protected function guardAgainstIncompleteCredentials()
    {
        foreach (['dbName', 'host'] as $requiredProperty) {
            if (strlen($this->$requiredProperty) === 0) {
                throw CannotStartDump::emptyParameter($requiredProperty);
            }
        }
    }

    /**
     * @param string $collection
     *
     * @return \pierresilva\DbDumper\Databases\MongoDb
     */
    public function setCollection(string $collection)
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * @param string $authenticationDatabase
     *
     * @return \pierresilva\DbDumper\Databases\MongoDb
     */
    public function setAuthenticationDatabase(string $authenticationDatabase)
    {
        $this->authenticationDatabase = $authenticationDatabase;

        return $this;
    }

    /**
     * Generate the dump command for MongoDb.
     *
     * @param string $filename
     *
     * @return string
     */
    public function getDumpCommand(string $filename) : string
    {
        $quote = $this->determineQuote();

        $command = [
            "{$quote}{$this->dumpBinaryPath}mongodump{$quote}",
            "--db {$this->dbName}",
            '--archive',
        ];

        if ($this->userName) {
            $command[] = "--username '{$this->userName}'";
        }

        if ($this->password) {
            $command[] = "--password '{$this->password}'";
        }

        if (isset($this->host)) {
            $command[] = "--host {$this->host}";
        }

        if (isset($this->port)) {
            $command[] = "--port {$this->port}";
        }

        if (isset($this->collection)) {
            $command[] = "--collection {$this->collection}";
        }

        if ($this->authenticationDatabase) {
            $command[] = "--authenticationDatabase {$this->authenticationDatabase}";
        }

        return $this->echoToFile(implode(' ', $command), $filename);
    }
}
