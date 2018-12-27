<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Finder</h3>
      </div>
      <div class="box-body">
        <form id="DepartmentLocationFinder">
          <div class="row">
            <div class="scopeSelector col-xs-12 col-md-2">
              <label>Scope</label>
              <select id="departmentScope" name="scope" onChange="Department.setLocationSelector(this)" class="form-control">
                <option value="">--</option>
                <?php
                  foreach (lookup('location_scope') as $k => $v) {
                    echo "<option value='{$k}'>{$v}</option>";
                  }
                ?>
              </select>
            </div>
            <div class="col-xs-12 col-md-2 hide" id="citySelectorCont">
              <label for="citySelector">City/Muni</label>
              <select class="form-control" id="citySelector" onchange="Department.loadBarangayOptions(departmentLocation, this)">
                  <option value="">--</option>
                  <?php
                  foreach (lookup_muni_city(null, false) as $v) {
                  echo "<option value='" . $v['citymunCode'] . "'>" . $v['provDesc'] . ' | ' . $v['citymunDesc'] . "</option>";
                  }
                  ?>
              </select>
            </div>
            <div class="locationSelector hidden col-xs-12 col-md-2">
              <label>Location</label>
              <select id="departmentLocation" name="location_code" class="form-control">
                <option value="">--</option>
              </select>
            </div>
            <div class="col-xs-12 col-md-3">
              <label>Display</label>
              <select name="result_filter" id="displayFilter" class="form-control">
                <option value="">Show all</option>
                <option value="1">Show active</option>
                <option value="2">Show active with officers</option>
                <option value="3">Show active without officer</option>
                <option value="4">Show disabled</option>
              </select>
            </div>
            <div class="col-xs-12 col-md-2">
              <label>Keyword</label>
              <input type="text" class="form-control" id="keyword_search" name="keyword" placeholder="search keyword">
            </div>
            <div class="col-xs-12 col-md-1">
              <label>&nbsp;</label>
              <button type="submit" class="form-control btn btn-success">Load</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-xs-12">
    <div class="box box-success">
      <div class="box-header with-border">
        <h3 class="box-title">Results</h3>
      </div>
      <div class="box-body table-responsive no-padding" id="DepartmentLocationResults">
        <h4>Select location scope do display departments.</h4>
      </div>
    </div>
  </div>
</div>

<?php view('pages/department/modals.php'); ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.8/css/alt/AdminLTE-select2.min.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>