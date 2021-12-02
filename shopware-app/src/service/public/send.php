<?php

include "../src/autoload.php";


$orderNumber = $_POST['orderNumber'];
$productId = $_POST['productId'];
$title = $_POST['title'];
$review = $_POST['review'];
$stars = $_POST['stars'];

$repoOrders = new OrderRepository();
$repoShops = new ShopRepository();

$order = $repoOrders->getOrder($orderNumber);
$shop = $repoShops->getShopByID($order->getShopId());


$client = new HttpClient(
    $shop->getShopUrl() . '/api',
    $shop->getApiKey(),
    $shop->getApiSecret()
);


$postData = [
    'productId' => $productId,
    'salesChannelId' => $order->getSaleschannelId(),
    'title' => $title,
    'content' => $review,
    'points' => (int)$stars,
    'status' => true,
];

$response = $client->post('product-review', $postData);

$statusCode = $response[0];
?>


<html data-theme="light">

<head>
    <link rel="stylesheet" href="css/pico.min.css">
</head>

<body>


<main class="container">
    <h1>Product Review</h1>

    <?php
    if ($statusCode < 200 || $statusCode >= 300) {
        ?>
        <article style="background-color: red; color:white;">
            <h5 style="color:white;">Ouch, there was an error!</h5>
            <?php var_dump($response); ?>
        </article>
        <?php
    } else {
        ?>
        <p>
            Thank you for your review!<br/>
            You can now open the product in the shop where you can view
            your review!
        </p>
        <?php
    }


    $productURL = 'http://localhost/detail/' . $productId;

    ?>

    <a href="<?php echo $productURL; ?>" target="_blank">
        <button class="btn btn-primary btn-sm" type="submit" style="background-color: #008490;">Open your Review</button>
    </a>

    <a href="/?order=<?php echo $orderNumber; ?>">
        <button class="btn btn-primary btn-sm secondary" type="submit">Back</button>
    </a>
</main>

</body>
</html>