<?php

include "../../../../src/autoload.php";

try {

    $validatorSignature = new SignatureValidator();
    $repoOrders = new OrderRepository();
    $repoShop = new ShopRepository();

    Logger::logRequest('admin-get-reviews');

    $body = Request::getBody();
    $body = json_decode($body, true);

    Logger::logData('admin-get-reviews', $body);

    # before we finalize our shop
    # make sure to validate its signature
    $shopId = $body['source']['shopId'];
    $shop = $repoShop->getShopByID($shopId);
    $validatorSignature->validateShopRequest($shop->getShopSecret());

    $orderId = $body['data']['ids'][0];

    $repoOrders = new OrderRepository();

    $order = $repoOrders->getOrderBySwId($orderId);

    $body = [
        "actionType" => "openNewTab",
        "payload" => [
            "redirectUrl" => "http://localhost:1000/?order=" . $order->getNumber()
        ]
    ];

    Response::success($body, $shop);

} catch (Throwable $ex) {

    Logger::logError('admin_get_reviews', $ex->getMessage());

    Response::error($ex->getMessage());
}
