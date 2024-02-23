<?php
session_start();
include('connect.php');

$ui = $_SESSION['SESS_MEMBER_ID'];
$un = $_SESSION['SESS_FIRST_NAME'];

$type = $_POST['type'];

$date = date("Y-m-d");
$time = date('H:i:s');

if ($type == 'deposit') {

    $amount = $_POST['amount'];
    $bank = $_POST['bank'];

    $mn_blc = 0;
    $b_blc = 0;
    $re = $db->prepare("SELECT * FROM bank_balance WHERE id=:id ");
    $re->bindParam(':id', $bank);
    $re->execute();
    for ($k = 0; $r = $re->fetch(); $k++) {
        $b_blc = $r['amount'];
        $de_name = $r['name'];
        $acc_no = $r['dep_id'];
    }

    $cr_blc = 0;
    $blc = 0;
    $re = $db->prepare("SELECT * FROM cash WHERE id=:id ");
    $re->bindParam(':id', $acc_no);
    $re->execute();
    for ($k = 0; $r = $re->fetch(); $k++) {
        $blc = $r['amount'];
        $cr_name = $r['name'];
    }

    $cr_blc = $blc - $amount;

    $mn_blc = $b_blc + $amount;

    $sql = "UPDATE  cash SET amount=? WHERE id=?";
    $ql = $db->prepare($sql);
    $ql->execute(array($cr_blc, $acc_no));

    $sql = "INSERT INTO transaction_record (transaction_type,type,record_no,amount,action,credit_acc_no,credit_acc_type,credit_acc_name,credit_acc_balance,debit_acc_type,debit_acc_name,debit_acc_id,debit_acc_balance,date,time,user_id,user_name) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $ql = $db->prepare($sql);
    $ql->execute(array('bank_transfer', 'Debit', $bank, $amount, 0, $acc_no, 'Cash', $cr_name, $cr_blc, 'cash_transfer', $de_name, $bank, $mn_blc, $date, $time, $ui, $un));

    $sql = "UPDATE  bank_balance SET amount=? WHERE id=?";
    $ql = $db->prepare($sql);
    $ql->execute(array($mn_blc, $bank));

    $sql = "INSERT INTO bank_record (transaction_type,type,record_no,amount,action,credit_acc_no,credit_acc_type,credit_acc_name,credit_acc_balance,debit_acc_type,debit_acc_name,debit_acc_id,debit_acc_balance,date,time,user_id,user_name) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $ql = $db->prepare($sql);
    $ql->execute(array('cash_deposit', 'Credit', $acc_no, $amount, 0, $bank, 'cash_transfer', $de_name, $mn_blc, 'Cash', $cr_name, $acc_no, $cr_blc, $date, $time, $ui, $un));

    header("location: acc_bank_transfer.php");
} else

if ($type == 'chq') {

    $id = $_POST['id'];
    $bank = $_POST['bank'];
    $b_blc = 0;

    $re = $db->prepare("SELECT * FROM bank_balance WHERE id =:id ");
    $re->bindParam(':id', $bank);
    $re->execute();
    for ($k = 0; $r = $re->fetch(); $k++) {
        $b_blc = $r['amount'];
    }

    $re = $db->prepare("SELECT * FROM payment WHERE transaction_id = :id ");
    $re->bindParam(':id', $id);
    $re->execute();
    for ($k = 0; $r = $re->fetch(); $k++) {
        $amount = $r['amount'];
        $chq_no = $r['chq_no'];
        $chq_date = $r['chq_date'];
        $chq_bank = $r['chq_bank'];
    }

    $sql = "UPDATE  bank_balance SET amount = amount + ? WHERE id=?";
    $ql = $db->prepare($sql);
    $ql->execute(array($amount, $bank));

    $sql = "INSERT INTO bank_record (transaction_type,type,record_no,amount,action,credit_acc_no,credit_acc_type,credit_acc_name,credit_acc_balance,debit_acc_type,debit_acc_name,debit_acc_id,debit_acc_balance,date,time,user_id,user_name,chq_no,chq_bank,chq_date) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $ql = $db->prepare($sql);
    $ql->execute(array('chq_deposit', 'Credit', $id, $amount, 1, $id, 'Cash Transfer', 'Chq', 0, 'bank_deposit', 'Bank Account', $bank, $b_blc, $date, $time, $ui, $un, $chq_no, $chq_bank, $chq_date));

    $sql = "UPDATE  payment SET action=?, reserve_date = ? WHERE transaction_id=?";
    $ql = $db->prepare($sql);
    $ql->execute(array(1, $date, $id));

    echo $id;
}

if ($type == 'dep_realize') {

    $id = $_POST['id'];

    $re = $db->prepare("SELECT * FROM payment WHERE transaction_id = :id ");
    $re->bindParam(':id', $id);
    $re->execute();
    for ($k = 0; $r = $re->fetch(); $k++) {
        $chq_no = $r['chq_no'];
        $amount = $r['amount'];
    }

    $re = $db->prepare("SELECT * FROM bank_record WHERE chq_no = :id ");
    $re->bindParam(':id', $chq_no);
    $re->execute();
    for ($k = 0; $r = $re->fetch(); $k++) {
        $bank = $r['debit_acc_id'];
    }

    $sql = "UPDATE  payment SET action=?, reserve_date = ? WHERE transaction_id=?";
    $ql = $db->prepare($sql);
    $ql->execute(array(2, $date, $id));

    $mn_blc = 0;
    $b_blc = 0;
    $re = $db->prepare("SELECT * FROM bank_balance WHERE id =:id ");
    $re->bindParam(':id', $bank);
    $re->execute();
    for ($k = 0; $r = $re->fetch(); $k++) {
        $b_blc = $r['amount'];
    }

    $mn_blc = $b_blc + $amount;

    $sql = "UPDATE  bank_balance SET amount=? WHERE id=?";
    $ql = $db->prepare($sql);
    $ql->execute(array($mn_blc, $bank));

    $sql = "UPDATE  bank_record SET action=? WHERE chq_no=?";
    $ql = $db->prepare($sql);
    $ql->execute(array(2, $chq_no));


    echo $id;
}

if ($type == 'iss_realize') {

    $id = $_POST['id'];
    $unit = $_POST['unit'];

    if ($unit == 'grn') {

        $re = $db->prepare("SELECT * FROM supply_payment WHERE id = :id ");
        $re->bindParam(':id', $id);
        $re->execute();
        for ($k = 0; $r = $re->fetch(); $k++) {
            $chq_no = $r['chq_no'];
            $chq_date = $r['chq_date'];
            $amount = $r['amount'];
            $bank = $r['bank_id'];
            $chq_bank = $r['chq_bank'];
        }

        $cr_name = 'GRN';

        $sql = "UPDATE  supply_payment SET action = ?, reserve_date = ? WHERE id=?";
        $ql = $db->prepare($sql);
        $ql->execute(array(2, $date, $id));

        $cr_type = 'grn_payment';
    } else

    if ($unit == 'exp') {

        $re = $db->prepare("SELECT * FROM payment WHERE transaction_id = :id ");
        $re->bindParam(':id', $id);
        $re->execute();
        for ($k = 0; $r = $re->fetch(); $k++) {
            $chq_no = $r['chq_no'];
            $chq_date = $r['chq_date'];
            $amount = $r['amount'];
            $bank = $r['bank_id'];
            $chq_bank = $r['chq_bank'];
        }

        $re = $db->prepare("SELECT * FROM expenses_records WHERE acc_id = '$bank' AND acc_name = '$chq_bank' ");
        $re->bindParam(':id', $id);
        $re->execute();
        for ($k = 0; $r = $re->fetch(); $k++) {
            $cr_name = $r['type'];
        }

        $sql = "UPDATE  payment SET action = ?, reserve_date = ? WHERE transaction_id=?";
        $ql = $db->prepare($sql);
        $ql->execute(array(2, $date, $id));

        $cr_type = 'expenses_payment';
    }

    $mn_blc = 0;
    $b_blc = 0;
    $re = $db->prepare("SELECT * FROM bank_balance WHERE id =:id ");
    $re->bindParam(':id', $bank);
    $re->execute();
    for ($k = 0; $r = $re->fetch(); $k++) {
        $b_blc = $r['amount'];
        $cr_name = $r['name'];
    }

    $mn_blc = $b_blc - $amount;

    $sql = "UPDATE  bank_balance SET amount=? WHERE id=?";
    $ql = $db->prepare($sql);
    $ql->execute(array($mn_blc, $bank));

    $sql = "INSERT INTO bank_record (transaction_type,type,record_no,amount,action,credit_acc_no,credit_acc_type,credit_acc_name,credit_acc_balance,debit_acc_type,debit_acc_name,debit_acc_id,debit_acc_balance,date,time,user_id,user_name,chq_no,chq_bank,chq_date) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $ql = $db->prepare($sql);
    $ql->execute(array('chq_issue', 'Debit', $id, $amount, 2, $id, $cr_type, $cr_name, 0, 'chq_issue', $chq_bank, $bank, $mn_blc, $date, $time, $ui, $un, $chq_no, $chq_bank, $chq_date));

    echo $id;
}

if ($type == 'dep_return') {

    $id = $_POST['id'];

    $re = $db->prepare("SELECT * FROM payment WHERE transaction_id = :id ");
    $re->bindParam(':id', $id);
    $re->execute();
    for ($k = 0; $r = $re->fetch(); $k++) {
        $chq_no = $r['chq_no'];
    }

    $sql = "UPDATE  payment SET action=?, reserve_date = ? WHERE transaction_id=?";
    $ql = $db->prepare($sql);
    $ql->execute(array(3, $date, $id));

    $sql = "UPDATE  bank_record SET action=? WHERE chq_no=?";
    $ql = $db->prepare($sql);
    $ql->execute(array(3, $chq_no));

    echo $id;
}

if ($type == 'iss_return') {

    $id = $_POST['id'];
    $unit = $_POST['unit'];

    if ($unit == 'grn') {

        $re = $db->prepare("SELECT * FROM supply_payment WHERE id = :id ");
        $re->bindParam(':id', $id);
        $re->execute();
        for ($k = 0; $r = $re->fetch(); $k++) {
            $chq_no = $r['chq_no'];
        }

        $sql = "UPDATE  supply_payment SET action=?, reserve_date = ? WHERE id=?";
        $ql = $db->prepare($sql);
        $ql->execute(array(3, $date, $id));

        $sql = "UPDATE  bank_record SET action=? WHERE chq_no=?";
        $ql = $db->prepare($sql);
        $ql->execute(array(3, $chq_no));
    }

    if ($unit == 'exp') {

        $re = $db->prepare("SELECT * FROM payment WHERE transaction_id = :id ");
        $re->bindParam(':id', $id);
        $re->execute();
        for ($k = 0; $r = $re->fetch(); $k++) {
            $chq_no = $r['chq_no'];
        }

        $sql = "UPDATE  payment SET action=?, reserve_date = ? WHERE transaction_id=?";
        $ql = $db->prepare($sql);
        $ql->execute(array(3, $date, $id));

        $sql = "UPDATE  bank_record SET action=? WHERE chq_no=?";
        $ql = $db->prepare($sql);
        $ql->execute(array(3, $chq_no));
    }

    echo $id;
}

if ($type == 'withdraw') {

    $bank = $_POST['bank'];
    $amount = $_POST['amount'];

    $cr_blc = 0;
    $b_blc = 0;
    $re = $db->prepare("SELECT * FROM bank_balance WHERE id =:id ");
    $re->bindParam(':id', $bank);
    $re->execute();
    for ($k = 0; $r = $re->fetch(); $k++) {
        $b_blc = $r['amount'];
        $cr_name = $r['name'];
        $acc_no = $r['dep_id'];
    }

    $cr_blc = $b_blc - $amount;

    $mn_blc = 0;
    $blc = 0;
    $re = $db->prepare("SELECT * FROM cash WHERE id=:id ");
    $re->bindParam(':id', $acc_no);
    $re->execute();
    for ($k = 0; $r = $re->fetch(); $k++) {
        $blc = $r['amount'];
        $de_name = $r['name'];
    }

    $mn_blc = $blc + $amount;

    $sql = "UPDATE  cash SET amount=? WHERE id=?";
    $ql = $db->prepare($sql);
    $ql->execute(array($mn_blc, $acc_no));

    $sql = "INSERT INTO transaction_record (transaction_type,type,record_no,amount,action,credit_acc_no,credit_acc_type,credit_acc_name,credit_acc_balance,debit_acc_type,debit_acc_name,debit_acc_id,debit_acc_balance,date,time,user_id,user_name) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $ql = $db->prepare($sql);
    $ql->execute(array('bank_transfer', 'Credit', $bank, $amount, 0, $bank, 'Cash', $cr_name, $cr_blc, 'cash_withdraw', $de_name, $acc_no, $mn_blc, $date, $time, $ui, $un));

    $sql = "UPDATE  bank_balance SET amount=? WHERE id=?";
    $ql = $db->prepare($sql);
    $ql->execute(array($cr_blc, $bank));

    $sql = "INSERT INTO bank_record (transaction_type,type,record_no,amount,action,credit_acc_no,credit_acc_type,credit_acc_name,credit_acc_balance,debit_acc_type,debit_acc_name,debit_acc_id,debit_acc_balance,date,time,user_id,user_name) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $ql = $db->prepare($sql);
    $ql->execute(array('cash_withdraw', 'Debit', $acc_no, $amount, 0, $acc_no, 'Cash', $de_name, $mn_blc, 'bank_withdraw', $cr_name, $bank, $cr_blc, $date, $time, $ui, $un));


    header("location: acc_bank_transfer.php");
}

if ($type == 'chargers') {

    $bank = $_POST['bank'];
    $desc = $_POST['desc'];
    $chr_date = $_POST['date'];
    $amount = $_POST['amount'];


    $bn_blc = 0;
    $blc = 0;
    $re = $db->prepare("SELECT * FROM bank_balance WHERE id =:id ");
    $re->bindParam(':id', $bank);
    $re->execute();
    for ($k = 0; $r = $re->fetch(); $k++) {
        $blc = $r['amount'];
        $bn_name = $r['name'];
    }

    $bn_blc = $blc - $amount;

    $sql = "UPDATE  bank_balance SET amount=? WHERE id=?";
    $ql = $db->prepare($sql);
    $ql->execute(array($bn_blc, $bank));

    $acc_no = 0;
    $re = $db->prepare("SELECT count(id) FROM bank_record WHERE transaction_type = 'bank_charges' ");
    $re->bindParam(':id', $bank);
    $re->execute();
    for ($k = 0; $r = $re->fetch(); $k++) {
        $acc_no = $r['count(id)'];
    }
    $acc_no = $acc_no + 1;

    $sql = "INSERT INTO bank_record (transaction_type,type,record_no,amount,action,credit_acc_no,credit_acc_type,credit_acc_name,credit_acc_balance,debit_acc_type,debit_acc_name,debit_acc_id,debit_acc_balance,date,time,user_id,user_name) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $ql = $db->prepare($sql);
    $ql->execute(array('bank_charges', 'Debit', $chr_date, $amount, 0, $acc_no, 'Cash', 'Bank Charges', 0, 'bank_charges', $bn_name, $bank, $bn_blc, $date, $time, $ui, $un));


    header("location: acc_bank_transfer.php");
}
