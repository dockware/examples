<?php

class Shop
{

    /**
     * @var string
     */
    private $shopId;

    /**
     * @var string
     */
    private $shopUrl;

    /**
     * @var string
     */
    private $shopSecret;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $apiSecret;

    /**
     * @param string $shopId
     * @param string $shopUrl
     * @param string $shopSecret
     * @param string $apiKey
     * @param string $apiSecret
     */
    public function __construct($shopId, $shopUrl, $shopSecret, $apiKey, $apiSecret)
    {
        $this->shopId = $shopId;
        $this->shopUrl = $shopUrl;
        $this->shopSecret = $shopSecret;
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    /**
     * @return string
     */
    public function getShopId()
    {
        return $this->shopId;
    }

    /**
     * @return string
     */
    public function getShopUrl()
    {
        return $this->shopUrl;
    }

    /**
     * @return string
     */
    public function getShopSecret()
    {
        return $this->shopSecret;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @return string
     */
    public function getApiSecret()
    {
        return $this->apiSecret;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param string $apiSecret
     */
    public function setApiSecret($apiSecret)
    {
        $this->apiSecret = $apiSecret;
    }

}