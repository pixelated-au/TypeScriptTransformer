<?php

namespace Pixelated\TypeScriptTransformer\Writers;

use Pixelated\TypeScriptTransformer\Contracts\Constants;
use Spatie\Enum\Enum;
use Spatie\TypeScriptTransformer\Structures\TransformedType;
use Spatie\TypeScriptTransformer\Structures\TypesCollection;
use Spatie\TypeScriptTransformer\Writers\ModuleWriter;

class PixelatedTypeScriptWriter extends ModuleWriter
{
    public function format(TypesCollection $collection): string
    {
        $output = "// noinspection TypeScriptUnresolvedReference" . PHP_EOL;
        $output .= "// @ts-nocheck" . PHP_EOL;

        $iterator = $collection->getIterator();

        $iterator->uasort(function (TransformedType $a, TransformedType $b) {
            return strcmp($a->name, $b->name);
        });

        foreach ($iterator as $type) {
            if ($type->isInline) {
                continue;
            }
            $output .= $this->getTypeExportString($type);
        }

        return $output;
    }

    private function getTypeExportString(TransformedType $type): string
    {
        return "export $type->keyword $type->name $type->transformed;" . PHP_EOL;
    }
}
