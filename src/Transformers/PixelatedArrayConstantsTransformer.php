<?php

namespace Pixelated\TypeScriptTransformer\Transformers;

use Pixelated\TypeScriptTransformer\Contracts\Constants;
use ReflectionClass;
use ReflectionClassConstant;
use Spatie\TypeScriptTransformer\Structures\TransformedType;
use Spatie\TypeScriptTransformer\Transformers\Transformer;

class PixelatedArrayConstantsTransformer implements Transformer
{
    public function transform(ReflectionClass $class, string $name): ?TransformedType
    {
        if (!in_array(Constants::class, $class->getInterfaceNames())) {
            return null;
        }

        $enums = $this->resolveProperties($class);

        return TransformedType::create(
            $class,
            $name,
            $enums,
            keyword: 'enum',
        );
    }

    private function resolveProperties(ReflectionClass $class): string
    {
        $properties = $class->getConstants(ReflectionClassConstant::IS_PUBLIC);

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
