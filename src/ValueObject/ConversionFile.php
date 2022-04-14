<?php

namespace Meiji\YandexMetrikaOffline\ValueObject;

use Meiji\YandexMetrikaOffline\ValueObject\ConversionHeader;
use Meiji\YandexMetrikaOffline\ValueObject\ConversionsIterator;

class ConversionFile
{
    private $name     = 'file';
    private $filename = 'data.csv';
    private $headers;
    private $dataHeader;
    private $dataConversions;

    public function __construct(ConversionHeader $dataHeader, ConversionsIterator $dataConversions)
    {
        $this->dataHeader      = $dataHeader;
        $this->dataConversions = $dataConversions;

        $this->headers = [
            'Content-Disposition' => 'form-data; name="' . $this->name . '"; filename="' . $this->filename . '"',
            'Content-Type'        => 'text/csv',
            'Content-Length'      => ''
        ];
    }

    public function getArray()
    {
        return [
            'name'     => $this->name,
            'filename' => $this->filename,
            'contents' => $this->getFileContent(),
            'headers'  => $this->headers
        ];
    }

    public function getFileContent()
    {
        $fileContent = $this->dataHeader->getString() . PHP_EOL;
        $fileContent .= $this->dataConversions->getString($this->dataHeader->getUsesColumns());

        return $fileContent;
    }
}
