<?php

namespace Meiji\YandexMetrikaOffline\Http;

use Meiji\YandexMetrikaOffline\Conversion;

class Client
{
    private $token;
    private $contentType;
    private $multipart;
    private $url;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    public function addFile(\Meiji\YandexMetrikaOffline\ValueObject\ConversionFile $file)
    {
        $this->contentType = 'multipart/form-data';
        $this->multipart[] = $file->getArray();

        return $this;
    }

    public function requestPost()
    {
        $guzzle = new \GuzzleHttp\Client([
            'headers' => [
                'Authorization' => 'OAuth ' . $this->token,
                'User-Agent'    => 'MeijiYandexMetrikaOffline/' . Conversion::VERSION,
                'Content-Type'  => $this->contentType
            ]
        ]);

        $optionsArray = [];

        if (!empty($this->multipart)) {
            $optionsArray['multipart'] = $this->multipart;
        }

        $response = $guzzle->post($this->url, $optionsArray);

        return $response;
    }
}
