<?php


class ShopRepository
{
    const STORAGE_FOLDER = __DIR__ . '/../../_storage';

    const STORAGE_FILENAME_SHOPS = self::STORAGE_FOLDER . '/shops.json';


    /**
     * @param Shop $shop
     */
    public function saveShop(Shop $shop)
    {
        $shops = $this->getAllShops();

        $shops[$shop->getShopId()] = [
            'shopId' => $shop->getShopId(),
            'shopUrl' => $shop->getShopUrl(),
            'shopSecret' => $shop->getShopSecret(),
            'apiKey' => $shop->getApiKey(),
            'secretKey' => $shop->getApiSecret(),
        ];

        if (!file_exists(self::STORAGE_FOLDER)) {
            mkdir(self::STORAGE_FOLDER);
        }

        file_put_contents(self::STORAGE_FILENAME_SHOPS, json_encode($shops));
    }

    /**
     * @param string $shopId
     * @return Shop|null
     */
    public function getShopByID(string $shopId)
    {
        $allShops = $this->getAllShops();

        if (!array_key_exists($shopId, $allShops)) {
            return null;
        }

        $shopData = $allShops[$shopId];

        return $this->toEntity($shopData);
    }

    /**
     * @return array|void
     */
    public function getAllShops()
    {
        if (!file_exists(self::STORAGE_FILENAME_SHOPS)) {
            return [];
        }

        $content = file_get_contents(self::STORAGE_FILENAME_SHOPS);

        return json_decode($content, true);
    }

    /**
     * @param array $shopData
     * @return Shop
     */
    public function toEntity(array $shopData): Shop
    {
        return new  Shop(
            $shopData['shopId'],
            $shopData['shopUrl'],
            $shopData['shopSecret'],
            $shopData['apiKey'],
            $shopData['secretKey']
        );
    }

}