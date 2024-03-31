<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avronaut\Load;

use Avronaut\AvroFileMap;
use Avronaut\AvroFilePath;

/** @internal */
interface Loader
{
    /** @throws \Exception */
    public function load(AvroFilePath $filePath): AvroFileMap;
}
