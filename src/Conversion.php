<?php

namespace Meiji\YandexMetrikaOffline;

use Meiji\YandexMetrikaOffline\Scope\Upload;

class Conversion
{
    public const API_URL = 'https://api-metrika.yandex.net/management/v1';
    public const VERSION = '0.1';

    private $oAuthToken;

    public function __construct($token)
    {
        $this->oAuthToken = $token;
    }

    public function upload($counterId = null, $client_id_type = null)
    {
        return new Upload($this, $counterId, $client_id_type);
    }

    public function getHTTPClient()
    {
        return new Http\Client($this->oAuthToken);
    }
}
