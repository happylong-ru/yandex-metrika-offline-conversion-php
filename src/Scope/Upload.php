<?php

namespace Meiji\YandexMetrikaOffline\Scope;

use Meiji\YandexMetrikaOffline\ValueObject\Conversion;

class Upload
{
    const CLIENT_ID_TYPE_USER = 'USER_ID';
    const CLIENT_ID_TYPE_CLIENT = 'CLIENT_ID';
    const SCOPE_PATH = 'upload';

    private $conversionInstance;
    private $client_id_type;
    private $counterId;
    private $header;
    private $conversions;
    private $comment;

    public function __construct(
        \Meiji\YandexMetrikaOffline\Conversion $conversionInstance,
        $counterId,
        $client_id_type = self::CLIENT_ID_TYPE_CLIENT
    ) {
        $this->conversionInstance = $conversionInstance;
        $this->counterId($counterId);
        $this->clientIdType($client_id_type);
        $this->header      = new \Meiji\YandexMetrikaOffline\ValueObject\ConversionHeader($this->client_id_type);
        $this->conversions = new \Meiji\YandexMetrikaOffline\ValueObject\ConversionsIterator();
    }

    public function clientIdType($type)
    {
        if ($type == self::CLIENT_ID_TYPE_USER || $type == self::CLIENT_ID_TYPE_CLIENT) {
            $this->client_id_type = $type;
        }

        return $this;
    }

    public function counterId($id)
    {
        $this->counterId = $id;

        return $this;
    }

    public function comment($text)
    {
        $this->comment = $text;

        return $this;
    }

    public function addConversion($cid, $target, $dateTime = null, $price = null, $currency = null)
    {
        if ($price) {
            $this->header->addUsesColumn('Price');
        }

        if ($currency) {
            $this->header->addUsesColumn('Currency');
        }

        $conversion = new Conversion($cid, $target, $dateTime, $price, $currency);

        $this->conversions->append($conversion);

        return $conversion;
    }

    public function send()
    {
        $requestUrl = \Meiji\YandexMetrikaOffline\Conversion::API_URL
            . '/counter/'
            . $this->counterId
            . '/offline_conversions/'
            . self::SCOPE_PATH
            . '?client_id_type='
            . $this->client_id_type;

        if ($this->comment) {
            $requestUrl .= '&comment=' . $this->comment;
        }

        $result = false;

            $response = $this->conversionInstance->getHTTPClient()
            ->setUrl($requestUrl)
            ->addFile(new \Meiji\YandexMetrikaOffline\ValueObject\ConversionFile($this->header, $this->conversions))
            ->requestPost();

        if ($response->getStatusCode() === 200) {
            $result = \json_decode((string)$response->getBody());
        }

        return $result;
    }
}
