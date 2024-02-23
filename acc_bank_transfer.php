<!DOCTYPE html>
<html>
<?php
include("head.php");
include("connect.php");
?>


<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>


<body class="hold-transition skin-yellow sidebar-mini">
    <?php
    include_once("auth.php");
    $r = $_SESSION['SESS_LAST_NAME'];
    $_SESSION['SESS_FORM'] = 'acc_bank_transfer';
    if ($r == 'Cashier') {
        include_once("sidebar2.php");
    }
    if ($r == 'admin') {
        include_once("sidebar.php");
    }

    ?>
    <!-- /.sidebar -->
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                BANK TRANSFER
                <small>Preview</small>
            </h1>

        </section>
        <!-- Main content -->
        <section class="content">
            <!-- SELECT2 EXAMPLE -->
            <div class="row">
                <div class="col-md-6">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Transfer Select</h3>
                                    <!-- /.box-header -->
                                </div>
                                <div class="form-group">
                                    <div class="box-body d-block">
                                        <div class="row">

                                            <div class="col-md-1"></div>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <label>Bank Accounts</label>
                                                        </div>

                                                        <select class="form-control " name="bank" id="b_sel" onchange="bank_select()" style="width: 100%;" tabindex="1" autofocus>

                                                            <?php
                                                            $result = $db->prepare("SELECT * FROM bank_balance ");
                                                            $result->bindParam(':userid', $res);
                                                            $result->execute();
                                                            for ($i = 0; $row = $result->fetch(); $i++) { ?>
                                                                <option value="<?php echo $row['id']; ?>">
                                                                    <?php echo $row['name']; ?> -<?php echo $row['ac_no']; ?>
                                                                </option>
                                                            <?php    } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-9" style="display: none;" id="err">
                                                <h4 class="text-danger " style="margin-left: 50px; text-align: center;">Select transfer method</h4>
                                            </div>

                                            <div class="col-md-10 mt-2" style="display:flex; justify-content: center; margin: 25px 0 0 20px; ">
                                                <div class="form-group ">
                                                    <span class="btn btn-primary btn-lg m-0 me-2 acc" id="cash" onclick="acc_type('cash')">Cash Deposit</span>
                                                    <span class="btn btn-warning btn-lg m-0 ms-2 acc" id="chq" onclick="acc_type('chq')">Chq Deposit</span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12" id="cash_box" style="display: none;">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Cash Deposit</h3>
                                    <!-- /.box-header -->
                                </div>
                                <div class="form-group">
                                    <div class="box-body d-block">
                                        <form method="POST" action="acc_bank_transfer_save.php">
                                            <div class="row">

                                                <div class="col-md-10">
                                                    <?php $style = '';
                                                    $result = $db->prepare("SELECT * FROM cash ");
                                                    $result->bindParam(':id', $res);
                                                    $result->execute();
                                                    for ($i = 0; $row = $result->fetch(); $i++) {
                                                        $id = $row['id'];
                                                        if ($id != 1) {
                                                            $style = 'style="display: none;"';
                                                        } ?>
                                                        <div class="form-group dep-cont" id="de_acc_<?php echo $id; ?>" <?php echo $style; ?>>
                                                            <h4 style="text-align: center;"> <?php echo $row['name']; ?> : <small>Rs.</small> <?php echo $row['amount']; ?> </h4>
                                                            <input type="hidden" id="de_blc_<?php echo $id; ?>" value="<?php echo $row['amount']; ?>">
                                                        </div>

                                                    <?php } ?>
                                                </div>

                                                <div class="col-md-1"></div>
                                                <div class="col-md-6">
                                                    <div class="form-group ">
                                                        <label>Transfer Amount</label>
                                                        <input class="form-control" type="number" name="amount" id="dep_txt" onkeyup="check_deposit()" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-5" style="display:flex; justify-content: center; margin-top: 20px;">
                                                    <input type="hidden" name="bank" id="txt_bank" value="">
                                                    <input type="hidden" name="type" id="txt_acc" value="deposit">
                                                    <input class="btn btn-danger" type="submit" id="btn_de" value="Deposit" disabled>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12" id="chq_box" style="display: none;">
                            <div class="box box-warning">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Chq Deposit</h3>
                                    <!-- /.box-header -->
                                </div>
                                <div class="form-group">
                                    <div class="box-body d-block">
                                        <table id="example2" class="table table-bordered table-hover" style="border-radius: 0;">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Chq No</th>
                                                    <th>Chq Bank</th>
                                                    <th>Chq Date</th>
                                                    <th>Amount (Rs.)</th>
                                                    <th>#</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $total = 0;
                                                $style = "";
                                                $result = $db->prepare("SELECT * FROM payment WHERE action=0 AND pay_type='Chq' ");
                                                $result->bindParam(':userid', $res);
                                                $result->execute();
                                                for ($i = 0; $row = $result->fetch(); $i++) {
                                                ?>
                                                    <tr id="re_<?php echo $row['transaction_id']; ?>">
                                                        <td><?php echo $row['transaction_id']; ?></td>
                                                        <td><?php echo $row['chq_no']; ?></td>
                                                        <td><?php echo $row['chq_bank']; ?></td>
                                                        <td><?php echo $row['chq_date']; ?></td>
                                                        <td><?php echo $row['amount']; ?></td>
                                                        <td> <span id="<?php echo $row['transaction_id']; ?>" class="deposit_btn btn btn-success" title="Click to Deposit"> <i class="fa-solid fa-money-bill-transfer"></i></span></td>
                                                        <?php $total += $row['amount']; ?>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>

                                        </table>
                                        <h4>Total Rs <b><?php echo number_format($total, 2); ?></h4>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Withdraw Select</h3>
                                    <!-- /.box-header -->
                                </div>
                                <div class="form-group">
                                    <div class="box-body d-block">
                                        <form method="POST" action="acc_bank_transfer_save.php">
                                            <div class="row">

                                                <div class="col-md-1"></div>
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <label>Bank Accounts</label>
                                                            </div>

                                                            <select class="form-control " name="bank" id="bank_sel1" onchange="bank_withdraw()" style="width: 100%;" tabindex="1" autofocus>

                                                                <?php
                                                                $result = $db->prepare("SELECT * FROM bank_balance ");
                                                                $result->bindParam(':userid', $res);
                                                                $result->execute();
                                                                for ($i = 0; $row = $result->fetch(); $i++) { ?>
                                                                    <option value="<?php echo $row['id']; ?>">
                                                                        <?php echo $row['name']; ?> -<?php echo $row['ac_no']; ?>
                                                                    </option>
                                                                <?php    } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <?php $style = '';
                                                    $result = $db->prepare("SELECT * FROM bank_balance ");
                                                    $result->bindParam(':id', $res);
                                                    $result->execute();
                                                    for ($i = 0; $row = $result->fetch(); $i++) {
                                                        $id = $row['id'];
                                                        if ($id != 1) {
                                                            $style = 'style="display: none;"';
                                                        } ?>
                                                        <div class="form-group acc-cont" id="acc_<?php echo $id; ?>" <?php echo $style; ?>>
                                                            <h5 style="text-align: center;margin-top: 0;">Account Bal: <small>Rs.</small> <?php echo $row['amount']; ?> </h5>
                                                            <input type="hidden" id="blc_<?php echo $id; ?>" value="<?php echo $row['amount']; ?>">
                                                            <input type="hidden" id="dep_<?php echo $id; ?>" value="<?php echo $row['dep_id']; ?>">
                                                        </div>

                                                    <?php } ?>
                                                </div>

                                                <div class="col-md-1"></div>
                                                <div class="col-md-6">
                                                    <div class="form-group ">
                                                        <label>Transfer Amount</label>
                                                        <input class="form-control" type="number" name="amount" id="with_txt" onkeyup="check_withdraw()" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-5" style="display:flex; justify-content: center; margin-top: 20px;">
                                                    <input type="hidden" name="type" value="withdraw">
                                                    <input class="btn btn-danger" type="submit" id="btn_wi" value="Withdraw" disabled>
                                                </div>

                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Bank Charges</h3>
                                    <!-- /.box-header -->
                                </div>
                                <div class="form-group">
                                    <div class="box-body d-block">
                                        <form method="POST" action="acc_bank_transfer_save.php">
                                            <div class="row">

                                                <div class="col-md-1"></div>
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <label>Bank Accounts</label>
                                                            </div>

                                                            <select class="form-control " name="bank" id="bank_sel2" onchange="bank_charge()" style="width: 100%;" tabindex="1" autofocus>

                                                                <?php
                                                                $result = $db->prepare("SELECT * FROM bank_balance ");
                                                                $result->bindParam(':userid', $res);
                                                                $result->execute();
                                                                for ($i = 0; $row = $result->fetch(); $i++) { ?>
                                                                    <option value="<?php echo $row['id']; ?>" balance="<?php echo $row['amount']; ?>">
                                                                        <?php echo $row['name']; ?> -<?php echo $row['ac_no']; ?>
                                                                    </option>
                                                                <?php    } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <?php $style = '';
                                                    $result = $db->prepare("SELECT * FROM bank_balance ");
                                                    $result->bindParam(':id', $res);
                                                    $result->execute();
                                                    for ($i = 0; $row = $result->fetch(); $i++) {
                                                        $id = $row['id'];
                                                        if ($id != 1) {
                                                            $style = 'style="display: none;"';
                                                        } ?>
                                                        <div class="form-group acc-cont1" id="acc_1<?php echo $id; ?>" <?php echo $style; ?>>
                                                            <h5 style="text-align: center;margin-top: 0;">Account Bal: <small>Rs.</small> <?php echo $row['amount']; ?> </h5>
                                                            <input type="hidden" id="blc_1<?php echo $id; ?>" value="<?php echo $row['amount']; ?>">
                                                        </div>

                                                    <?php } ?>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group ">
                                                        <label>Description</label>
                                                        <input class="form-control" type="text" name="desc" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group ">
                                                        <label>Date</label>
                                                        <input class="form-control" id="datepicker" type="text" name="date" value="<?php echo date('Y-m-d'); ?>" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group ">
                                                        <label>Amount</label>
                                                        <input class="form-control" type="number" step=".01" name="amount" id="char_txt" onkeyup="check_charge()" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-6" style="margin-top: 20px;">
                                                    <input type="hidden" name="type" value="chargers">
                                                    <input class="btn btn-info" type="submit" id="btn_ch" value="Save" disabled>
                                                </div>

                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

    </div>
    <!-- /.content-wrapper -->
    <?php include("dounbr.php"); ?>

    <div class="control-sidebar-bg"></div>
    </div>

    <!-- jQuery 2.2.3 -->
    <script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="../../bootstrap/js/bootstrap.min.js"></script>
    <!-- Select2 -->
    <script src="../../plugins/select2/select2.full.min.js"></script>
    <!-- DataTables -->
    <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- date-range-picker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="../../plugins/daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap datepicker -->
    <script src="../../plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- SlimScroll 1.3.0 -->
    <script src="../../plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- iCheck 1.0.1 -->
    <script src="../../plugins/iCheck/icheck.min.js"></script>
    <!-- FastClick -->
    <script src="../../plugins/fastclick/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="../../dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../../dist/js/demo.js"></script>
    <!-- Dark Theme Btn-->
    <script src="https://dev.colorbiz.org/ashen/cdn/main/dist/js/DarkTheme.js"></script>



    <script type="text/javascript">
        function bank_charge() {
            let bank = $("#bank_sel2").val();

            $('.acc-cont1').css('display', 'none');
            $('#acc_1' + bank).css('display', 'block');
        }

        function check_charge() {
            let txt = $("#char_txt").val();
            let bank = $("#bank_sel2").val();
            let blc = parseInt($("#blc_1" + bank).val());

            if (0 >= txt || blc < txt) {
                $('#btn_ch').attr("disabled", "");
            } else {
                $('#btn_ch').removeAttr('disabled');
            }
        }

        function bank_withdraw() {
            let bank = $("#bank_sel1").val();

            $('.acc-cont').css('display', 'none');
            $('#acc_' + bank).css('display', 'block');
        }

        function check_withdraw() {
            let txt = $("#with_txt").val();
            let bank = $("#bank_sel1").val();
            let blc = parseInt($("#blc_" + bank).val());

            if (0 >= txt || blc < txt) {
                $('#btn_wi').attr("disabled", "");
            } else {
                $('#btn_wi').removeAttr('disabled');
            }
        }

        function acc_type(type) {
            $(".acc").addClass("hover disabled");
            $("#" + type).removeClass("hover disabled");
            $('#err').css('display', 'none');

            $('#txt_bank').val($('#b_sel').val());

            if (type == 'cash') {
                $('#cash_box').css('display', 'block')
            } else {
                $('#cash_box').css('display', 'none')
            }
            if (type == 'chq') {
                $('#chq_box').css('display', 'block')
            } else {
                $('#chq_box').css('display', 'none')
            }
        }

        function check_deposit() {
            let txt = $("#dep_txt").val();
            let bank = $("#b_sel").val();
            let dep = $("#dep_" + bank).val();
            let blc = parseInt($("#de_blc_" + dep).val());
            console.log(blc);

            if (0 >= txt || blc < txt) {
                $('#btn_de').attr("disabled", "");
            } else {
                $('#btn_de').removeAttr('disabled');
            }

        }

        function bank_select() {
            $('#txt_bank').val($('#b_sel').val());

            let bank = $("#b_sel").val();
            let dep = $("#dep_" + bank).val();

            $('.dep-cont').css('display', 'none');
            $('#de_acc_' + dep).css('display', 'block');
        }

        $(".deposit_btn").click(function() {
            var element = $(this);
            var id = element.attr("id");
            var bank = $('#b_sel').val();
            var info = 'type=chq&id=' + id + '&bank=' + bank;

            $.ajax({
                type: "POST",
                url: "acc_bank_transfer_save.php",
                data: info,
                success: function(res) {
                    console.log(res);
                    $('#re_' + res).animate({
                            backgroundColor: "#fbc7c7"
                        }, "fast")
                        .animate({
                            opacity: "hide"
                        }, "slow");
                }
            });

        });

        $(function() {
            $("#example1").DataTable();
            $('#example2').DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": false,
                "autoWidth": false
            });
        });
    </script>

    <!-- Page script -->
    <script>
        $(function() {
            //Initialize Select2 Elements
            $(".select2").select2();

            //Date range picker
            $('#reservation').daterangepicker();
            //Date range picker with time picker
            //$('#datepicker').datepicker({datepicker: true,  format: 'yyyy/mm/dd '});
            //Date range as a button


            //Date picker
            $('#datepicker1').datepicker({
                autoclose: true,
                datepicker: true,
                format: 'yyyy-mm-dd '
            });
            $('#datepicker').datepicker({
                autoclose: true,
                datepicker: true,
                format: 'yyyy-mm-dd '
            });


            $('#datepickerd').datepicker({
                autoclose: true,
                datepicker: true,
                format: 'yyyy-mm-dd '
            });
            $('#datepickerd').datepicker({
                autoclose: true
            });
        });
    </script>

</body>

</html>