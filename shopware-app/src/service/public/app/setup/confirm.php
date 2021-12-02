<?php

include "../../../src/autoload.php";

$repoShops = new ShopRepository();
$validatorSignature = new SignatureValidator();


try {

    Logger::logRequest('confirm');

    $body = file_get_contents('php://input');
    $data = json_decode($body, true);

    # get our shop model for the
    # provided shopId
    $shop = $repoShops->getShopByID($data['shopId']);

    # before we finalize our shop
    # make sure to validate its signature
    $validatorSignature->validateShopRequest($shop->getShopSecret());

    # everything is fine,
    # so lets update our data and add the
    # api keys that we get from shopware
    $shop->setApiKey($data['apiKey']);
    $shop->setApiSecret($data['secretKey']);

    $repoShops->saveShop($shop);

} catch (Throwable $ex) {

    Response::error($ex->getMessage());
}
