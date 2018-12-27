<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">

            <div class="box-header with-border">
                <h3 class="box-title">
                    <span>Service Code | <b class="text-red"><?php echo $serviceCode; ?></b></span>
                </h3>
            </div>

            <div class="box-body">
                <form id="ServiceSetupForm" class="wizard-content hidden" action="<?php echo site_url('services/save_setup') ?>" enctype="multipart/form-data">
                    <div id="error_message_box" class="hide row">
                        <div class="error_messages no-border-radius alert alert-danger" role="alert"></div>
                    </div>
                    <div class="div-wizard">
                        <h3>Service Information</h3>
                        <fieldset>
                            <h2>Service Information</h2>
                            <p class="desc">Please enter service informations and proceed to next step for additonal data needed for service</p>
                            <div class="fieldset-content">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="row">
                                            <div class="col-sm-12 col-lg-4 col-lg-push-8 logo padding-top-10">
                                                <div class="image-upload-container">
                                                  <img class="image-preview" src="<?php echo public_url(); ?>assets/logo/blank-logo.png">
                                                  <span class="hiddenFileInput hide">
                                                    <input type="file" data-default="<?php echo public_url(); ?>assets/logo/blank-logo.png" accept="image/*" class="image-upload-input" id="Logo" name="Logo"/>
                                                  </span>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-lg-8 col-lg-pull-4">
                                                <div class="form-group">
                                                    <label for="ServiceType">Service Type</label>
                                                    <select class="form-control" name="ServiceType" id="ServiceType">
                                                        <option value="">--</option>
                                                         <?php
                                                          foreach (lookup('service_type') as $k => $v) {
                                                            echo "<option value='{$k}'>{$v}</option>";
                                                          }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="LocationScope">Scope</label>
                                                    <select class="form-control" name="LocationScope" id="LocationScope" onchange="ServiceSetup.setLocationSelector(this); ServiceSetup.setDepartmentSelector()">
                                                        <option value="">--</option>
                                                         <?php
                                                          foreach (lookup('location_scope') as $k => $v) {
                                                            echo "<option value='{$k}'>{$v}</option>";
                                                          }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group hide" id="citySelectorCont">
                                            <label for="citySelector">City/Muni</label>
                                            <select class="form-control" id="citySelector" onchange="ServiceSetup.loadBarangayOptions(LocationCode, this)">
                                                <option value="">--</option>
                                                <?php
                                                foreach (lookup_muni_city(null, false) as $v) {
                                                echo "<option value='" . $v['citymunCode'] . "'>" . $v['provDesc'] . ' | ' . $v['citymunDesc'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="LocationCode">Location</label>
                                            <select disabled class="form-control" name="LocationCode" id="LocationCode" onchange="ServiceSetup.setDepartmentSelector()">
                                                <option value="">--</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="DepartmentScope">Department</label>
                                            <select disabled class="form-control" name="DepartmentScope" id="DepartmentScope">
                                                <option value="">--</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="Name">Name</label>
                                            <input type="text" class="form-control" name="Name" id="Name" placeholder="Service name">
                                        </div>
                                        <div class="form-group">
                                            <label for="Limit">Service Limit</label>
                                            <input type="text" class="form-control" name="Limit" id="Limit" placeholder="Leave blank if not applicable">
                                            <span class="help-block">Max number of services that can be provided.</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="CycleInterval">Service Cycle Interval</label>
                                            <select class="form-control" name="CycleInterval" id="CycleInterval">
                                                <option value="">--</option>
                                                 <?php
                                                  foreach (lookup('cycle_interval') as $k => $v) {
                                                    echo "<option value='{$k}'>{$v}</option>";
                                                  }
                                                ?>
                                            </select>
                                            <span class="help-block">When can a citizen apply again for this service.</span>
                                        </div>

                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="row">
                                            <div class="col-md-6 visible-md padding-bottom-30 padding-top-30">
                                                <div class="padding-bottom-30 padding-top-30"></div>
                                                <div class="padding-bottom-30 padding-top-10"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Description">Description</label>
                                            <textarea rows="3" class="form-control" name="Description" id="Description"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="TermsCondition">Terms and Condition</label>
                                            <textarea rows="3" class="form-control" name="TermsCondition" id="TermsCondition"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="Objectives">Objectives</label>
                                            <textarea rows="3" class="form-control" name="Objectives" id="Objectives"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="Qualifications">Qualifications</label>
                                            <textarea rows="3" class="form-control" name="Qualifications" id="Qualifications"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="Tags">Tags</label>
                                            <select class="form-control" multiple="" name="Tags[]" id="Tags">
                                                 <?php
                                                  foreach (lookup('service_tags') as $k => $v) {
                                                    echo "<option value='{$k}'>{$v}</option>";
                                                  }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <h3>Additional Fields</h3>
                        <fieldset>
                            <h2>Additional Fields</h2>
                            <p class="desc">Please setup additional fields needed on service application form. <br> Drag item to change order</p>
                            <div class="fieldset-content">
                                <table id="serviceExtraFieldsTable" class="table table-responsive">
                                    <thead>
                                        <th style="width: 10px;"></th>
                                        <th>Field Group</th>
                                        <th>Field Type</th>
                                        <th>Label</th>
                                        <th style="width: 30px;"></th>
                                    </thead>
                                    <tbody id="createdFields">
                                        
                                    </tbody>
                                    <tfoot id="addFormFields">
                                        <tr class="info">
                                            <td></td>
                                            <td>
                                                <select class="form-control fieldGroup input-sm">
                                                     <?php
                                                      foreach (lookup('field_class') as $k => $v) {
                                                        echo "<option value='{$k}'>{$v}</option>";
                                                      }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control fieldType input-sm">
                                                     <?php
                                                      foreach (lookup('field_type') as $k => $v) {
                                                        echo "<option value='{$k}'>{$v}</option>";
                                                      }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control fieldLabel input-sm" placeholder="Field label">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-success btn-sm" onclick="ServiceSetup.addFieldRow()"><i class="fa fa-plus"></i></button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </fieldset>

                        <h3>Service Requirements</h3>
                        <fieldset>
                            <h2>Service Requirements</h2>
                            <p class="desc">Setup service document requirements as needed.<br>Requirement process order can be done on functions setup.</p>
                            <div class="fieldset-content">
                                <table id="serviceRequirementsTable" class="table table-responsive">
                                    <thead>
                                        <th style="width: 10px;"></th>
                                        <th style="width: 30%">Document</th>
                                        <th>Description</th>
                                        <th style="width: 30px;"></th>
                                    </thead>
                                    <tbody id="createdRequirements">
                                        
                                    </tbody>
                                    <tfoot id="addRequirementsRow">
                                        <tr class="info">
                                            <td></td>
                                            <td>
                                                <select class="form-control requirementDoc input-sm">
                                                    <option value="">--</option>
                                                     <?php
                                                      // foreach (lookup('document_list') as $k => $v) {
                                                      //   echo "<option value='{$k}'>{$v}</option>";
                                                      // }
                                                     foreach(lookup_all('Doc_Templates', false, 'Name') as $item) {
                                                        echo '<option value="' . $item['id'] . '">' . $item['Name'] . '</option>';
                                                     }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <textarea rows="1" class="requirementDesc form-control input-sm" placeholder="Requirement description"></textarea>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-success btn-sm" onclick="ServiceSetup.addRequirementRow()"><i class="fa fa-plus"></i></button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </fieldset>

                        <h3>Functions and Officers</h3>
                        <fieldset>
                            <h2>Service Functions</h2>
                            <p class="desc">
                                Setup service or requirements functions and assign officers on each functions and process steps order.
                                <br>Drag item to change process flow
                            </p>
                            <div class="fieldset-content">
                                <table id="serviceFunctionsTable" class="table table-responsive">
                                    <thead>
                                        <th style="width: 10px;"></th>
                                        <th style="width: 200px">For</th>
                                        <th style="width: 150px">Function Type</th>
                                        <th style="width: 150px;">Description</th>
                                        <th style="min-width: 150px">Officers</th>
                                        <th style="width: 30px;"></th>
                                    </thead>
                                    <tbody id="createdFunctions">
                                        
                                    </tbody>
                                    <tfoot id="addFunctionRow">
                                        <tr class="info">
                                            <td></td>
                                            <td>
                                                <select class="form-control functionFor input-sm">
                                                    <option value="Main">Main Service</option>
                                                    <optgroup label="Requirements"></optgroup>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control functionType input-sm">
                                                    <option value="">--</option>
                                                     <?php
                                                      foreach (lookup('function_type') as $k => $v) {
                                                        echo "<option value='{$k}'>{$v}</option>";
                                                      }
                                                    ?>
                                                </select>
                                            </td>
                                            <td colspan="2" class="func-desc-td">
                                                <textarea rows="1" class="functionDesc form-control input-sm" placeholder="Function description"></textarea>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-success btn-sm" onclick="ServiceSetup.addFunctionRow()"><i class="fa fa-plus"></i></button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </fieldset>

                    </div>

                    <input type="hidden" name="Code" value="<?php echo $serviceCode; ?>">
                    <div id="assignedOfficersHidden"></div>
                </form>
            </div>
        </div>
    </div>
</div>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.8/css/alt/AdminLTE-select2.min.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-steps/1.1.0/jquery.steps.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.6.0/Sortable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/autosize.js/4.0.2/autosize.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $("input, textarea").attr('autocomplete', 'off');
    })
</script>