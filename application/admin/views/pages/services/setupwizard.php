<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">

            <div class="box-header with-border">
                <?php
                if (isset($returnUrl)) {
                    echo '<a class="btn btn-sm btn-danger" style="margin-top: -4px;margin-right: 5px;" href="' . $returnUrl . '"><i class="fa fa-arrow-left"></i> Back</a>';
                }
                ?>
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
                                                  <img class="image-preview" src="<?php echo public_url() . 'assets/logo/' . (isset($serviceData) ? logo_filename($serviceData->Logo) . '?' . time() : 'blank-logo.png')?>">
                                                  <span class="hiddenFileInput hide">
                                                    <input type="file" data-default="<?php echo public_url() . 'assets/logo/' . (isset($serviceData) ? logo_filename($serviceData->Logo) : 'blank-logo.png')?>" accept="image/*" class="image-upload-input" id="Logo" name="Logo"/>
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
                                                            echo "<option ".(isset($serviceData) && $serviceData->ServiceType == $k ? 'selected="selected"' : '')." value='{$k}'>{$v}</option>";
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
                                                            echo "<option ".(isset($serviceData) && $serviceData->LocationScopeID == $k ? 'selected="selected"' : '')." value='{$k}'>{$v}</option>";
                                                          }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group hide" id="citySelectorCont">
                                            <label for="citySelector">City/Muni</label>
                                            <select class="form-control" id="citySelector" onchange="ServiceSetup.loadBarangayOptions('#LocationCode', this)">
                                                <option value="">--</option>
                                                <?php
                                                foreach (lookup_muni_city(null, false) as $v) {
                                                echo "<option ".(isset($serviceData) && $serviceData->LocationScopeID == 6 && $serviceData->MunicipalityCityID == $v['citymunCode'] ? 'selected="selected"' : '')."  value='" . $v['citymunCode'] . "'>" . $v['provDesc'] . ' | ' . $v['citymunDesc'] . "</option>";
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
                                            <label for="DepartmentScope">Department / Organization</label>
                                            <select disabled class="form-control" name="DepartmentScope" id="DepartmentScope">
                                                <option value="">--</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="Name">Name</label>
                                            <input type="text" class="form-control" name="Name" id="Name" placeholder="Service name" value="<?php echo (isset($serviceData) ? $serviceData->Name : '')?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="Limit">Service Limit</label>
                                            <input type="text" class="form-control" name="Limit" id="Limit" placeholder="Leave blank if not applicable" value="<?php echo (isset($serviceData) && $serviceData->Limit ? $serviceData->Limit : '')?>">
                                            <span class="help-block">Max number of services that can be provided.</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="CycleInterval">Service Cycle Interval</label>
                                            <select class="form-control" name="CycleInterval" id="CycleInterval">
                                                <option value="">--</option>
                                                 <?php
                                                  foreach (lookup('cycle_interval') as $k => $v) {
                                                    echo "<option ".(isset($serviceData) && $serviceData->CycleInterval == $k ? 'selected="selected"' : '')." value='{$k}'>{$v}</option>";
                                                  }
                                                ?>
                                            </select>
                                            <span class="help-block">When can a citizen apply again for this service.</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="Fee">Fee</label>
                                            <input type="text" class="form-control" name="Fee" id="Fee" placeholder="Leave blank if not applicable" value="<?php echo (isset($serviceData) && $serviceData->Fee ? $serviceData->Fee : '')?>">
                                            <span class="help-block">Service or Penalty fee.</span>
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
                                            <textarea rows="4" class="form-control" name="Description" id="Description"><?php echo (isset($serviceData) ? $serviceData->Description : '')?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="TermsCondition">Terms and Condition</label>
                                            <textarea rows="4" class="form-control" name="TermsCondition" id="TermsCondition"><?php echo (isset($serviceData) ? $serviceData->TermsCondition : '')?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="Objectives">Objectives</label>
                                            <textarea rows="4" class="form-control" name="Objectives" id="Objectives"><?php echo (isset($serviceData) ? $serviceData->Objectives : '')?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="Qualifications">Qualifications</label>
                                            <textarea rows="4" class="form-control" name="Qualifications" id="Qualifications"><?php echo (isset($serviceData) ? $serviceData->Qualifications : '')?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="Tags">Tags</label>
                                            <select class="form-control" multiple="" name="Tags[]" id="Tags">
                                                 <?php
                                                  foreach (lookup('service_tags') as $k => $v) {
                                                    echo "<option  ".(isset($serviceData) && in_array($k, json_decode($serviceData->Tags, true))? 'selected="selected"' : '')." value='{$k}'>{$v}</option>";
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
                            <span class="help-block small text-orange" style="padding: 0;margin: 0">For Selection type options. Add item on default value and use Pipe `|` to separate each options. eg: Yes|No</span>
                            <div class="fieldset-content">
                                <table id="serviceExtraFieldsTable" class="table table-responsive">
                                    <thead>
                                        <th style="width: 10px;"></th>
                                        <th>Field Type</th>
                                        <th>Label</th>
                                        <th>Default Value</th>
                                        <th style="width: 30px;"></th>
                                    </thead>
                                    <tbody id="createdFields">
                                        <?php
                                        if (isset($extraFields) && is_array($extraFields)) {
                                            foreach ($extraFields as $item) {
                                                $typeOptions = '';
                                                foreach (lookup('field_type') as $k => $v) {
                                                    $typeOptions .= "<option ".($k == $item['FieldType'] ? 'selected="selected"' : '')." value='{$k}'>{$v}</option>";
                                                }
                                                echo '<tr class="sortable-row" id="'.$item['FieldID'].'">
                                                        <td><i class="drag-handle fa fa-arrows"></i></td>
                                                        <td>
                                                            <select class="form-control fieldType input-sm fType" name="Field['.$item['FieldID'].'][Type]" title="">'
                                                                . $typeOptions .
                                                            '</select>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control fieldLabel input-sm fLabel" placeholder="Field label" autocomplete="off" name="Field['.$item['FieldID'].'][Label]" title="" value="'.$item['FieldLabel'].'">
                                                            <input type="hidden" class="item-order" name="Field['.$item['FieldID'].'][Ordering]" value="'.$item['Ordering'].'" title="">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control fieldValue input-sm fValue" placeholder="Field default value" autocomplete="off" name="Field['.$item['FieldID'].'][DefaultValue]" title="" value="'.$item['DefaultValue'].'">
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger btn-sm" onclick="ServiceSetup.removeFieldRow(this)"><i class="fa fa-trash"></i></button>
                                                            </td>
                                                    </tr>';
                                            }
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot id="addFormFields">
                                        <tr class="info">
                                            <td></td>
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
                                                <input type="text"  maxlength="1000" class="form-control fieldLabel input-sm" placeholder="Field label">
                                            </td>
                                            <td>
                                                <input type="text" maxlength="5000" class="form-control fieldValue input-sm" placeholder="Field default value">
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
                                        <?php
                                        // if (isset($requirements) && is_array($requirements)) {
                                        //     foreach ($requirements as $item) {
                                        //         echo '<tr class="" id="'.$item['id'].'">
                                        //                 <td></td>
                                        //                 <td style="padding-top: 13px;"><input type="hidden" name="Requirement['.$item['id'].'][DocID]" value="'.$item['DocumentID'].'"><b>'.$item['Document'].'</b></td>
                                        //                 <td>
                                        //                     <textarea rows="1" class="requirementDesc form-control input-sm" placeholder="Requirement description" autocomplete="off" name="Requirement['.$item['id'].'][Desc]"></textarea>
                                        //                 </td>
                                        //                 <td><button type="button" class="btn btn-danger btn-sm" onclick="ServiceSetup.removeRequirementRow(this)"><i class="fa fa-trash"></i></button></td>
                                        //             </tr>';
                                        //     }
                                        // }
                                        ?>
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

                    <?php
                    if (isset($serviceData)) {
                        echo '<input type="hidden" id="edit-mode" value="1">';
                    }
                    ?>

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

        setTimeout(function(){
            <?php 
            if (isset($serviceData)) {
                echo 'ServiceSetup.setLocationSelector($("#LocationScope"), "'. $serviceData->LocationCode .'", "'. $serviceData->MunicipalityCityID .'", function() {
                    ServiceSetup.setDepartmentSelector('. $serviceData->DepartmentLocationID .');
                });';

                if ($serviceData->LocationScopeID == 6) {
                    echo 'ServiceSetup.loadBarangayOptions("#LocationCode", "#citySelector", "'. $serviceData->BarangayID .'", function() {
                        ServiceSetup.setDepartmentSelector('. $serviceData->DepartmentLocationID .');
                    });';
                }

                // set requirement rows
                if (isset($requirements) && is_array($requirements)) {
                    foreach ($requirements as $item) {
                        $json_params = json_encode(array(
                            'docid'     => $item['DocumentID'],
                            'desc'      => $item['Description'],
                            'docname'   => $item['Document'],
                            'id'        => $item['id']
                        ), JSON_HEX_TAG);
                        echo "ServiceSetup.addRequirementRow({$json_params});";
                    }
                }

                // set function rows
                if (isset($processOrder['orderedProcess']) && is_array($processOrder['orderedProcess'])) {
                    foreach ($processOrder['orderedProcess'] as $item) {
                        $fortext = 'Main Service';
                        if ($item['For'] != 'Main') {
                            $fortext = $requirements[$item['For']]['Document'];
                        }
                        $json_params = json_encode(array(
                            'fncfor'     => $item['For'],
                            'fnctype'      => $item['FunctionTypeID'],
                            'fncdesc'   => $item['Description'],
                            'fortxt'   => $fortext,
                            'typetxt'   => $item['FunctionName'],
                            'id'        => $item['id']
                        ), JSON_HEX_TAG);
                        echo "ServiceSetup.addFunctionRow({$json_params});";
                    }
                }

                if (isset($officers) && is_array($officers)) {
                    foreach ($officers as $functionID => $assign_officers) {
                        foreach ($assign_officers as $assign_officer) {
                            echo "ServiceSetup.officerFinderSelected[{$functionID}] = " . json_encode($assign_officer['userData']) . ';';
                            echo "ServiceSetup.addOfficer({$functionID});";
                        }
                    }
                }
            }
            ?>
        }, 100);
    })
</script>