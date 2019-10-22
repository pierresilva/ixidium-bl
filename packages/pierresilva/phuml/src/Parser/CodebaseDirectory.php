<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

use SplFileInfo;

class CodebaseDirectory
{
    /** @var SplFileInfo */
    private $directory;

    public static function from(string $path): CodebaseDirectory
    {
        return new CodebaseDirectory($path);
    }

    public function absolutePath(): string
    {
        return $this->directory->getRealPath();
    }

    private function __construct(string $path)
    {
        $this->setDirectory(new SplFileInfo($path));
    }

    private function setDirectory(SplFileInfo $path): void
    {
        if (!$path->isDir()) {
            throw InvalidDirectory::notFoundAt($path);
        }
        $this->directory = $path;
    }
}
