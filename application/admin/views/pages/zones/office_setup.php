<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">

            <div class="box-header with-border">
                <h3 class="box-title" style="display: block;width: 100%;">
                    <a class="btn btn-sm btn-danger" style="margin-top: -4px;margin-right: 5px;" href="<?php echo $returnUrl ?>"><i class="fa fa-arrow-left"></i> Back</a>
                    <span style="line-height: 2.5"><span><?php echo ($zonetype == 'city' ? $locationInfo->citymunDesc : $locationInfo->provDesc); ?> | <b class="text-red"><?php echo $psgc; ?></b></span></span>
                    <img style="width: 50px; height: 50px; float: right;" src="<?php echo public_url('assets/logo/') . logo_filename($locationInfo->logo)?>">
                </h3>
            </div>

            <div class="box-body">
                <form id="PublicOfficeSetupForm" class="wizard-content hidden" action="<?php echo site_url('zones/save_office_setup') ?>" enctype="multipart/form-data">
                    <div id="error_message_box" class="hide row">
                        <div class="error_messages no-border-radius alert alert-danger" role="alert"></div>
                    </div>

                    <div class="div-wizard">
                        <h3>Office Information</h3>
                        <fieldset>
                            <h2>Office Information</h2>
                            <p class="desc">Public office informations</p>
                            <div class="fieldset-content">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="Name">Name</label>
                                            <input type="text" class="form-control" name="Name" id="Name" placeholder="Public Office Name" value="<?php echo ($currentData ? $currentData->Name : '')?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="Domain">Domain</label>
                                            <input type="text" class="form-control" name="Domain" id="Domain" placeholder="Domain" value="<?php echo ($currentData ? $currentData->Domain : '')?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="Email">Email</label>
                                            <input type="text" class="form-control" name="Email" id="Email" placeholder="Email" value="<?php echo ($currentData ? $currentData->Email : '')?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="Contact">Contact</label>
                                            <input type="text" class="form-control" name="Contact" id="Contact" placeholder="Contact Number" value="<?php echo ($currentData ? $currentData->Contact : '')?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="Address">Address</label>
                                            <textarea rows="2" class="form-control" name="Address" id="Address" placeholder="Address"><?php echo ($currentData ? $currentData->Address : '')?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="Message">Message</label>
                                            <textarea rows="3" class="form-control" name="Message" id="Message" placeholder="Message to the public"><?php echo ($currentData ? $currentData->Message : '')?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="Description">Description</label>
                                            <textarea rows="2" class="form-control" name="Description" id="Description" placeholder="Description"><?php echo ($currentData ? $currentData->Description : '')?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="News">News</label>
                                            <textarea rows="2" class="form-control" name="News" id="News" placeholder="News"><?php echo ($currentData ? $currentData->News : '')?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="Events">Events</label>
                                            <textarea rows="2" class="form-control" name="Events" id="Events" placeholder="Events"><?php echo ($currentData ? $currentData->Events : '')?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <h3>About the <?php echo ucfirst($zonetype) ?></h3>
                        <fieldset>
                            <h2>About the <?php echo ucfirst($zonetype) ?></h2>
                            <div class="fieldset-content">
                                <div class="row">
                                     <div class="col-md-12 col-lg-6">
                                        <div class="form-group">
                                            <label for="About">About</label>
                                            <textarea rows="3" class="form-control" name="About" id="About" placeholder="About"><?php echo ($currentData ? $currentData->About : '')?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="Demography">Demography</label>
                                            <textarea rows="3" class="form-control" name="Demography" id="Demography" placeholder="Demography"><?php echo ($currentData ? $currentData->Demography : '')?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-6">
                                        <div class="form-group">
                                            <label for="Vision">Vision</label>
                                            <textarea rows="3" class="form-control" name="Vision" id="Vision" placeholder="Vision"><?php echo ($currentData ? $currentData->Vision : '')?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="Mission">Mission</label>
                                            <textarea rows="3" class="form-control" name="Mission" id="Mission" placeholder="Mission"><?php echo ($currentData ? $currentData->Mission : '')?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <h3>Public Servants</h3>
                        <fieldset>
                            <h2>Public Servants</h2>
                            <p class="desc">Drag to order hierarchy. First item will be the leader.</p>
                            <div class="fieldset-content">
                                <table id="publicServantsTable" class="table table-responsive">
                                    <thead>
                                        <th style="width: 10px;"></th>
                                        <th>Position</th>
                                        <th>Firstname</th>
                                        <th>Lastname</th>
                                        <th align="center">Photo</th>
                                        <th style="width: 30px;"></th>
                                    </thead>
                                    <tbody id="addedPublicServants">
                                        <?php
                                        if ($currentData) {
                                            $currentServants = json_decode($currentData->PublicServants, true);
                                            foreach ($currentServants as $id => $item) {
                                                echo '<tr class="sortable-row" id="'. $id .'">
                                                        <td><i class="drag-handle fa fa-arrows"></i></td>
                                                        <td>
                                                            <div class="form-group">
                                                                <input type="text" name="Servant['.$id.'][Position]" class="form-control fieldPosition input-sm" placeholder="Position" value="'.$item['Position'].'">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <input type="text" name="Servant['.$id.'][Firstname]" class="form-control fieldFirstname input-sm" placeholder="Firstname" value="'.$item['Firstname'].'">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <input type="text" name="Servant['.$id.'][Lastname]" class="form-control fieldLastname input-sm" placeholder="Lastname" value="'.$item['Lastname'].'">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="smaller image-upload-container">
                                                              <img class="image-preview img-responsive" src="' . public_url() . 'assets/' . (isset($item['Photo']) && $item['Photo'] ? '/etc/' . $item['Photo'] : '/profile/avatar_default.jpg') . '">
                                                              <span class="hiddenFileInput hide">
                                                                <input type="file" accept="image/*" class="image-upload-input fieldPhoto" name="Images['.$id.']"/>
                                                              </span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger btn-sm" onClick="Zones.removePublicServantRow(this)"><i class="fa fa-trash"></i></button>
                                                            <input type="hidden" class="item-order" name="Servant['.$id.'][Ordering]" value="'.$item['Ordering'].'">
                                                        </td>
                                                    </tr>';
                                            }
                                        }
                                        ?>
                                        <tr class="info" id="<?php echo $initialServantID ?>">
                                            <td></td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="text" name="Servant[<?php echo $initialServantID ?>][Position]" class="form-control fieldPosition input-sm" placeholder="Position">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="text" name="Servant[<?php echo $initialServantID?>][Firstname]" class="form-control fieldFirstname input-sm" placeholder="Firstname">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="text" name="Servant[<?php echo $initialServantID?>][Lastname]" class="form-control fieldLastname input-sm" placeholder="Lastname">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="smaller image-upload-container">
                                                  <img class="image-preview" src="<?php echo public_url(); ?>assets/profile/avatar_default.jpg">
                                                  <span class="hiddenFileInput hide">
                                                    <input type="file" accept="image/*" class="image-upload-input fieldPhoto" name="Images[<?php echo $initialServantID?>]"/>
                                                  </span>
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-success btn-sm" onclick="Zones.addPublicServantRow()"><i class="fa fa-plus"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </fieldset>

                        <h3>Banners</h3>
                        <fieldset>
                            <h2>Banners</h2>
                            <div class="fieldset-content">
                                <table id="bannersTable" class="table table-responsive">
                                    <thead>
                                        <th style="width: 10px;"></th>
                                        <th></th>
                                        <th></th>
                                        <th style="width: 30px;"></th>
                                    </thead>
                                    <tbody id="addedBanners">
                                        <?php
                                        if ($currentData) {
                                            $currentBanners = json_decode($currentData->Banners, true);
                                            foreach ($currentBanners as $id => $item) {
                                                echo '<tr class="sortable-row" id="'. $id .'">
                                                        <td><i class="drag-handle fa fa-arrows"></i></td>
                                                        <td>
                                                            <div class="banner image-upload-container">
                                                              <img class="image-preview img-responsive" src="' . public_url() . 'assets/etc/' . (isset($item['Photo']) && $item['Photo'] ? $item['Photo'] : 'placeholder-banner.png') . '">
                                                              <span class="hiddenFileInput hide">
                                                                <input type="file" accept="image/*" data-default="' . public_url() . 'assets/etc/placeholder-banner.png" class="image-upload-input fieldBanner" name="Images['. $id .']"/>
                                                              </span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <input type="text" name="Banners['.$id.'][URL]" class="form-control fieldUrl input-sm" placeholder="Link URL" value="'.(isset($item['URL']) ? $item['URL'] : '').'">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger btn-sm" onClick="Zones.removeBannerRow(this)"><i class="fa fa-trash"></i></button>
                                                            <input type="hidden" class="item-order" name="Banners['.$id.'][Ordering]" value="'.$item['Ordering'].'">
                                                        </td>
                                                    </tr>';
                                            }
                                        }
                                        ?>
                                        <tr class="info" id="<?php echo $initialBannerID ?>">
                                            <td></td>
                                            <td>
                                                <div class="banner image-upload-container">
                                                  <img class="image-preview img-responsive" src="<?php echo public_url(); ?>assets/etc/placeholder-banner.png">
                                                  <span class="hiddenFileInput hide">
                                                    <input type="file" accept="image/*" data-default="<?php echo public_url(); ?>assets/etc/placeholder-banner.png" class="image-upload-input fieldBanner" name="Images[<?php echo $initialBannerID ?>]"/>
                                                  </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="text" name="Banners[<?php echo $initialBannerID?>][URL]" class="form-control fieldUrl input-sm" placeholder="Link URL">
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-success btn-sm" onclick="Zones.addBannerRow()"><i class="fa fa-plus"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </fieldset>

                        <h3>Other Details</h3>
                        <fieldset>
                            <h2>Other Details</h2>
                            <div class="fieldset-content">
                                <div class="row">
                                     <div class="col-md-12 col-lg-6">
                                        <div class="form-group">
                                            <label for="Councils">Councils</label>
                                            <textarea rows="3" class="form-control" name="Councils" id="Councils" placeholder="Councils"><?php echo ($currentData ? $currentData->Councils : '')?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="Departments">Departments</label>
                                            <textarea rows="3" class="form-control" name="Departments" id="Departments" placeholder="Departments"><?php echo ($currentData ? $currentData->Departments : '')?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="Jobs">Jobs</label>
                                            <textarea rows="3" class="form-control" name="Jobs" id="Jobs" placeholder="Jobs"><?php echo ($currentData ? $currentData->Jobs : '')?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-6">
                                        <div class="form-group">
                                            <label for="Bids">Bids</label>
                                            <textarea rows="3" class="form-control" name="Bids" id="Bids" placeholder="Bids"><?php echo ($currentData ? $currentData->Bids : '')?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="Awards">Awards</label>
                                            <textarea rows="3" class="form-control" name="Awards" id="Awards" placeholder="Awards"><?php echo ($currentData ? $currentData->Awards : '')?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="BusinessDirectory">BusinessDirectory</label>
                                            <textarea rows="3" class="form-control" name="BusinessDirectory" id="BusinessDirectory" placeholder="BusinessDirectory"><?php echo ($currentData ? $currentData->BusinessDirectory : '')?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                    </div>

                    <input type="hidden" id="zonetype" name="zonetype" value="<?php echo $zonetype ?>">
                    <input type="hidden" id="zonepsgc" name="zonepsgc" value="<?php echo $psgc ?>">

                </form>
            </div>

        </div>
    </div>
</div>

<style type="text/css">
    #publicServantsTable td, #bannersTable td {
        vertical-align: middle;
    }

    #publicServantsTable .form-group, #bannersTable .form-group {
        margin-bottom: 0;
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.8/css/alt/AdminLTE-select2.min.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-steps/1.1.0/jquery.steps.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.6.0/Sortable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/autosize.js/4.0.2/autosize.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(){

    })
</script>