<?php

namespace Pixelated\TypeScriptTransformer\Writers;

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
        $callback = static fn(TransformedType $type) => "export enum {$type->name} = { {$type->transformed} };";
        //TODO
        //TODO
        //TODO
        //TODO

        foreach ($namespaces as $namespace => $types) {
            asort($types);

            $output .= "namespace {$namespace} {" . PHP_EOL;

            $output .= implode(PHP_EOL, array_map(
                static fn(TransformedType $type) => "export enum {$type->name} = { {$type->transformed} };",
                $types
            ));

            $output .= PHP_EOL . "}" . PHP_EOL;
        }

        $output .= implode(PHP_EOL, array_map(
            static fn(TransformedType $type) => "export enum {$type->name} { {$type->transformed} };",
            $rootTypes
        ));

        return $output;
    }
}
