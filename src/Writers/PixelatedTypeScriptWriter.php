<?php

namespace Pixelated\TypeScriptTransformer\Writers;

use Spatie\TypeScriptTransformer\Structures\TypesCollection;
use Spatie\TypeScriptTransformer\Writers\TypeDefinitionWriter;

class PixelatedTypeScriptWriter extends TypeDefinitionWriter
{
    public function format(TypesCollection $collection): string
    {
        $output = "// noinspection TypeScriptUnresolvedReference" . PHP_EOL;
        $output .= "// @ts-nocheck" . PHP_EOL;
        return $output . parent::format($collection);
    }
}
