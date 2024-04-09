<?php

namespace Pixelated\TypeScriptTransformer\Transformers;

use ReflectionClass;
use Spatie\Enum\Enum;
use Spatie\TypeScriptTransformer\Structures\TransformedType;
use Spatie\TypeScriptTransformer\Transformers\Transformer;

class PixelatedSpatieEnumTransformer implements Transformer
{
    public function transform(ReflectionClass $class, string $name): ?TransformedType
    {
        if ($class->isSubclassOf(Enum::class) === false) {
            return null;
        }

        return TransformedType::create(
            $class,
            $name,
            $this->resolveOptions($class),
            keyword: 'enum',
        );
    }

    private function resolveOptions(ReflectionClass $class): string
    {
        /** @var \Spatie\Enum\Enum $enum */
        $enum  = $class->getName();
        $enums = $enum::toArray();

        $options = '';
        foreach ($enums as $key => $value) {
            $key     = is_numeric($key) ? $key : "\"$key\"";
            $options .= "$value = $key,\n";
        }

        return $options;
    }
}
