<?php
session_start();
include('connect.php');

$u = $_SESSION['SESS_MEMBER_ID'];

$invo = $_POST['id'];
$type = $_POST['type'];
$qty = $_POST['qty'];
$cost = $_POST['cost'];

$dic = 0;
$sell = 0;
$stock = 0;
if ($type == 'GRN') {
    $dic = $_POST['dic'];
    $sell = $_POST['sell'];
}
if ($type == 'Return') {
    $stock = $_POST['stock'];
} else {
    $pro = $_POST['pr'];
}

if ($dic == '') {
    $dic = 0;
}


if ($type == 'Return') {

    $result = $db->prepare("SELECT * FROM stock WHERE id=:id ");
    $result->bindParam(':id', $stock);
    $result->execute();
    for ($i = 0; $row = $result->fetch(); $i++) {
        $pro = $row['product_id'];
        $pro_name = $row['name'];
    }
} else {

    $result = $db->prepare("SELECT * FROM products WHERE product_id=:id ");
    $result->bindParam(':id', $pro);
    $result->execute();
    for ($i = 0; $row = $result->fetch(); $i++) {
        $pro_name = $row['gen_name'];
    }
}

$amount = $cost * $qty;
$dic = $amount * $dic / 100;
$date = date("Y-m-d");


if ($invo != '') {
    $sql = "INSERT INTO purchases_item (invoice,name,qty,amount,date,product_id,cost,sell,discount,type,user_id,stock_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
    $re = $db->prepare($sql);
    $re->execute(array($invo, $pro_name, $qty, $amount, $date, $pro, $cost, $sell, $dic, $type, $u, $stock));
}

if ($type == 'GRN') {
    header("location: grn.php?id=$invo");
}

if ($type == 'Return') {
    header("location: grn_return.php?id=$invo");
}

if ($type == 'Order') {
    header("location: grn_order.php?id=$invo");
}
