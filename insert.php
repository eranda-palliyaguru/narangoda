<?php
function insert($table, $values)
{


    include('connect.php');

    $sql = "INSERT INTO $table (product_id,name,invoice_no,type,balance,qty,date) VALUES (?,?,?,?,?,?,?)";
    $ql = $db->prepare($sql);
    $ql->execute(array($p_id, $name, $invo, 'out', $qty_blc, $qty, $date));
}
