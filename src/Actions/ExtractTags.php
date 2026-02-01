<?php

namespace Nadi\CakePHP\Actions;

use ReflectionClass;

class ExtractTags
{
    public static function from($target): array
    {
        if ($tags = static::explicitTags([$target])) {
            return $tags;
        }

        return [];
    }

    public static function fromArray(array $data): array
    {
        return [];
    }

    protected static function explicitTags(array $targets): array
    {
        $tags = [];

        foreach ($targets as $target) {
            if (is_object($target) && method_exists($target, 'tags')) {
                $tags = array_merge($tags, $target->tags());
            }
        }

        return array_unique($tags);
    }
}
