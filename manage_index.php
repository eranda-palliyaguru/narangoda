<!DOCTYPE html>
<html>

<?php

include("head.php");
include("connect.php");
?>

<body class="hold-transition skin-yellow sidebar-mini">
<?php
include_once("auth.php");
$r=$_SESSION['SESS_LAST_NAME'];
$_SESSION['SESS_DEPARTMENT'] = 'management';

if($r =='lorry'){

header("location:sales_start.php");
}

include_once("sidebar.php");



?>


    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Home
        <small>Preview</small>
      </h1>

    </section>

    <!-- Main content -->
    <section class="content">



	<?php
		include('connect.php');
 date_default_timezone_set("Asia/Colombo");
 $cash=$_SESSION['SESS_FIRST_NAME'];

                  $date =  date("Y/m/d");

				$result = $db->prepare("SELECT sum(amount) FROM sales WHERE    date='$date' ");

					$result->bindParam(':userid', $date);
                $result->execute();
                for($i=0; $row = $result->fetch(); $i++){

				  $amount=$row['sum(amount)'];
				}




                $result = $db->prepare("SELECT sum(amount) FROM sales WHERE    date='$date' AND cashier='$cash' ");

					$result->bindParam(':userid', $date);
                $result->execute();
                for($i=0; $row = $result->fetch(); $i++){

				  $amount_cash=$row['sum(amount)'];
				}




				$result = $db->prepare("SELECT sum(amount) FROM sales WHERE    date='$date' AND cashier='Buddika' ");

					$result->bindParam(':userid', $date);
                $result->execute();
                for($i=0; $row = $result->fetch(); $i++){

				  $bamount_cash=$row['sum(amount)'];
				}

				$result = $db->prepare("SELECT * FROM customer  ");
				$result->bindParam(':userid', $date);
				$result->execute();
				$tre = $result->rowcount();


				date_default_timezone_set("Asia/Colombo");
				$date=date("Y-m-d");
				$month=date("Y-M");



			?>

    <div class="row">
       <div class="col-md-3">
          <!-- Info Boxes Style 2 -->
          <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="glyphicon glyphicon-signal"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Sales Value</span>
              <span class="info-box-number">24,587<?php // echo $date; ?></span>

              <div class="progress">
                <div class="progress-bar" style="width: 50%"></div>
              </div>
                  <span class="progress-description">
                    50% Increase in 30 Days
                  </span>
            </div>
           </div> 
            <!-- /.info-box-content -->
        </div>
        <div class="col-md-3">
          <!-- /.info-box -->
          <div class="info-box bg-green">
            <span class="info-box-icon"><i class="glyphicon glyphicon-user"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">New Customer</span>
              <span class="info-box-number">12</span>

              <div class="progress">
                <div class="progress-bar" style="width: 20%"></div>
              </div>
                  <span class="progress-description">
                    20% Increase in 30 Days
                  </span>
            </div>
            <!-- /.info-box-content -->
          </div>
        </div>
        <div class="col-md-3">
          <!-- /.info-box -->
          <div class="info-box bg-red">
            <span class="info-box-icon"><i class="glyphicon glyphicon-resize-small"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Credit Recovery</span>
              <span class="info-box-number">114,381</span>

              <div class="progress">
                <div class="progress-bar" style="width: 70%"></div>
              </div>
                  <span class="progress-description">
                    70% Increase in 30 Days
                  </span>
            </div>
            <!-- /.info-box-content -->
          </div>
        </div>
        <div class="col-md-3">
          <!-- /.info-box -->
          <div class="info-box bg-aqua">
            <span class="info-box-icon"><i class="glyphicon glyphicon-signal"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Day Average</span>
              <span class="info-box-number">163,921</span>

              <div class="progress">
                <div class="progress-bar" style="width: 20%"></div>
              </div>
                  <span class="progress-description">
                    40% Increase in 30 Days
                  </span>
            </div>
            <!-- /.info-box-content -->
            </div></div>
          <!-- /.info-box -->
        
          </div>  <!-- /.box -->


     <div class="row">
        <div class="col-md-6">
                    <!-- LINE CHART -->
                    <div class="box box-solid bg-teal-gradient">
            <div class="box-header">
              <i class="fa fa-th"></i>

              <h3 class="box-title">Sales Graph</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i>
                </button>
              </div>
            </div>
            <div class="box-body border-radius-none">
              <div class="chart" id="line-chart" style="height: 300px;"></div>
            </div>
          </div>
        </div>
        <!-- /.col (LEFT) -->
        <div class="col-md-6">
          <!-- BAR CHART -->
          <div class="box box-solid ">
            <div class="box-header ">
              <h3 class="box-title">IN AND OUT Chart</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn  btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-sm" data-widget="remove"><i class="fa fa-times"></i>
                </button>
              </div>
            </div>
            <div class="box-body chart-responsive">
              <div class="chart" id="bar-chart" style="height: 300px;"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </div>
        <!-- /.col (RIGHT) -->


               
     
     
      <div class="col-md-12">
          <!-- BAR CHART -->
          <div class="box box-solid ">
            <div class="box-header ">
              <h3 class="box-title">14 Day Payment Chart</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn  btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-sm" data-widget="remove"><i class="fa fa-times"></i>
                </button>
              </div>
            </div>
            <div class="box-body chart-responsive">
              <div class="chart" id="bar-chart2" ></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </div>

        </div>

	 <div class="row">




	 <?php     $r=$_SESSION['SESS_LAST_NAME'];

 ?>





	      <div class="col-md-4">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-red">
              <div class="widget-user-image">

              </div>
              <!-- /.widget-user-image -->
              <h3 class="widget-user-username">Stores </h3>
              <h5 class="widget-user-desc"></h5>
            </div>
            <div class="box-footer no-padding">
              <ul class="nav nav-stacked">
			   <?php
			   $result = $db->prepare("SELECT * FROM products WHERE product_id>=9  ");
				 $result->bindParam(':userid', $date);
                $result->execute();
                for($i=0; $row = $result->fetch(); $i++){
					?>
                <li><a href="#"><?php echo $row['gen_name']; ?> <span class="pull-right badge bg-red"><?php echo $row['qty']; ?></span></a></li>
                <?php } ?>
                </ul>
            </div>
          </div>
          <!-- /.widget-user -->
        </div>







	        <div class="col-md-4">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-yellow" style="background: url('user_pic/laugh_gas_price_goes_up.jpg') center center;">



              <!-- /.widget-user-image -->
              <h2 class="widget-user-username">YARD</h2>
              <h4 class="widget-user-desc"></h4>
            </div>
            <div class="box-footer no-padding">
              <ul class="nav nav-stacked"><?php
			    $result = $db->prepare("SELECT * FROM products WHERE product_name>=1  ");

					$result->bindParam(':userid', $date);
                $result->execute();
                for($i=0; $row = $result->fetch(); $i++){


					?>
                <li><a href="#"><?php echo $row['gen_name']; ?> <span class="pull-right badge bg-red"><?php echo $row['qty']; ?></span></a></li>
                <?php } ?>
				  <?php
			    $result = $db->prepare("SELECT * FROM products WHERE product_name='' and product_id<9 ");
				  $result->bindParam(':userid', $date);
                $result->execute();
                for($i=0; $row = $result->fetch(); $i++){
				  $pro_id=$row['product_id'];
				  $cha=0;
	        $result1 = $db->prepare("SELECT * FROM products WHERE product_name='$pro_id'  ");
				   $result1->bindParam(':userid', $date);
                $result1->execute();
                for($i=0; $row1 = $result1->fetch(); $i++){
				 $cha=$row1['qty'];
				 }

					?>
                <li><a href="#"><?php echo $row['gen_name']; ?><span class="pull-right badge bg-"><?php echo $row['qty']-$cha; ?></span>
			   <span class="pull-right badge bg-green"><?php echo $row['qty']; ?></span>
				 </a></li>
                <?php } ?>
              </ul>
            </div>
          </div>
          <!-- /.widget-user -->
         </div>


               




		      <div class="col-md-4">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-purple">
              <div class="widget-user-image">

              </div>
              <!-- /.widget-user-image -->
              <h3 class="widget-user-username"> Buffer Stock Balance</h3>
              <h5 class="widget-user-desc"></h5>
            </div>
            <div class="box-footer no-padding">
              <ul class="nav nav-stacked">

				 <?php
			   $result = $db->prepare("SELECT * FROM products WHERE  product_id<9 ");

					$result->bindParam(':userid', $date);
                $result->execute();
                for($i=0; $row = $result->fetch(); $i++){


					?>
                <li><a href="#"><?php echo $row['gen_name']; ?>
	              <span class="pull-right badge bg-red"><?php echo $row['qty']-$row['qty2']; ?></span>
	              <span class="pull-right badge bg-aqua"><?php echo $row['qty2']; ?></span>
					</a></li>

                <?php } ?>
                </ul>
            </div>
          </div>
          <!-- /.widget-user -->
          </div></div>

        <div class="row">

	 <!-- /----------------------------------------------------.Lorry-view --------------------------------------------------------------- -->

	 <?php
	 $result = $db->prepare("SELECT * FROM lorry WHERE action='load'  ");

					$result->bindParam(':userid', $date);
                $result->execute();
                for($i=0; $row = $result->fetch(); $i++){
					$no=$row['lorry_no'];
					$lo_id=$row['loading_id'];



			$result11 = $db->prepare("SELECT * FROM loading WHERE transaction_id='$lo_id' ");
			$result11->bindParam(':userid', $date);
            $result11->execute();
            for($i=0; $row11 = $result11->fetch(); $i++){
			$driver_id=$row11['driver'];
			$result1 = $db->prepare("SELECT * FROM employee WHERE  id='$driver_id'  ");
				$result1->bindParam(':userid', $date);
                $result1->execute();
                for($i=0; $row1 = $result1->fetch(); $i++){
				$driver=$row1['name'];
				$pic=$row1['pic'];
				} }

	 ?>

	 <div class="col-md-4">
          <!-- Widget: user widget style 1 -->

          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user">
		<a href="loading_view.php?id=<?php echo $lo_id; ?>" style="color: black">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-black" style="background: url('user_pic/images.jfif') center center;">
              <h2 class="widget-user-username" style="color: black" ><?php echo $no; ?></h2>
              <h3 class="widget-user-desc" style="color: darkred" ><?php echo $driver; ?></h3>
            </div></a>
            <div class="widget-user-image">
              <img class="img-circle" src="<?php echo $pic; ?>" alt="User Avatar">
            </div>

            <div class="box-footer no-padding">
              <ul class="nav nav-stacked">
			  <?php

			  $result1 = $db->prepare("SELECT * FROM loading_list WHERE loading_id='$lo_id' ");
			  $result1->bindParam(':userid', $date);
              $result1->execute();
              for($i=0; $row1 = $result1->fetch(); $i++){
			  if($row1['product_code']>4){
				  ?>	 <li  style="background: url('user_pic/download.jfif') center;"> <?php
				  } else{
				  echo ' <li >';
				  }

					?>
                <a ><span class="badge bg-black"><?php echo $row1['product_name']; ?></span> <span class="pull-right badge "><?php echo $row1['qty_sold']; ?></span>
				<span class="pull-right badge bg-red"><?php echo $row1['qty']; ?></span>
				</a></li>
                <?php } ?>

                </ul>
            </div>
          </div>
          <!-- /.widget-user -->
        </div>
	 <?php
	 }
	 ?>


        <!-- ./col -->
        </div>




		 </section>
</div>
    </div>
    <!-- /.content -->

  <!-- /.content-wrapper -->
  <?php
  include("dounbr.php");
?>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>

<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="../../bootstrap/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="../../plugins/morris/morris.min.js"></script>
<!-- FastClick -->
<script src="../../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
    <!-- Dark Theme Btn-->
    <script src="https://dev.colorbiz.org/ashen/cdn/main/dist/js/DarkTheme.js"></script>
<!-- page script -->

<script>
  $(function () {
    "use strict";



    // LINE CHART
    var line = new Morris.Line({
      element: 'line-chart',
      resize: true,
      data: [
        {y: '2019 Q1', item1: 26006060},
        {y: '2019 Q2', item1: 27007080},
        {y: '2019 Q3', item1: 49001020},
        {y: '2019 Q4', item1: 37006070},
        {y: '2020 Q1', item1: 68001000},
        {y: '2020 Q2', item1: 56007000},
        {y: '2020 Q3', item1: 48002000},
        {y: '2020 Q4', item1: 150007030},
        {y: '2021 Q1', item1: 106008070},
        {y: '2021 Q2', item1: 84003020}
      ],
      xkey: 'y',
      ykeys: ['item1'],
      labels: ['Value'],
      lineColors: ['#ffffff'],
      gridTextColor: ['#ffffff'],
      hideHover: 'auto'
    });



    //BAR CHART
    var bar = new Morris.Bar({
      element: 'bar-chart',
      resize: true,
      data: [
        {y: '2015', a: 10000000, b: 9000000},
        {y: '2016', a: 7500000, b: 6500000},
        {y: '2017', a: 5000000, b: 4000000},
        {y: '2018', a: 7500000, b: 6500000},
        {y: '2019', a: 5000000, b: 4000000},
        {y: '2020', a: 7500000, b: 6500000},
        {y: '2021', a: 7500000, b: 9700000}
      ],
      barColors: ['#00a65a', '#f56954'],
      xkey: 'y',
      ykeys: ['a', 'b'],
      labels: ['IN', 'OUT'],
      hideHover: 'auto'
    });


    //BAR CHART
    var bar = new Morris.Bar({
      element: 'bar-chart2',
      resize: true,
      data: [
<?php $x=0;
while($x <= 14) {
  $d=strtotime("-$x Day");
$date=date("Y-m-d", $d);
$cash=0; $chq=0;$credit=0;
$result1 = $db->prepare("SELECT  amount FROM payment WHERE date='$date' AND action > '0' AND type='cash' ");
$result1->bindParam(':userid', $date);
        $result1->execute();
        for($i=0; $row1 = $result1->fetch(); $i++){
$cash+=$row1['amount'];
        }
        $result1 = $db->prepare("SELECT  amount FROM payment WHERE date='$date' AND action > '0' AND type='chq' ");
        $result1->bindParam(':userid', $date);
                $result1->execute();
                for($i=0; $row1 = $result1->fetch(); $i++){
        $chq+=$row1['amount'];
                }

                $result1 = $db->prepare("SELECT  amount FROM payment WHERE date='$date' AND action > '0' AND type='credit' ");
        $result1->bindParam(':userid', $date);
                $result1->execute();
                for($i=0; $row1 = $result1->fetch(); $i++){
        $credit+=$row1['amount'];
                }

        $split = explode("-", $date);
        $y= $split[0];
        $m= $split[1];
        $d= $split[2];
        $date=mktime(0,0,0,$m,$d,$y);
        $date= date('M d',$date);
                      
  ?>
        {x:'<?php echo $date;?>' , a: <?php echo $cash;?> , b: <?php echo $chq;?> , c: <?php echo $credit;?>},
  <?php  $x++; } ?>

      ],
      barColors: ['#ff9900', '#8c8c8c', '#cc0000'],
      xkey: 'x',
      ykeys: ['a', 'b', 'c'],
      labels: ['CASH', 'CHQ', 'CREDIT'],
      hideHover: 'auto'
    });    
  });
</script>
</body>
</html>
