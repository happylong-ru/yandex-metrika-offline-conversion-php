<?php

namespace Meiji\YandexMetrikaOffline\ValueObject;

use Meiji\YandexMetrikaOffline\Scope\Upload;

class ConversionHeader
{
    const CLIENT_ID_TYPE_USER_COLUMN_NAME = 'UserId';
    const CLIENT_ID_TYPE_CIENT_COLUMN_NAME = 'ClientId';

    private static $availableColumns = ['UserId', 'ClientId', 'Target', 'DateTime', 'Price', 'Currency'];
    private $ClientIdType;
    private $usesColumns;

    public function __construct(&$client_id_type = null)
    {
        $this->ClientIdType = &$client_id_type;

        $this->setDefaultUsesColumns();
    }

    public function getString()
    {
        $typeColumnName = $this->ClientIdType == Upload::CLIENT_ID_TYPE_USER
            ? self::CLIENT_ID_TYPE_USER_COLUMN_NAME
            : self::CLIENT_ID_TYPE_CIENT_COLUMN_NAME;

        $headerString = $typeColumnName;
        foreach ($this->usesColumns as $columnName) {
            $headerString .= ",".$columnName;
        }

        return $headerString;
    }

    public function setDefaultUsesColumns()
    {
        $this->usesColumns = [];
        $this->addUsesColumn('Target');
        $this->addUsesColumn('DateTime');
    }

    public function addUsesColumn($name)
    {
        if (in_array($name, self::$availableColumns)) {
            $this->usesColumns[] = $name;
        }
    }

    public function getUsesColumns()
    {
        return $this->usesColumns;
    }

    public function count()
    {
        return count($this->usesColumns) + 1;
    }
}
