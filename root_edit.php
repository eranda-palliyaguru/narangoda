<!-- SELECT2 EXAMPLE -->
<div class="box box-success">
  <div class="box-header with-border">
    <h3 class="box-title">New Root</h3>
  </div>

  <div class="box-body">
    <form method="post" action="root_save.php">
      <?php date_default_timezone_set("Asia/Colombo");
      include('connect.php');
      $id = $_GET['id'];
      $result = $db->prepare("SELECT * FROM root WHERE root_id=:id ");
      $result->bindParam(':id', $id);
      $result->execute();
      for ($i = 0; $row = $result->fetch(); $i++) {
        $name =  $row['root_name'];
        $area =  $row['root_area'];
      } ?>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>Root Name</label>
            <input type="text" name="root_name" value="<?php echo $name; ?>" class="form-control">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label> Area</label>
            <select class="form-control select2" name="area" style="width: 100%;" autofocus>

              <option <?php if ($area == 'Kaluthara') {
                        echo 'selected';
                      } ?>> Kaluthara </option>
              <option <?php if ($area == 'Horana') {
                        echo 'selected';
                      } ?>> Horana </option>

            </select>
          </div>
        </div>
      </div>

      <div class="form-group">
        <input class="btn btn-success" type="submit" value="Update">
        <input  type="hidden" name="id" value="<?php echo $id; ?>">
      </div>
    </form>
  </div>
</div>
<!-- /.row -->