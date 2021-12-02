<?php

class SignatureValidator
{

    /**
     * @param string $shopSecret
     * @throws Exception
     */
    public function validateShopRequest(string $shopSecret)
    {
        $body = file_get_contents('php://input');
        $data = json_decode($body, true);

        $requestSignature = $_SERVER['HTTP_SHOPWARE_SHOP_SIGNATURE'];

        $verificationSignature = \hash_hmac('sha256', $body, $shopSecret);

        if ($verificationSignature !== $requestSignature) {
            throw new Exception('Invalid Shop Signature');
        }
    }

}