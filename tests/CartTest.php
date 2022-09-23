<?php

use PHPUnit\Framework\TestCase;

final class CartTest extends TestCase
{
    public function testAdd(): void
    {
        $cart = new Cart;
        $cart->add("fish", 100, 2);
        $cart->add("milk", 180, 3);
        $this->assertEquals(
            count($cart->getList()),
            2
        );
    }

    public function testRemove(): void
    {
        $cart = new Cart;
        $cart->add("fish", 100, 2);
        $cart->add("milk", 180, 3);
        $cart->remove("fish");

        $this->assertEquals(
            count($cart->getList()),
            1
        );
    }

    public function testGetTotal(): void
    {
        $cart = new Cart;
        $cart->add("fish", 100, 2);
        $cart->add("milk", 180, 3);

        $this->assertEquals(
            $cart->getTotal(),
            740
        );
    }

    public function testUpdateItemAmount(): void
    {
        $cart = new Cart;
        $cart->add("apple", 8.89, 3);
        $cart->updateItemAmount("apple", 11);

        $this->assertEquals(
            $cart->getTotal(),
            97.79
        );
    }

    public function testGetList(): void
    {
        $cart = new Cart;
        $cart->add("fish", 100, 2);
        $cart->add("milk", 180, 3);
        $cart->addDiscount("fish", "fish-day", 10, Cart::DISCOUNT_BY_AMOUNT);
        
        $this->assertEquals(
            $cart->getList(),
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
            ]
        );
    }

    public function testAddDiscount(): void
    {
        $cart = new Cart;
        $cart->add("juice", 110, 3);
        $cart->addDiscount("juice", "birthday", 10, Cart::DISCOUNT_BY_AMOUNT);

        $this->assertEquals(

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
            $cart->getTotal(),
            247.5
        );
    }

    public function testRemoveDiscount(): void
    {
        $cart = new Cart;
        $cart->add("juice", 110, 3);
        $cart->addDiscount("juice", "birthday", 10, Cart::DISCOUNT_BY_AMOUNT);
        $cart->removeDiscount("juice");

        $this->assertEquals(
            $cart->getTotal(),
            330
        );
    }
}