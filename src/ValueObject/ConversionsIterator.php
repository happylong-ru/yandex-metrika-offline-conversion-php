<?php

namespace Meiji\YandexMetrikaOffline\ValueObject;

class ConversionsIterator extends \ArrayIterator
{
    public function __construct(array $array = [], $flags = 0)
    {
        parent::__construct($array, $flags);
    }

    public function getString(array $columns = [])
    {
        $conversionString = null;

        foreach ($this as $key => $conversion) {
            $conversionString .= $conversion->getString($columns) . (($key == count($this) - 1) ? null : PHP_EOL);
        }

        return $conversionString;
    }
}
