<?php

namespace Pixelated\TypeScriptTransformer\Writers;

use Pixelated\TypeScriptTransformer\Contracts\Constants;
use Spatie\Enum\Enum;
use Spatie\TypeScriptTransformer\Actions\ReplaceSymbolsInCollectionAction;
use Spatie\TypeScriptTransformer\Structures\TransformedType;
use Spatie\TypeScriptTransformer\Structures\TypesCollection;
use Spatie\TypeScriptTransformer\Writers\TypeDefinitionWriter;

class PixelatedTypeScriptWriter extends TypeDefinitionWriter
{
    public function format(TypesCollection $collection): string
    {
        (new ReplaceSymbolsInCollectionAction())->execute($collection);

        [$namespaces, $rootTypes] = $this->groupByNamespace($collection);

        $output = '';
        //TODO
        //TODO
        //TODO
        //TODO
        //$callback = static fn(TransformedType $type) => "export enum {$type->name} = {$type->transformed};";
        //TODO
        //TODO
        //TODO
        //TODO

        foreach ($namespaces as $namespace => $types) {
            asort($types);
            $output .= "namespace {$namespace} {" . PHP_EOL;

            $output .= implode(PHP_EOL, array_map([$this, 'getTypeExportString'], $types));

            $output .= PHP_EOL . "}" . PHP_EOL;
        }

        $output .= implode(PHP_EOL, array_map([$this, 'getTypeExportString'], $rootTypes));

        return $output;
    }

    private function getTypeExportString(TransformedType $type): string
    {
        if ($type->reflection->isSubclassOf(Enum::class)
            || in_array(Constants::class, $type->reflection->getInterfaceNames(), true)) {
            return "export enum {$type->name} {$type->transformed}";
        }

        return "export type {$type->name} = {$type->transformed};";
    }
}
