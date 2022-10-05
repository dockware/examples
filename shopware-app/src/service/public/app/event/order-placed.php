<?php

include "../../../src/autoload.php";


$validatorSignature = new SignatureValidator();
$repoOrders = new OrderRepository();
$repoShop = new ShopRepository();


try {

    Logger::logRequest('order-placed');


    $body = Request::getBody();
    $body = json_decode($body, true);

    Logger::logData('order-placed-body', $body);


    # before we finalize our shop
    # make sure to validate its signature
    $shopId = $body['source']['shopId'];
    $shop = $repoShop->getShopByID($shopId);
    $validatorSignature->validateShopRequest($shop->getShopSecret());


    # now extract the order data
    # and save the order in our local system.
    $orderData = $body['data']['payload']['order'];


    $order = new Order(
        $orderData['id'],
        $shopId,
        $orderData['salesChannelId'],
        $orderData['orderDateTime'],
        $orderData['orderNumber']
    );

    foreach ($orderData['lineItems'] as $lineItem) {

        $label = $lineItem['label'];
        $totalPrice = $lineItem['totalPrice'];
        $quantity = $lineItem['quantity'];
        $productId = $lineItem['productId'];
        $productNumber = $lineItem['payload']['productNumber'];

        $order->addLineItem($productId, $label, $productNumber, (int)$quantity, (float)$totalPrice);
    }


    $repoOrders->saveOrder($order);


    $emailCustomer = $orderData['orderCustomer']['email'];

    $mailer = new Mailer();
    $mailer->sendMail(
        $emailCustomer,
        'Review Order ' . $order->getNumber(),
        'Click on this link: <a href="http://localhost:1000/?order=' . $order->getNumber() . '">Review Products</a>'
    );


    $client = new HttpClient(
        $shop->getShopUrl() . '/api',
        $shop->getApiKey(),
        $shop->getApiSecret()
    );

    $postData = [
        'customFields' => [
            'dockware_product_reviews_exported_date' => date('Y-m-d'),
        ]
    ];

    $response = $client->patch('order/' . $order->getSwId(), $postData);


    Response::success([], $shop);

} catch (Throwable $ex) {

    Logger::logError('order_placed', $ex->getMessage());

    Response::error($ex->getMessage());
}
