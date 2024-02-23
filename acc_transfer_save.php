<?php
session_start();
include('connect.php');

$user_id = $_SESSION['SESS_MEMBER_ID'];
$user_name = $_SESSION['SESS_FIRST_NAME'];

$type = $_POST['type'];
$from = $_POST['acc_from'];
$to = $_POST['acc_to'];
$type = $_POST['type'];
$amount = $_POST['amount'];

$date = date("Y-m-d");
$time = date('H:i:s');



$cr_type = 'cash';
$de_type = 'cash_transfer';

$cr_blc = 0;
$blc = 0;
$re = $db->prepare("SELECT * FROM cash WHERE id =:id ");
$re->bindParam(':id', $from);
$re->execute();
for ($k = 0; $r = $re->fetch(); $k++) {
    $blc = $r['amount'];
    $cr_name = $r['name'];
}

$cr_blc = $blc - $amount;

$de_blc = 0;
$blc = 0;
$re = $db->prepare("SELECT * FROM cash WHERE id =:id ");
$re->bindParam(':id', $to);
$re->execute();
for ($k = 0; $r = $re->fetch(); $k++) {
    $blc = $r['amount'];
    $de_name = $r['name'];
}

$de_blc = $blc + $amount;

$sql = "UPDATE  cash SET amount=? WHERE id=?";
$ql = $db->prepare($sql);
$ql->execute(array($de_blc, $to));

$sql = "INSERT INTO transaction_record (transaction_type,type,record_no,amount,action,credit_acc_no,credit_acc_type,credit_acc_name,credit_acc_balance,debit_acc_type,debit_acc_name,debit_acc_id,debit_acc_balance,date,time,user_id,user_name) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
$ql = $db->prepare($sql);
$ql->execute(array('acc_transfer', 'Debit', $from, $amount, 0, $from, $cr_type, $cr_name, $cr_blc, $de_type, $de_name, $to, $de_blc, $date, $time, $user_id, $user_name));

$sql = "UPDATE  cash SET amount=? WHERE id=?";
$ql = $db->prepare($sql);
$ql->execute(array($cr_blc, $from));

$sql = "INSERT INTO transaction_record (transaction_type,type,record_no,amount,action,credit_acc_no,credit_acc_type,credit_acc_name,credit_acc_balance,debit_acc_type,debit_acc_name,debit_acc_id,debit_acc_balance,date,time,user_id,user_name) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
$ql = $db->prepare($sql);
$ql->execute(array('acc_transfer', 'Credit', $to, $amount, 0, $to, $de_type, $de_name, $de_blc, $cr_type, $cr_name, $from, $cr_blc, $date, $time, $user_id, $user_name));


header("location: acc_transfer.php");
