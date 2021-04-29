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
        $output = "// @ts-nocheck\n";

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
        if ($type->reflection->isSubclassOf(Enum::class)
            || in_array(Constants::class, $type->reflection->getInterfaceNames(), true)) {
            return "export enum $type->name $type->transformed";
        }

        return "export type $type->name = $type->transformed;";
    }
}
