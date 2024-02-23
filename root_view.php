<!DOCTYPE html>
<html>
<?php
include("head.php");
include("connect.php");
?>

<body class="hold-transition skin-yellow sidebar-mini">
    <?php
    include_once("auth.php");
    $r = $_SESSION['SESS_LAST_NAME'];
    $_SESSION['SESS_FORM'] = 'root';

    if ($r == 'Cashier') {

        header("location:./../../../index.php");
    }
    if ($r == 'admin') {

        include_once("sidebar.php");
    }
    ?>

    <style>
        .d-none {
            display: none !important;
        }

        .mx-2 {
            margin-left: 10px;
            margin-right: 10px;
        }

        .w-100 {
            width: 100%;
        }

        .fa-remove {
            font-size: 20px !important;
            padding: 0;
        }

        .container-up {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            width: 100%;
            background-color: rgb(0, 0, 0, 0.3);
        }

        .container-up .container-close {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .container-up .box {
            padding: 7px 15px;
            width: max-content;
            min-width: 500px;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>



    <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Root Details
                <small>Preview</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Forms</a></li>
                <li class="active">Root</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-success">
                <div class="box-header">
                    <h3 class="box-title">
                        Root Customer

                        <small class="btn btn-info mx-2" style="padding: 5px 10px;" title="Add Customer" onclick="click_new('1')">Add Customer</small>
                    </h3>

                </div>
                <!-- /.box-header -->

                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">

                        <thead>
                            <tr>
                                <th>ID.</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>contact</th>
                                <th>#</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php

                            $result = $db->prepare("SELECT * FROM customer  WHERE root_id = :id ");
                            $result->bindParam(':id', $_GET['id']);
                            $result->execute();
                            for ($i = 0; $row = $result->fetch(); $i++) {
                            ?>
                                <tr class="record">
                                    <td><?php echo $row['customer_id']; ?></td>
                                    <td><?php echo $row['customer_name']; ?></td>
                                    <td><?php echo $row['address']; ?></td>
                                    <td><?php echo $row['contact']; ?></td>
                                    <td>
                                        <a href="#" id="<?php echo $row['customer_id']; ?>" class="delbutton" title="Click to Delete">
                                            <button class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                        </a>
                                    </td>
                                </tr>
                            <?php  } ?>

                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <?php
    include("dounbr.php");
    ?>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>

    <div class="container-up d-none" id="container_up">
        <div class="container-close" onclick="click_cl()"></div>

        <div class="box box-success popup d-none" id="popup_1">
            <div class="box-header with-border">
                <h3 class="box-title w-100">New Customer <i onclick="click_cl()" class="btn p-0 me-2  pull-right fa fa-remove" style="font-size: 25px;"></i></h3>
            </div>

            <div class="box-body">
                <form method="POST" action="root_customer_save.php" class="w-100">
                    <div class="row">

                        <div class="col-md-9">
                            <div class="form-group">
                                <label>Customer</label>
                                <select class="form-control select2 w-100" name="id">
                                    <?php
                                    $result = $db->prepare("SELECT * FROM customer  ");
                                    $result->bindParam(':userid', $res);
                                    $result->execute();
                                    for ($i = 0; $row = $result->fetch(); $i++) {
                                    ?>
                                        <option value="<?php echo $row['customer_id']; ?>"><?php echo $row['customer_name']; ?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3" style="height: 75px;display: flex;align-items: end;justify-content: center;">
                            <div class="form-group">
                                <input class="btn btn-info" type="submit" value="Add">
                                <input type="hidden" name="root" value="<?php echo $_GET['id']; ?>">
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>

    </div>

    </div>
    <!-- ./wrapper -->
    <script src="js/jquery.js"></script>
    <!-- jQuery 2.2.3 -->
    <script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="../../bootstrap/js/bootstrap.min.js"></script>
    <!-- Select2 -->
    <script src="../../plugins/select2/select2.full.min.js"></script>
    <!-- DataTables -->
    <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="../../plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="../../plugins/fastclick/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="../../dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../../dist/js/demo.js"></script>
    <!-- page script -->
    <script>
        function click_new(i) {
            if (i == '1') {
                $("#popup_" + i).removeClass("d-none");
            } else {
                $("#popup_" + i).addClass("d-none");
            }

            $("#container_up").removeClass("d-none");
        }

        function click_cl() {
            $("#container_up").addClass("d-none");
        }
        $(function() {
            //Initialize Select2 Elements
            $(".select2").select2();

            $("#example1").DataTable({
                "language": {
                    "paginate": {
                        "next": "<i class='fa fa-angle-double-right'></i>",
                        "previous": "<i class='fa fa-angle-double-left'></i>"
                    }
                }
            });
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false
            });
        });
    </script>
    <script type="text/javascript">
        $(function() {


            $(".delbutton").click(function() {

                //Save the link in a variable called element
                var element = $(this);

                //Find the id of the link that was clicked
                var del_id = element.attr("id");

                //Built a url to send
                var info = 'id=' + del_id;
                if (confirm("Sure you want to delete this Root? There is NO undo!")) {

                    $.ajax({
                        type: "GET",
                        url: "root_customer_dll.php",
                        data: info,
                        success: function(res) {
                            console.log(res);
                        }
                    });
                    $(this).parents(".record").animate({
                            backgroundColor: "#fbc7c7"
                        }, "fast")
                        .animate({
                            opacity: "hide"
                        }, "slow");

                }

                return false;

            });

        });
    </script>
</body>

</html>