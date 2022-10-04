<?php

class Response
{

    /**
     * @param array $data
     * @param Shop $shop
     * @return void
     */
    public static function success(array $data, Shop $shop): void
    {
        $validatorSignature = new SignatureValidator();
        $signature = $validatorSignature->signBody($data, $shop->getShopSecret());

        header('shopware-app-signature: ' . $signature);

        die(json_encode($data));
    }

    /**
     * @param string $message
     */
    public static function error(string $message)
    {
        $data = [
            'error' => $message,
        ];

        http_response_code(500);

        die(json_encode($data));
    }

}
