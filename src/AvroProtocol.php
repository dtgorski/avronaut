<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut;

use Avronaut\Render\Diag\AstDumper;
use Avronaut\Write\StandardWriter;
use Avronaut\Write\Writer;

class AvroProtocol
{
    public static function fromFileMap(AvroFileMap $fileMap): AvroProtocol
    {
        return new self($fileMap);
    }

    private function __construct(private readonly AvroFileMap $fileMap)
    {
    }

    public function getFileMap(): AvroFileMap
    {
        return $this->fileMap;
    }

    /** @throws \Exception */
    public function dump(Writer $writer = new StandardWriter()): void
    {
        $dumper = new AstDumper($writer);

        foreach ($this->getFileMap() as $node) {
            $node->accept($dumper);
        }
    }
}
