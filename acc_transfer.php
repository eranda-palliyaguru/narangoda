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
    $_SESSION['SESS_FORM'] = 'acc_transfer';
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
                FUND TRANSFER
                <small>Preview</small>
            </h1>

        </section>
        <!-- Main content -->
        <section class="content">
            <!-- SELECT2 EXAMPLE -->
            <div class="row">

                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Transfer Save</h3>
                            <!-- /.box-header -->
                        </div>
                        <div class="form-group">
                            <div class="box-body d-block">
                                <form method="POST" action="acc_transfer_save.php">
                                    <div class="row">

                                        <div class="col-md-8">

                                            <div class="col-md-12" id="fu_box" style=" margin-top:30px;">
                                                <form method="POST" action="acc_transfer_save.php" class="">
                                                    <div class="row">

                                                        <div class="col-md-5">
                                                            <div class="form-group ">
                                                                <label for="">Select Account</label>
                                                                <select class="form-control" name="acc_from" id="from" onchange="check()" style="width: 100%;" tabindex="1" autofocus>

                                                                    <?php
                                                                    $result = $db->prepare("SELECT * FROM cash ");
                                                                    $result->bindParam(':userid', $res);
                                                                    $result->execute();
                                                                    for ($i = 0; $row = $result->fetch(); $i++) {
                                                                        $id = $row['id']; ?>
                                                                        <option value="<?php echo $id; ?>" <?php if ($id == 1) { ?> selected <?php } ?>>
                                                                            <?php echo $row['name']; ?>
                                                                        </option>
                                                                    <?php    } ?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2 d-flex" style="font-size: 35px;margin-top: 25px;display: flex;justify-content: space-around;">
                                                            <i class="fa fa-angle-double-right " style="opacity: 0.25;"></i>
                                                            <i class="fa fa-angle-double-right px-1" style="opacity: 0.5;"></i>
                                                            <i class="fa fa-angle-double-right " style="opacity: 0.75;"></i>
                                                        </div>

                                                        <div class="col-md-5">
                                                            <div class="form-group ">
                                                                <label for="">Select Account</label>
                                                                <select class="form-control" name="acc_to" id="to" onchange="check()" style="width: 100%;" tabindex="1" autofocus>

                                                                    <?php
                                                                    $result = $db->prepare("SELECT * FROM cash ");
                                                                    $result->bindParam(':userid', $res);
                                                                    $result->execute();
                                                                    for ($i = 0; $row = $result->fetch(); $i++) {
                                                                        $id = $row['id']; ?>
                                                                        <option value="<?php echo $id; ?>" <?php if ($id == 2) { ?> selected <?php } ?>>
                                                                            <?php echo $row['name']; ?>
                                                                        </option>
                                                                    <?php    } ?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-11" style="display: none;" id="err">
                                                            <h5 class="text-danger mt-0" style="margin-left: 50px; text-align: center; font-weight:600;color:red !important;">Please select deference account</h5>
                                                        </div>

                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-5 abc" style="margin-top: 30px;">
                                                            <div class="form-group ">
                                                                <label>Transfer Amount</label>
                                                                <input class="form-control" type="number" name="amount" id="pay_txt" onkeyup="checking()" autocomplete="off">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-5 abc" style="display:flex; justify-content: center; margin-top: 50px;">
                                                            <input type="hidden" name="type" value="fund">
                                                            <input class="btn btn-danger" type="submit" id="btn_tr" value="Transfer" disabled style="padding: 8px 25px;">
                                                        </div>

                                                    </div>

                                                </form>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <?php
                                                $result = $db->prepare("SELECT * FROM cash ");
                                                $result->bindParam(':userid', $res);
                                                $result->execute();
                                                for ($i = 0; $row = $result->fetch(); $i++) {
                                                ?>

                                                    <div class="col-md-10" style="margin-left: 30px;">
                                                        <div class="small-box" style="border: 1px solid;padding: 15px 0; ">
                                                            <div class="inner">
                                                                <h3 style="font-size: 23px;position: relative;"><?php echo $row['amount']; ?></h3>
                                                                <input type="hidden" id="acc_<?php echo $id; ?>" value="<?php echo $row['amount']; ?>">
                                                                <p><?php echo $row['name']; ?></p>
                                                            </div>
                                                            <div class="icon" style="color: rgba(var(--bg-content-light),0.2);"><i class="fa fa-dollar"></i></div>
                                                        </div>
                                                    </div>

                                                <?php    } ?>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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
        function check() {
            let f = $("#from").val();
            let t = $("#to").val();

            if (f == t) {
                $('#err').css('display', 'block');
                $('.abc').css('display', 'none');
            } else {
                $('#err').css('display', 'none');
                $('.abc').css('display', 'flex');
            }
        }

        function checking() {
            let f = $("#from").val();
            let blc = parseInt($("#acc_" + f).val());
            let txt = $("#pay_txt").val();

            if (0 >= txt || txt > blc) {
                $('#btn_tr').attr("disabled", "");
            } else {
                $('#btn_tr').removeAttr('disabled');
            }

        }

        $(function() {
            $("#example1").DataTable();
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": true
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
                autoclose: true
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