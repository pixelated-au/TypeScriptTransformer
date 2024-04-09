<?php

namespace Pixelated\TypeScriptTransformer\Writers;

use Spatie\TypeScriptTransformer\Structures\TransformedType;
use Spatie\TypeScriptTransformer\Structures\TypesCollection;
use Spatie\TypeScriptTransformer\Writers\ModuleWriter;

class PixelatedTypeScriptWriter extends ModuleWriter
{
    /**
     * @throws \Exception
     */
    public function format(TypesCollection $collection): string
    {
        $output = "// noinspection TypeScriptUnresolvedReference" . PHP_EOL;
        $output .= "// @ts-nocheck" . PHP_EOL;

        $types = $collection->getIterator();

        $types->uasort(fn(TransformedType $a, TransformedType $b) => strcmp($a->name, $b->name));
        foreach ($types as $type) {
            if ($type->isInline) {
                continue;
            }
            $output .= "export {$type->toString()}" . PHP_EOL;
        }

        return $output;
    }
}
