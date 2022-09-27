<?php

class Cart
{
    private $list;
    
    public function __construct()
    {
        $this->list = [];
    }
    
    /**
     * Add an item to this cart
     * 
     * @param string $name   The item's name
     * @param float  $price  Unit price of the item
     * @param int    $amount Amount of the item
     * 
     * @return void
     */
    public function add(string $name, float $price, int $amount): void
    {
        if (key_exists($name, $this->list) || $amount <= 0 || $price < 0 || empty($name)) {
            return;
        }
        $this->list[$name] = [
            "amount" => $amount,
            "price" => $price,
            "totalPrice" => $amount * $price,
            "discountName" => "",
            "discountAmount" => 0,
        ];
    }

    /**
     * Remove an item from this cart
     * 
     * @param string $name The name of the item to remove
     * 
     * @return void
     */
    public function remove(string $itemName): void
    {
        unset($this->list[$itemName]);
    }

    /**
     * Modify the amount of the given item
     * 
     * @param string $itemName The item's name
     * @param int    $amount   New amount of the item
     * 
     * @return void
     */
    public function updateItemAmount(string $itemName, int $newAmount): void
    {
        if (! key_exists($itemName, $this->list) || $newAmount <= 0) {
            return;
        }

        $scale = $newAmount / $this->list[$itemName]["amount"];
        $this->list[$itemName]["amount"] = $newAmount;

        if (! empty($this->list[$itemName]["discountName"])) {
            $this->list[$itemName]["discountAmount"] *= $scale;
        }

        $this->list[$itemName]["totalPrice"] *= $scale;
    }

    /**
     * Assign a discount to the given item
     * 
     * @param string $itemName     The item's name
     * @param string $discountName The discount's name
     * @param float  $value        Value of the discount
     * 
     * @return void
     */
    public function addDiscount(string $itemName, string $discountName, float $value): void
    {
        if (! key_exists($itemName, $this->list) || empty($discountName || $value < 0)) {
            return;
        }

        // need to avoid duplicated discount
        if (! empty($this->list[$itemName]["discountName"])) {
            throw new Exception("already has a discount");
        }

        // avoid discount greater than the original price
        $value = min($value, $this->list[$itemName]["price"]);
    
        $this->list[$itemName]["discountName"] = $discountName;
        $this->list[$itemName]["discountAmount"] = $value * $this->list[$itemName]["amount"];
        $this->list[$itemName]["totalPrice"] -= $this->list[$itemName]["discountAmount"];
    }

    /**
     * Assign a discount to the given item by percentage
     * 
     * @param string $itemName     The item's name
     * @param string $discountName The discount's name
     * @param float  $value        Percent of the discount. value 70 means 30% off
     * 
     * @return void
     */
    public function addPercentDiscount(string $itemName, string $discountName, float $value): void
    {
        if (! key_exists($itemName, $this->list) || empty($discountName)) {
            return;
        }

        // need to avoid duplicated discount
        if (! empty($this->list[$itemName]["discountName"])) {
            throw new Exception("already has a discount");

        } elseif ($value > 100 || $value < 0) {
            throw new Exception("invalid discount percent");
        }
    
        $this->list[$itemName]["discountName"] = $discountName;
        $this->list[$itemName]["discountAmount"] = (100 - $value) / 100 * $this->list[$itemName]["totalPrice"];
        $this->list[$itemName]["totalPrice"] -= $this->list[$itemName]["discountAmount"];
    }

    /**
     * Remove the discount from the given item
     * 
     * @param string $itemName The item's name
     * 
     * @return void
     */
    public function removeDiscount(string $itemName): void
    {
        if (! key_exists($itemName, $this->list)) {
            return;
        }
        $this->list[$itemName]["discountName"] = "";
        $this->list[$itemName]["discountAmount"] = 0;
        $this->list[$itemName]["totalPrice"] = $this->list[$itemName]["price"] * $this->list[$itemName]["amount"];
    }

    /**
     * Return the list of this cart
     *
     * @return array
     */
    public function getList(): array
    {
        return $this->list;
    }

    /**
     * Return the total price of this cart
     *
     * @return float
     */
    public function getTotal(): float
    {
        $total = 0.0;
        foreach($this->list as $item) {
            $total += $item["totalPrice"];
        }
        return $total;
    }
}