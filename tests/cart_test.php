<?php
// Test 1: Add item to cart
function test_add_to_cart() {
    $cart = [];
    $item = ['id' => 1, 'name' => 'Face Cream', 'price' => 19.99, 'qty' => 1];
    $cart[] = $item;
    assert(count($cart) === 1, "Test 1 FAILED");
    echo "Test 1 PASSED: Item added to cart\n";
}

// Test 2: Calculate total
function test_cart_total() {
    $cart = [
        ['price' => 19.99, 'qty' => 2],
        ['price' => 9.99,  'qty' => 1]
    ];
    $total = array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $cart));
    assert(round($total, 2) === 49.97, "Test 2 FAILED");
    echo "Test 2 PASSED: Cart total correct\n";
}

// Test 3: Remove item from cart
function test_remove_from_cart() {
    $cart = [
        ['id' => 1, 'name' => 'Face Cream'],
        ['id' => 2, 'name' => 'Serum']
    ];
    $cart = array_filter($cart, fn($i) => $i['id'] !== 1);
    assert(count($cart) === 1, "Test 3 FAILED");
    echo "Test 3 PASSED: Item removed from cart\n";
}

test_add_to_cart();
test_cart_total();
test_remove_from_cart();
?>