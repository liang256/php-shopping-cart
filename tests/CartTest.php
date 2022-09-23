<?php

use PHPUnit\Framework\TestCase;

final class CartTest extends TestCase
{
    public function testAdd(): void
    {
        $cart = new Cart;
        $cart->add("fish", 100, 2);
        $cart->add("milk", 180, 3);
        $cart->add("milk", 10000, 3);
        $this->assertEquals(
            2,
            count($cart->getList())
        );
    }

    public function testRemove(): void
    {
        $cart = new Cart;
        $cart->add("fish", 100, 2);
        $cart->add("milk", 180, 3);
        $cart->remove("fish");

        $this->assertEquals(
            1,
            count($cart->getList())
        );
    }

    public function testGetTotal(): void
    {
        $cart = new Cart;
        $cart->add("fish", 100, 2);
        $cart->add("milk", 180, 3);

        $this->assertEquals(
            740,
            $cart->getTotal()
        );
    }

    public function testUpdateItemAmount(): void
    {
        $cart = new Cart;
        $cart->add("apple", 8.89, 3);
        $cart->updateItemAmount("apple", 11);

        $this->assertEquals(
            97.79,
            $cart->getTotal()
        );
    }

    public function testGetList(): void
    {
        $cart = new Cart;
        $cart->add("fish", 100, 2);
        $cart->add("milk", 180, 3);
        $cart->addDiscount("fish", "fish-day", 10, Cart::DISCOUNT_BY_AMOUNT);
        
        $this->assertEquals(
            [
                "milk" => [
                    "amount" => 3,
                    "price" => 180,
                    "totalPrice" => 540,
                    "discountName" => "",
                    "discountAmount" => 0,
                ],
                "fish" => [
                    "amount" => 2,
                    "price" => 100,
                    "totalPrice" => 180,
                    "discountName" => "fish-day",
                    "discountAmount" => 20,
                ],
            ],
            $cart->getList()
        );
    }

    public function testAddDiscount(): void
    {
        $cart = new Cart;
        $cart->add("juice", 110, 3);
        $cart->addDiscount("juice", "birthday", 10, Cart::DISCOUNT_BY_AMOUNT);

        $this->assertEquals(
            300,
            $cart->getTotal()
        );
    }

    public function testAddOverDiscount(): void
    {
        $cart = new Cart;
        $cart->add("juice", 100, 3);
        $cart->addDiscount("juice", "birthday", 1000, Cart::DISCOUNT_BY_AMOUNT);

        $this->assertEquals(
            0,
            $cart->getTotal()
        );
        
        $this->assertEquals(
            300,
            $cart->getList()["juice"]["discountAmount"]
        );
    }

    public function testAddDiscountPercentage(): void
    {
        $cart = new Cart;
        $cart->add("juice", 110, 3);
        $cart->addDiscount("juice", "birthday", 75, Cart::DISCOUNT_BY_PERCENTAGE);

        $this->assertEquals(
            247.5,
            $cart->getTotal()
        );
    }

    public function testRemoveDiscount(): void
    {
        $cart = new Cart;
        $cart->add("juice", 110, 3);
        $cart->addDiscount("juice", "birthday", 10, Cart::DISCOUNT_BY_AMOUNT);
        $cart->removeDiscount("juice");

        $this->assertEquals(
            330,
            $cart->getTotal()
        );
    }
}