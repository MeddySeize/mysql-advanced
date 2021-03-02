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

$q = "SELECT * FROM user";

$users = $conn->query($q)->fetchAll(PDO::FETCH_ASSOC);

$startDate = (new DateTime('2011-01-01T15:03:01'))->getTimestamp();
$endDate = (new DateTime('2021-02-01T15:03:01'))->getTimestamp();

foreach ($users as $user) {
    $zeroCounter =0;
    $numOrders = mt_rand(0, 20);
    if ($numOrders === 0) continue;

    for ($i=0; $i<$numOrders; $i++) {
        $date = date('Y-m-d H:i:s', mt_rand($startDate, $endDate));

        $query = $conn->prepare("INSERT INTO `order` (date, user)
                    VALUES (:date, :user)");
        $query->bindParam('date', $date);
        $query->bindParam('user', $user['id']);
        $query->execute();
    }
}

echo 'order populated';

include_once 'order-line-population.php';