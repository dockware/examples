<?php


class OrderRepository
{

    const STORAGE_FOLDER = __DIR__ . '/../../_storage';


    /**
     * @param Order $order
     */
    public function saveOrder(Order $order)
    {
        $data = [
            'swId' => $order->getSwId(),
            'shopId' => $order->getShopId(),
            'salesChannelId' => $order->getSaleschannelId(),
            'date' => $order->getDate(),
            'orderNumber' => $order->getNumber(),
            'lineItems' => $order->getLineItems(),
        ];

        if (!file_exists(self::STORAGE_FOLDER)) {
            mkdir(self::STORAGE_FOLDER);
        }

        file_put_contents(self::STORAGE_FOLDER . '/order_' . $order->getNumber() . '.json', json_encode($data));
    }

    /**
     * @return array
     */
    public function getAllOrders()
    {
        if (!file_exists(self::STORAGE_FOLDER)) {
            return [];
        }

        $allFiles = scandir(self::STORAGE_FOLDER);

        $allOrders = [];

        # sort DESC
        arsort($allFiles);

        foreach ($allFiles as $file) {

            if (strpos($file, 'order_') === false) {
                continue;
            }

            $orderNumber = basename($file, ".json");
            $orderNumber = str_replace('order_', '', $orderNumber);

            $allOrders[] = $this->getOrder($orderNumber);
        }

        return $allOrders;
    }

    /**
     * @param string $orderNumber
     * @return Order
     */
    public function getOrder(string $orderNumber)
    {
        $file = self::STORAGE_FOLDER . '/order_' . $orderNumber . '.json';

        if (!file_exists($file)) {
            return null;
        }


        $content = file_get_contents($file);

        $data = json_decode($content, true);

        $order = new Order(
            $data['swId'],
            $data['shopId'],
            $data['salesChannelId'],
            $data['date'],
            $data['orderNumber']
        );

        foreach ($data['lineItems'] as $item) {

            $order->addLineItem(
                $item['id'],
                $item['name'],
                $item['number'],
                (int)$item['quantity'],
                (float)$item['price']
            );
        }

        return $order;
    }

    /**
     * @param string $swId
     * @return Order|null
     */
    public function getOrderBySwId(string $swId): Order
    {
        $orders = $this->getAllOrders();

        /** @var Order $order */
        foreach ($orders as $order) {
            if ($order->getSwId() === $swId) {
                return $order;
            }
        }
    }

}