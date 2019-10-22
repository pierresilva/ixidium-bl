<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Processors;

use PhUml\Code\Codebase;
use PhUml\Code\Summary;
use PhUml\Templates\TemplateEngine;
use PhUml\Templates\TemplateFailure;

/**
 * It takes a code `Structure` and extracts a `Summary` of its contents as text
 */
class StatisticsProcessor extends Processor
{
    /** @var TemplateEngine */
    private $engine;

    public function __construct(TemplateEngine $engine = null)
    {
        $this->engine = $engine ?? new TemplateEngine();
    }

    public function name(): string
    {
        return 'Statistics';
    }

    /**
     * @throws TemplateFailure
     */
    public function process(Codebase $codebase): string
    {
        $summary = new Summary();
        $summary->from($codebase);

        return $this->engine->render('statistics.txt.twig', ['summary' => $summary]);
    }
}
