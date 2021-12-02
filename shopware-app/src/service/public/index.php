<?php

include "../src/autoload.php";


$orderNumber = isset($_GET['order']) ? $_GET['order'] : '';


$storage = new OrderRepository();

$pageOrder = null;
if (!empty($orderNumber)) {
    $pageOrder = $storage->getOrder($orderNumber);
}

?>

<html data-theme="light">

<head>
    <link rel="stylesheet" href="css/pico.min.css">
</head>
<body>


<main class="container">
    <h1>Product Review System</h1>
    <p>
        Thank you for your order in our dockware shop!<br/>
        You can now review your products in our external app system.<br/>
        Afterwards you'll be able to see your review in our shop.
    </p>

    <?php

    if (!empty($orderNumber) && $pageOrder == null) {
        ?>
        <article style="background-color: red; padding: 40px;padding-bottom:10px;">
            <h5 style="color:white;">Order with number <?php echo $orderNumber; ?> not found!</h5>
        </article>
        <?php
    }

    if ($pageOrder === null) {

        ?>
        <table>
            <thead>
            <tr>
                <th scope="col">Date</th>
                <th scope="col">Number</th>
                <th scope="col">Products</th>
                <th scope="col" style="width:5%;"></th>
            </tr>
            </thead>
            <tbody>

            <?php
            $allOrders = $storage->getAllOrders();

            /** @var Order $order */
            foreach ($allOrders as $order) {
                ?>
                <tr>
                    <th scope="row"><?php echo date('Y-m-d H:i:s', strtotime($order->getDate())); ?></th>
                    <td><?php echo $order->getNumber() ?></td>
                    <td><?php echo count($order->getLineItems()); ?></td>
                    <td>
                        <a href="?order=<?php echo $order->getNumber(); ?>">
                            <button class="btn btn-primary outline" type="submit" style="width: 150px;height: auto;font-size: 15px; margin:0;">Open</button>
                        </a>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>

        <?php
    }


    if ($pageOrder !== null) {
        ?>

        <article>
            <header>Order <strong>#<?php echo $orderNumber; ?></strong></header>

            <h6>Your Products</h6>
            <?php

            $pageOrder = $storage->getOrder($orderNumber);

            $productIndex = 1;

            foreach ($pageOrder->getLineItems() as $productNumber => $item) {
                ?>

                <details>
                    <summary>
                        #<?php echo $productIndex . ' ' . $item['name']; ?>
                    </summary>
                    <p>
                    <form method="post" action="send.php">
                        <input type="hidden" name="orderNumber" value="<?php echo $pageOrder->getNumber(); ?>">
                        <input type="hidden" name="productId" value="<?php echo $item['id']; ?>">

                        <label for="title">
                            Title
                            <input type="text" id="title" name="title" placeholder="Title" required>
                        </label>

                        <label for="stars">
                            Stars
                            <select name="stars">
                                <option value="1">&#11088;</option>
                                <option value="2">&#11088;&#11088;</option>
                                <option value="3">&#11088;&#11088;&#11088;</option>
                                <option value="4">&#11088;&#11088;&#11088;&#11088;</option>
                                <option value="5">&#11088;&#11088;&#11088;&#11088;&#11088;</option>
                            </select>
                        </label>

                        <label for="review">
                            Review
                            <textarea name="review" rows="5" required></textarea>
                        </label>

                        <button class="w-100 btn btn-primary btn-sm" type="submit" style="background-color: #008490;">Submit Review</button>
                    </form>
                    </p>
                </details>

                <?php

                $productIndex++;
            }

            ?>
        </article>

        <a href="/">
            <button class="btn btn-primary btn-sm secondary" type="submit">Back</button>
        </a>

        <?php
    }
    ?>

</main>
</body>
</html>
