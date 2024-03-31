<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Node;

use Avronaut\Tree\AstNode;
use Avronaut\Tree\Properties;

class DecimalTypeNode extends AstNode
{
    public function __construct(
        private readonly int $precision,
        private readonly int $scale,
        ?Properties $properties = null
    ) {
        parent::__construct($properties);

        if ($precision < 1) {
            throw new \InvalidArgumentException('unexpected invalid decimal type precision');
        }
        if ($scale < 0) {
            throw new \InvalidArgumentException('unexpected invalid decimal type scale');
        }
        if ($scale > $this->precision) {
            throw new \InvalidArgumentException('unexpected invalid decimal type scale');
        }
    }

    public function getPrecision(): int
    {
        return $this->precision;
    }

    public function getScale(): int
    {
        return $this->scale;
    }
}
