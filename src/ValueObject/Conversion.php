<?php

namespace Meiji\YandexMetrikaOffline\ValueObject;

class Conversion
{
    public $ClientId;
    public $Target;
    public $DateTime;
    public $Price;
    public $Currency;

    public function __construct($ClientId, $Target, $DateTime = null, $Price = null, $Currency = null)
    {
        if (!$DateTime) {
            $DateTime = time();
        }

        $this->ClientId = $ClientId;
        $this->Target   = $Target;
        $this->DateTime = $DateTime;
        $this->Price    = $Price;
        $this->Currency = $Currency;
    }

    public function getString(array $columns = [])
    {
        $conversionString = $this->ClientId;
        foreach ($columns as $columnName) {
            $conversionString .= "," . $this->{$columnName};
        }

        return $conversionString;
    }
}
