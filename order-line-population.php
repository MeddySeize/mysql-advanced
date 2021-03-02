<?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "course";

try {
    $conn = new PDO("mysql:host=$servername;dbname=".$db, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die;
}

$orders = $conn->query("SELECT * FROM `order`")->fetchAll(PDO::FETCH_ASSOC);

foreach ($orders as $order) {

    $numProductOrdered = mt_rand(1, 20);

    for ($i=0; $i<$numProductOrdered; $i++) {
        $productId = mt_rand(1, 6584);

        $product = $conn->query("SELECT * FROM product WHERE id=$productId")->fetch(PDO::FETCH_ASSOC);

        if (!$product) continue;

        if (is_numeric($product['selling_price'])) {
            $price = $product['selling_price'];
        } elseif (is_numeric($product['cost_price'])) {
            $price = $product['cost_price'] * 1.2;
        } else {
            $price = mt_rand(150, 3000);
        }

        $shouldPriceBeDifferent = (bool) mt_rand(0, 1);

        if ($shouldPriceBeDifferent) {
            $variation = mt_rand(-12, 15);
            if ($variation !== 0) {
                if ($variation > 0) {
                    $price = $price * (1 + ($variation/100));
                } else {
                    $price = $price * (1 - ($variation/100));
                }
            }
        }

        $quantity = mt_rand(1, 15);

        $vatRate = $product["category"] === 'food' ? 5.5 : 20;

        $vat = $price *  ($vatRate/100);

        $sold_price_vat_included = $price + $vat;

        $total = $sold_price_vat_included * $quantity;

        $query = $conn->prepare("INSERT INTO `order_line` (`order`, product, quantity, sold_price_vat_excluded, vat, sold_price_vat_included, total)
                    VALUES (:order, :product, :quantity, :sold_price_vat_excluded, :vat, :sold_price_vat_included, :total)");
        $query->bindParam('order', $order['id']);
        $query->bindParam('product', $product['id']);
        $query->bindParam('quantity', $quantity);
        $query->bindParam('sold_price_vat_excluded', $price);
        $query->bindParam('vat', $vat);
        $query->bindParam('sold_price_vat_included', $sold_price_vat_included);
        $query->bindParam('total', $total);
        $query->execute();
    }
}
