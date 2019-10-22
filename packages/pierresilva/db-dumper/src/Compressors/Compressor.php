<?php

namespace pierresilva\DbDumper\Compressors;

interface Compressor
{
    public function useCommand(): string;

    public function useExtension(): string;
}
