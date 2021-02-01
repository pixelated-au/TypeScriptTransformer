<?php
/**
 * Project: StreamEdge
 * Owner: Pixelated
 * Copyright: 2021
 */

namespace Pixelated\TypeScriptTransformer\Transformers;

use JetBrains\PhpStorm\Pure;
use Pixelated\TypeScriptTransformer\Contracts\Constants;
use ReflectionClass;
use Spatie\TypeScriptTransformer\Structures\TransformedType;
use Spatie\TypeScriptTransformer\Transformers\Transformer;

class PixelatedArrayConstantsTransformer implements Transformer
{
    #[Pure] public function canTransform(ReflectionClass $class): bool
    {
        return in_array(Constants::class, $class->getInterfaceNames());
    }

    public function transform(ReflectionClass $class, string $name): TransformedType
    {
        // Get the name of the TypeScript type
        $tsName = call_user_func([$class->getName(), 'getTypeScriptName']);


        //ray($tsName, $name, $class->getConstants(\ReflectionClassConstant::IS_PUBLIC));

        $enums = $this->resolveProperties($class);
        return TransformedType::create($class, $name, "export enum $name {\n$enums\n}");
    }

    private function resolveProperties(ReflectionClass $class)
    {
        $properties = $class->getConstants(\ReflectionClassConstant::IS_PUBLIC);

        $props = [];
        foreach ($properties as $key => $value) {
            $name = $value['name'];
            $props[] = "$key = $name";
        }

        return implode(",\n", $props);
    }
}
