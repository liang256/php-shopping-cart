<?php

use PHPUnit\Framework\TestCase;

final class CartTest extends TestCase
{
    public function test_add(): void
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

    public function test_remove(): void
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

    public function test_get_total(): void
    {
        $cart = new Cart;
        $cart->add("fish", 100, 2);
        $cart->add("milk", 180, 3);

        $this->assertEquals(
            740,
            $cart->getTotal()
        );
    }

    public function test_update_item_amount(): void
    {
        $cart = new Cart;
        $cart->add("apple", 8.89, 3);
        $cart->updateItemAmount("apple", 11);

        $this->assertEquals(
            97.79,
            $cart->getTotal()
        );
    }

    public function test_get_list(): void
    {
        $cart = new Cart;
        $cart->add("fish", 100, 2);
        $cart->add("milk", 180, 3);
        $cart->addDiscount("fish", "fish-day", 10);
        
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

    public function test_add_discount(): void
    {
        $cart = new Cart;
        $cart->add("juice", 110, 3);
        $cart->addDiscount("juice", "birthday", 10);

        $this->assertEquals(
            300,
            $cart->getTotal()
        );
    }

    public function test_add_over_discount(): void
    {
        $cart = new Cart;
        $cart->add("juice", 100, 3);
        $cart->addDiscount("juice", "birthday", 1000);

        $this->assertEquals(
            0,
            $cart->getTotal()
        );
        
        $this->assertEquals(
            300,
            $cart->getList()["juice"]["discountAmount"]
        );
    }

    public function test_add_discount_by_percentage(): void
    {
        $cart = new Cart;
        $cart->add("juice", 110, 3);
        $cart->addPercentDiscount("juice", "birthday", 75);

        $this->assertEquals(
            247.5,
            $cart->getTotal()
        );
    }

    public function test_remove_discount(): void
    {
        $cart = new Cart;
        $cart->add("juice", 110, 3);
        $cart->addDiscount("juice", "birthday", 10);
        $cart->removeDiscount("juice");

        $this->assertEquals(
            330,
            $cart->getTotal()
        );
    }
}