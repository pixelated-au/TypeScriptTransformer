<?php
/**
 * Project: StreamEdge
 * Owner: Pixelated
 * Copyright: 2021
 */

namespace Pixelated\TypeScriptTransformer\Transformers;

use Pixelated\TypeScriptTransformer\Contracts\Constants;
use ReflectionClass;
use Spatie\TypeScriptTransformer\Structures\TransformedType;
use Spatie\TypeScriptTransformer\Transformers\Transformer;

class PixelatedArrayConstantsTransformer implements Transformer
{
    public function canTransform(ReflectionClass $class): bool
    {
        return in_array(Constants::class, $class->getInterfaceNames());
    }

    public function transform(ReflectionClass $class, string $name): TransformedType
    {
        $enums = $this->resolveProperties($class);

        return TransformedType::create($class, $name, "{\n$enums\n}");
    }

    private function resolveProperties(ReflectionClass $class): string
    {
        $properties = $class->getConstants(\ReflectionClassConstant::IS_PUBLIC);

        $props = [];
        foreach ($properties as $key => $value) {
            if (is_array($value)) {
                $name    = $value['name'];
                $props[] = "    $key = \"$name\"";
            }
        }

        return implode(",\n", $props);
    }
}
