<?php


class Order
{

    /**
     * @var string
     */
    private $swId;

    /**
     * @var string
     */
    private $shopId;

    /**
     * @var string
     */
    private $saleschannelId;

    /**
     * @var string
     */
    private $date;

    /**
     * @var string
     */
    private $number;

    /**
     * @var array
     */
    private $lineItems;


    /**
     * @param string $swId
     * @param string $shopId
     * @param string $saleschannelId
     * @param string $date
     * @param string $number
     */
    public function __construct(string $swId, string $shopId, string $saleschannelId, string $date, string $number)
    {
        $this->swId = $swId;
        $this->shopId = $shopId;
        $this->date = $date;
        $this->number = $number;
        $this->saleschannelId = $saleschannelId;

        $this->lineItems = [];
    }

    /**
     * @return string
     */
    public function getSwId(): string
    {
        return $this->swId;
    }

    /**
     * @return string
     */
    public function getShopId(): string
    {
        return $this->shopId;
    }

    /**
     * @return string
     */
    public function getSaleschannelId(): string
    {
        return $this->saleschannelId;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @return array
     */
    public function getLineItems(): array
    {
        return $this->lineItems;
    }

    /**
     * @param string $swId
     * @param string $name
     * @param string $number
     * @param int $quantity
     * @param float $price
     */
    public function addLineItem(string $swId, string $name, string $number, int $quantity, float $price)
    {
        $this->lineItems[$number] = [
            'id' => $swId,
            'name' => $name,
            'number' => $number,
            'quantity' => $quantity,
            'price' => $price,
        ];
    }

}