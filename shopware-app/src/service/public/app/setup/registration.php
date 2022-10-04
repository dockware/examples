<?php

include "../../../src/autoload.php";


$repoShops = new ShopRepository();

try {

    Logger::logRequest('registration');


    # we start by verifying the signature
    # of our initial registration request
    $requestSignature = $_SERVER['HTTP_SHOPWARE_APP_SIGNATURE'];
    $verificationSignature = hash_hmac('sha256', $_SERVER['QUERY_STRING'], AppData::APP_SECRET);

    if ($verificationSignature !== $requestSignature) {
        throw new Exception('Invalid Signature in registration');
    }


    # our request is valid
    # so let's create a new shop and also
    # assign a random secret key for that shop
    $shopSecretKey = RandomString::generateRandomString(20);

    $shop = new Shop(
        $_GET['shop-id'],
        $_GET['shop-url'],
        $shopSecretKey,
        '',
        ''
    );

    $repoShops->saveShop($shop);


    # now we have to build the PROFF that we
    # are really the server backend for this app.
    # this will be returned to Shopware
    parse_str($_SERVER['QUERY_STRING'], $queryValues);
    $proof = hash_hmac('sha256', $shop->getShopId() . $shop->getShopUrl() . AppData::APP_NAME, AppData::APP_SECRET);


    $responseData = [
        "proof" => $proof,
        "secret" => $shop->getShopSecret(),
        "confirmation_url" => "http://server/app/setup/confirm.php"
    ];


    Logger::logData('response', $responseData);

    die(json_encode($responseData));

} catch (Throwable $ex) {

    Response::error($ex->getMessage());
}
