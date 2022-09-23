<?php

class Cart
{
    const DISCOUNT_BY_AMOUNT = true;
    const DISCOUNT_BY_PERCENTAGE = false;
    private $list;
    
    public function add(string $name, float $price, int $amount): void {
        if (key_exists($name, $this->list) || $amount <= 0 || $price < 0) {
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

    public function remove(string $itemName): void {
        unset($this->list[$itemName]);
    }

    public function updateItemAmount(string $itemName, int $newAmount): void {
        if (! key_exists($itemName, $this->list) || $newAmount <= 0) {
            return;
        }

        $scale = $newAmount / $this->list[$itemName]["amount"];
        $this->list[$itemName]["amount"] = $newAmount;

        $hasDiscount = $this->list[$itemName]["discountName"] != "";
        if ($hasDiscount) {
            $this->list[$itemName]["discountAmount"] *= $scale;
        }

        $this->list[$itemName]["totalPrice"] *= $scale;
    }

    public function addDiscount(string $itemName, string $discountName, float $value, bool $type): void {
        if (! key_exists($itemName, $this->list)) {
            return;
        }

        // need to avoid duplicated discount
        if ($this->list[$itemName]["discountName"] != "") {
            throw new Exception("already has a discount");
        }

        $this->list[$itemName]["discountName"] = $discountName;
        switch ($type) {
            case Cart::DISCOUNT_BY_AMOUNT:
                $this->list[$itemName]["discountAmount"] = $value * $this->list[$itemName]["amount"];
                break;

            case Cart::DISCOUNT_BY_PERCENTAGE:
                if ($value > 100 || $value < 0) {
                    throw new Exception("invalid discount value");
                }
                $this->list[$itemName]["discountAmount"] = (100 - $value) / 100 * $this->list[$itemName]["totalPrice"];
                break;
        }
        $this->list[$itemName]["totalPrice"] -= $this->list[$itemName]["discountAmount"];
    }

    public function removeDiscount(string $itemName): void {
        if (! key_exists($itemName, $this->list)) {
            return;
        }
        $this->list[$itemName]["discountName"] = "";
        $this->list[$itemName]["discountAmount"] = 0;
        $this->list[$itemName]["totalPrice"] = $this->list[$itemName]["price"] * $this->list[$itemName]["amount"];
    }

    public function getList(): array {
        return $this->list;
    }

    public function getTotal(): float {
        $total = 0.0;
        foreach($this->list as $item) {
            $total += $item["totalPrice"];
        }
        return $total;
    }
}