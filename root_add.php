<!-- SELECT2 EXAMPLE -->
<div class="box box-info">
  <div class="box-header with-border">
    <h3 class="box-title">New Root</h3>
  </div>

  <div class="box-body">
    <form method="post" action="root_save.php">
      <?php date_default_timezone_set("Asia/Colombo"); ?>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>Root Name</label>
            <input type="text" name="root_name" class="form-control">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label> Area</label>
            <select class="form-control select2" name="area" style="width: 100%;" autofocus>

              <option> Kaluthara </option>
              <option> Horana </option>

            </select>
          </div>
        </div>
      </div>

      <div class="form-group">
        <input class="btn btn-info" type="submit" value="Add">
        <input  type="hidden" name="id" value="0">
      </div>
    </form>
  </div>
</div>
<!-- /.row -->