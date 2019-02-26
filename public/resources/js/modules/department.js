function Department() {

    // because this is overwritten on jquery events
    var self = this;

    // initialize module variables
    this.departmentData = {}
    this.officersData = {}
    this.officerChanged;
    this.sortable = {};
    this.categorychanged;

    /**
     * Initialize events
     */
    this._init = function() 
    {

        self.set_events();
        self.set_configs();

    }

    /**
    * events delaration
    */
    this.set_events = function()
    {

        $('#DepartmentForm').submit(function(e) {
            e.preventDefault();
            self.saveDepartment(this);
        });

        $('#SubDepartmentForm').submit(function(e) {
            e.preventDefault();
            self.saveDepartment(this);
        });

        $('#DepartmentLocationFinder').submit(function(e) {
            e.preventDefault();
            self.getDepartmentLocations(this);
        });

        $('#DepartmentLocationForm').submit(function(e) {
            e.preventDefault();
            self.saveDepartmentLocation(this);
        });

        $('#DepartmentOfficerForm').submit(function(e) {
            e.preventDefault();
            self.saveDepartmentOfficer(this);
        });

        $('#DepartmentOfficerForm #accountFinder').keydown(function(e){
            if(e.keyCode == 13) {
                e.preventDefault();
                return false;
            }
        });

        $('#organizationSetupForm').submit(function(e) {
            e.preventDefault();
            self.saveOrganizationSetup(this);
        });

        $('#organizationCategoryForm').submit(function(e){
            e.preventDefault();
            self.saveOrganizationCategory(this);
        });

    }

    /**
    * usage of different libraries and plugins
    */
    this.set_configs = function()
    {
        
    }

    /**
    * show/hide department children
    */
    this.toggleSubDepartment = function(id)
    {
        $('#dept_' + id).toggleClass('hidden');
    }

    this.getDepartment = function(id)
    {   

        if (typeof(this.departmentData[id]) != 'undefined') {
            return this.departmentData[id];
        }

        return false;
    }

    this.getSubDepartment = function(parentID, subID)
    {   

        if (this.getDepartment(parentID) != false) {
            if (typeof(this.getDepartment(parentID).subDepartment[subID]) != 'undefined') {
                return this.getDepartment(parentID).subDepartment[subID];
            }
        }

        return false;
    }

    /**
    * add new department
    */
    this.addDepartment = function()
    {   
        // reset form data
        $('#DepartmentForm').trigger("reset");

        // reset input erros
        $.each($('#DepartmentForm').find('input'), function(i,e){
            $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger').addClass('text-white');
            $(e).popover('destroy');
        });
        //clean error box
        $('#DepartmentForm').find('#error_message_box .error_messages').html('');
        $('#DepartmentForm').find('#error_message_box').addClass('hide');

        $('#DepartmentForm #id').val('');
        $('#DepartmentForm .image-preview').prop('src', window.public_url() + 'assets/logo/blank-logo.png');

        $('#departmentModal .modal-title').html('<b>Department</b> | Add');
        $('#departmentModal').modal({
            backdrop : 'static',
            keyboard : false
        });
    }

    /**
    * edit department
    */
    this.editDepartment = function(id)
    {   
        var data = this.getDepartment(id);

        // reset form data
        $('#DepartmentForm').trigger("reset");

        // reset input erros
        $.each($('#DepartmentForm').find('input, select'), function(i,e){
            $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger').addClass('text-white');
            $(e).popover('destroy');
        });
        //clean error box
        $('#DepartmentForm').find('#error_message_box .error_messages').html('');
        $('#DepartmentForm').find('#error_message_box').addClass('hide');

        $('#DepartmentForm #id').val(data.id);
        $('#DepartmentForm #Code').val(data.Code);
        $('#DepartmentForm #Name').val(data.Name);
        $('#DepartmentForm #FunctionMandate').val(data.FunctionMandate);
        $('#DepartmentForm #Address').val(data.Address);

        $('#DepartmentForm .image-preview').prop('src', window.public_url() + 'assets/logo/' + data.Logo);

        $('#departmentModal .modal-title').html('<b>Department</b> | Update');
        $('#departmentModal').modal({
            backdrop : 'static',
            keyboard : false
        });
    }

    /**
    * delete department
    */
    this.deleteDepartment = function(id)
    {   
        var data = self.getDepartment(id);
        if (data) {
            bootbox.confirm('Are you sure you want to <label class="label label-danger">delete</label> ' + data.Name, function(r){
                if (r) {
                    $.LoadingOverlay("show", {zIndex: 999});
                    $.ajax({
                        url: window.base_url('department/delete_department/' + id),
                        type: 'GET',
                        success: function (response) {
                            if (response.status) {
                                bootbox.alert(response.message, function(){
                                    location.reload(); //easy way, just reload the page
                                });
                            } else {
                                $.LoadingOverlay("hide");
                            }
                        }
                    });
                }
            });
        }
    }

    /**
    * delete department
    */
    this.deleteSubDepartment = function(parentID, subID)
    {   
        var data        = self.getSubDepartment(parentID, subID);
        if (data) {
            bootbox.confirm('Are you sure you want to <label class="label label-danger">delete</label> ' + data.Name, function(r){
                if (r) {
                    $.LoadingOverlay("show", {zIndex: 999});
                    $.ajax({
                        url: window.base_url('department/delete_sub_department/' + subID),
                        type: 'GET',
                        success: function (response) {
                            if (response.status) {
                                bootbox.alert(response.message, function(){
                                    location.reload(); //easy way, just reload the page
                                });
                            } else {
                                $.LoadingOverlay("hide");
                            }
                        }
                    });
                }
            });
        }
    }

    /**
    * save new or edit department 
    */
    this.saveDepartment = function(form)
    {

        // prenvet multiple calls
        if ($(form).data('running')) {
            return false;
        }

        $(form).data('running', true);
        $(form).find('input').blur();
        $(form).LoadingOverlay("show");

        var formData = new FormData(form);
        
        // reset input erros
        $.each($(form).find('input, select'), function(i,e){
            $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger').addClass('text-white');
            $(e).popover('destroy');
        });
        //clean error box
        $(form).find('#error_message_box .error_messages').html('');
        $(form).find('#error_message_box').addClass('hide');

        $.ajax({
            url: $(form).prop('action'),
            type: 'POST',
            data: formData,
            success: function (response) {
                if (response.status) {
                    location.reload(); //easy way, just reload the page
                } else {
                    // bootbox.alert(response.message);
                    $(form).find('#error_message_box .error_messages').append('<p><b>' + response.message + '</b></p>');

                    $.each(response.fields, function(i,e){
                        $(form).find('#'+i).prop('title', e).closest('div').addClass('has-error').find('label').removeClass('text-white').addClass('text-danger');
                        Utils.popover($('#'+i), {
                            t: 'hover',
                            p: 'top',
                            m: e
                        });
                        $(form).find('#error_message_box .error_messages').append('<p>' + e + '</p>');
                    });

                    $(form).find('#error_message_box').removeClass('hide');
                }
            },
            complete: function() {
                $(form).LoadingOverlay("hide");
                $(form).data('running', false);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }

    /**
    * add new sub department
    */
    this.addSubDepartment = function(parentID)
    {
        var parentData = this.getDepartment(parentID);

        if (parentData != false) {

            // reset form data
            $('#SubDepartmentForm').trigger("reset");
            $('#SubDepartmentForm #id').val('');

                    // reset form data
            $('#SubDepartmentForm').trigger("reset");

            // reset input erros
            $.each($('#SubDepartmentForm').find('input, select'), function(i,e){
                $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger').addClass('text-white');
                $(e).popover('destroy');
            });
            //clean error box
            $('#SubDepartmentForm').find('#error_message_box .error_messages').html('');
            $('#SubDepartmentForm').find('#error_message_box').addClass('hide');

            $('#SubDepartmentForm #DepartmentID').val(parentData.id);
            $('#subDepartmentModal .forDepartment').text(parentData.Name);
            $('#SubDepartmentForm .image-preview').prop('src', window.public_url() + 'assets/logo/blank-logo.png');

            $('#subDepartmentModal .modal-title').html('<b>Sub Department / Office</b> | Add');
            $('#subDepartmentModal').modal({
                backdrop : 'static',
                keyboard : false
            });

        }
    }

    /**
    * edit sub department
    */
    this.editSubDepartment = function(parentID, subID)
    {   
        var data        = this.getSubDepartment(parentID, subID);
        var parentData  = this.getDepartment(parentID);

        if (data != false) {

            // reset form data
            $('#SubDepartmentForm').trigger("reset");

            // reset input erros
            $.each($('#SubDepartmentForm').find('input, select'), function(i,e){
                $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger').addClass('text-white');
                $(e).popover('destroy');
            });
            //clean error box
            $('#SubDepartmentForm').find('#error_message_box .error_messages').html('');
            $('#SubDepartmentForm').find('#error_message_box').addClass('hide');

            $('#SubDepartmentForm #id').val(data.id);
            $('#SubDepartmentForm #DepartmentID').val(parentData.id);
            $('#SubDepartmentForm #Type').val(data.Type);
            $('#SubDepartmentForm #Code').val(data.Code);
            $('#SubDepartmentForm #Name').val(data.Name);
            $('#SubDepartmentForm #FunctionMandate').val(data.FunctionMandate);
            $('#SubDepartmentForm #Address').val(data.Address);

            $('#subDepartmentModal .forDepartment').text(parentData.Name);
            $('#SubDepartmentForm .image-preview').prop('src', window.public_url() + 'assets/logo/' + data.Logo);

            $('#subDepartmentModal .modal-title').html('<b>Sub Department / Office</b> | Update');
            $('#subDepartmentModal').modal({
                backdrop : 'static',
                keyboard : false
            });

        }
    }


    /**
    * set location dropdown by scope
    */
    this.setLocationSelector = function(e)
    {   
        // reset location value
        if ($(self.form).find('#departmentLocation').hasClass("select2-hidden-accessible")) {
            $(self.form).find('#departmentLocation').select2('destroy');
        }
        if ($(self.form).find('#citySelector').hasClass("select2-hidden-accessible")) {
            $(self.form).find('#citySelector').select2('destroy');
        }
        $('#departmentLocation').val('').html('').prop('disabled', true);
        $('#citySelectorCont').addClass('hide');

        // Regional to barangay
        var scope = $(e).val();
        if (scope > 1) {
            $('.locationSelector').removeClass('hidden');
            if (scope == 6) {
                $('#citySelectorCont').removeClass('hide');
                $('#citySelector').val('').select2({
                    width: '100%'
                });
            } else {
                $('#DepartmentLocationFinder').LoadingOverlay("show");
                $('#departmentLocation').html(window.emptySelectOption);
                $.get(window.base_url('department/get_scope_locations'), {'scope' : scope}).done(function(data) {
                    var options = window.emptySelectOption;
                    $.each(data, function(i, e){
                        var key   = false;
                        var value = false;
                        switch(scope) {
                            case '2': 
                                key   = e.regCode;
                                value = e.regDesc;
                                break;
                            case '3': 
                                key   = e.provCode;
                                value = e.provDesc;
                                break;
                            case '4': 
                            case '5': 
                                key   = e.citymunCode;
                                value = e.provDesc + ' | ' + e.citymunDesc;
                                break;
                            case '6': 
                                key   = e.brgyCode;
                                value = e.brgyDesc;
                                break;
                        }

                        if (key != false) {
                            options += '<option value="' + key + '">' + value + '</option> \n';
                        }
                    });
                    $('#departmentLocation').html(options).prop('disabled', false).select2({
                        width: '100%'
                    });
                    $('#DepartmentLocationFinder').LoadingOverlay("hide");
                });
            }
        } else {
            $('.locationSelector').addClass('hidden');
        }
    }


    /**
    * get city barangay
    */
    this.loadBarangayOptions = function(target, e, selected = false)
    {
        if ($(self.form).find('#departmentLocation').hasClass("select2-hidden-accessible")) {
            $(self.form).find('#departmentLocation').select2('destroy');
        }

        $(target).html(window.emptySelectOption).prop('disabled', true);
        if ($(target).hasClass("select2-hidden-accessible")) {
            $(target).select2('destroy');
        }
        $.get(window.public_url('get/barangay'), {'citymunCode' : $(e).val()}).done(function(response) {
            if (response.status) {
                var options = window.emptySelectOption;
                $.each(response.data, function(i, e){
                    options += '<option value="' + e.brgyCode + '" '+ (selected == e.brgyCode ? 'selected="selected"' : '') +' >' + e.brgyDesc + '</option> \n';
                });
                $(target).html(options).prop('disabled', false).select2({
                    width: '100%'
                });
            } else {
                $(target).html(window.emptySelectOption);
            }
        });
    }


    /**
    * get department locations and officer
    */
    this.getDepartmentLocations = function(form)
    {
        
        // prenvet multiple calls
        if ($(form).data('running')) {
            return false;
        }

        $(form).data('running', true);

        $(form).LoadingOverlay("show");
        var formData = new FormData(form);

        var resultContainer = $('#DepartmentLocationResults');
        resultContainer.LoadingOverlay("show");

        self.officersData = {}; //reset

        $.ajax({
            url: window.base_url('department/get_locations_officers'),
            type: 'POST',
            data: formData,
            success: function (response) {
                if (response.status) {
                    var template = '<table id="tableData" class="table table-hover"> \
                                      <thead> \
                                        <tr> \
                                          <td style="width: 20px;"></td> \
                                          <th>Code</th> \
                                          <th>Name</th> \
                                          <th>Status</th> \
                                          <th>Officers</th> \
                                          <th class="visible-lg">Address</th> \
                                          <th class="hidden-xs hidden-sm">Type</th> \
                                          <th class="c"></th> \
                                        </tr> \
                                      </thead> \
                                      <tbody>';
                    $.each(response.data, function(i,e){

                        if (typeof(e.hideParent) == 'undefined') {
                            var logo        = e.Logo;
                            var locationID  = (e.location != false ? e.location.id : '');
                            var status      = ((e.location != false && e.location.Status == 1) ? 'Enabled' : 'Disabled');
                            var officers    = (e.location != false && e.location.officers != false ? e.location.officers : []);
                            var address     = (e.location != false ? e.location.Address : e.Address);

                            if (locationID) {
                                self.officersData[locationID] = officers;
                            }

                            template += '<tr class="text-left '+ (status == 'Enabled' ? 'bg-aqua disabled color-palette' : 'bg-gray disabled color-palette') +'" \
                                            id="loc_'+ locationID +'" \
                                            data-locid="'+ locationID +'" \
                                            data-deptid="'+ e.id +'" \
                                            data-subdeptid="" \
                                            data-status="'+ ((e.location != false && e.location.Status == 1) ? 1 : 0) +'" \
                                            data-address="'+ (e.location != false ? e.location.Address : '') +'" \
                                            data-contact="'+ (e.location != false ? e.location.Contact : '') +'" \
                                            data-name="'+ e.Name +'" \
                                            data-code="'+ e.Code +'" \
                                            ">';
                                template += '<td><img class="logo-small" src="' + window.public_url() + 'assets/logo/' + logo + '"></td>';
                                template += '<td>' + e.Code + '</td>';
                                template += '<td>' + e.Name + '</td>';
                                template += '<td class="'+ (status == 'Enabled' ? 'text-success' : 'text-default') +'"><b>' + status + '</b></td>';
                                if (status == 'Enabled') {
                                    template += '<td><button class="btn btn-sm btn-default" onClick="Department.showOfficers(this)"><b>' + officers.length + '</b> <i class="fa fa-search"></i></button></td>';
                                } else {
                                    template += '<td>' + officers.length + '</td>';    
                                }
                                template += '<td class="visible-lg">' + address + '</td>';
                                template += '<td class="hidden-xs hidden-sm">Main</td>';
                                template += '<td> \
                                                <div class="box-tools"> \
                                                    <div class="input-group pull-right" style="width: 10px;"> \
                                                      <div class="input-group-btn"> \
                                                        <button type="button" class="btn btn-xs btn-default" onClick="Department.setDepartmentLocation(this)"><i class="fa fa-pencil"></i> Edit</button> \
                                                      </div> \
                                                    </div> \
                                                </div>  \
                                            </td>';
                            template += '</tr>';
                        }

                        $.each(e.subDepartment, function(j,f){
                            var logo        = f.Logo;
                            var locationID  = (f.location != false ? f.location.id : '');
                            var status      = ((f.location != false && f.location.Status == 1) ? 'Enabled' : 'Disabled');
                            var officers    = (f.location != false && f.location.officers != false ? f.location.officers : []);
                            var address     = (f.location != false ? f.location.Address : f.Address);

                            if (locationID) {
                                self.officersData[locationID] = officers;
                            }

                            template += '<tr class="small text-left '+ (status == 'Enabled' ? 'bg-aqua disabled color-palette' : 'bg-gray disabled color-palette') +'" \
                                            id="loc_'+ locationID +'" \
                                            data-locid="'+ locationID +'" \
                                            data-deptid="'+ f.DepartmentID +'" \
                                            data-subdeptid="'+ f.id +'" \
                                            data-status="'+ ((f.location != false && f.location.Status == 1) ? 1 : 0) +'" \
                                            data-address="'+ (f.location != false ? f.location.Address : '') +'" \
                                            data-contact="'+ (f.location != false ? f.location.Contact : '') +'" \
                                            data-name="'+ f.Name +'" \
                                            data-code="'+ f.Code +'" \
                                            ">';
                                template += '<td class="indent-30"><img class="logo-smaller" src="' + window.public_url() + 'assets/logo/' + logo + '"></td>';
                                template += '<td class="indent-30">' + f.Code + '</td>';
                                template += '<td class="indent-30">' + f.Name + '</td>';
                                template += '<td class="'+ (status == 'Enabled' ? 'text-success' : 'text-default') +'"><b>' + status + '</b></td>';
                                if (status == 'Enabled') {
                                    template += '<td><button class="btn btn-sm btn-default" onClick="Department.showOfficers(this)"><b>' + officers.length + '</b> <i class="fa fa-search"></i></button></td>';
                                } else {
                                    template += '<td>' + officers.length + '</td>';    
                                }
                                template += '<td class="visible-lg">' + address + '</td>';
                                template += '<td class="hidden-xs hidden-sm">' + $global.department_type[f.Type] + '</td>';
                                template += '<td> \
                                                <div class="box-tools"> \
                                                    <div class="input-group pull-right" style="width: 10px;"> \
                                                      <div class="input-group-btn"> \
                                                        <button type="button" class="btn btn-xs btn-default" onClick="Department.setDepartmentLocation(this)"><i class="fa fa-pencil"></i> Edit</button> \
                                                      </div> \
                                                    </div> \
                                                </div>  \
                                            </td>';
                            template += '</tr>';
                        });

                    });

                    template += '</tbody></table>';

                    // record scope and location code
                    template += '<input type="hidden" id="selectedScope" value="'+$(form).find('#departmentScope').val()+'">';
                    template += '<input type="hidden" id="selectedLocation" value="'+$(form).find('#departmentLocation').val()+'">';

                    resultContainer.html(template);

                    if ($('#DepartmentLocationFinder #keyword_search').val().trim() != '') {
                        Utils.highlightMatch(resultContainer.find('#tableData tbody'), $('#DepartmentLocationFinder #keyword_search').val());
                    }
                } else {
                    resultContainer.html('<h4>' + response.message + '</h4>');
                }
            },
            complete: function() {
                $(form).LoadingOverlay("hide");
                resultContainer.LoadingOverlay("hide");
                $(form).data('running', false);
            },
            cache: false,
            contentType: false,
            processData: false
        });

    }


    /**
    * department location scope setup
    */
    this.setDepartmentLocation = function(element)
    {

        var scope         = $('#DepartmentLocationResults #selectedScope').val();
        var location_code = $('#DepartmentLocationResults #selectedLocation').val();
        var data          = $(element).closest('tr').data();

        // reset form data
        $('#DepartmentLocationForm').trigger("reset");

        // reset input erros
        $.each($('#DepartmentLocationForm').find('input, select'), function(i,e){
            $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger').addClass('text-white');
            $(e).popover('destroy');
        });
        //clean error box
        $('#DepartmentLocationForm').find('#error_message_box .error_messages').html('');
        $('#DepartmentLocationForm').find('#error_message_box').addClass('hide');

        $('#DepartmentLocationForm #id').val(data.locid);
        $('#DepartmentLocationForm #DepartmentID').val(data.deptid);
        $('#DepartmentLocationForm #SubDepartmentID').val(data.subdeptid);
        $('#DepartmentLocationForm #LocationScope').val(scope);
        $('#DepartmentLocationForm #LocationCode').val(location_code);

        $('#DepartmentLocationForm #LocationStatus').val(data.status);
        $('#DepartmentLocationForm #Address').val(data.address);
        $('#DepartmentLocationForm #Contact').val(data.contact);

        $('#departmentLocationModal .forDepartment').text(data.code + ' | ' + data.name);
        $('#departmentLocationModal').modal({
            backdrop : 'static',
            keyboard : false
        });

    }

    /**
    * save department scope location
    */
    this.saveDepartmentLocation = function(form)
    {

        // prenvet multiple calls
        if ($(form).data('running')) {
            return false;
        }

        $(form).data('running', true);
        $(form).find('input').blur();
        $(form).LoadingOverlay("show");

        var formData = new FormData(form);
        
        // reset input erros
        $.each($(form).find('input, select'), function(i,e){
            $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger').addClass('text-white');
            $(e).popover('destroy');
        });
        //clean error box
        $(form).find('#error_message_box .error_messages').html('');
        $(form).find('#error_message_box').addClass('hide');

        $.ajax({
            url: $(form).prop('action'),
            type: 'POST',
            data: formData,
            success: function (response) {
                if (response.status) {
                    self.getDepartmentLocations($('#DepartmentLocationFinder')[0]);
                    $('#departmentLocationModal').modal('hide');
                } else {
                    // bootbox.alert(response.message);
                    $(form).find('#error_message_box .error_messages').append('<p><b>' + response.message + '</b></p>');

                    $.each(response.fields, function(i,e){
                        $(form).find('#'+i).prop('title', e).closest('div').addClass('has-error').find('label').removeClass('text-white').addClass('text-danger');
                        Utils.popover($('#'+i), {
                            t: 'hover',
                            p: 'top',
                            m: e
                        });
                        $(form).find('#error_message_box .error_messages').append('<p>' + e + '</p>');
                    });

                    $(form).find('#error_message_box').removeClass('hide');
                }
            },
            complete: function() {
                $(form).LoadingOverlay("hide");
                $(form).data('running', false);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }

    /**
    * show and manage department officers
    */
    this.showOfficers = function(element)
    {
        
        self.officerChanged = false;

        var data          = $(element).closest('tr').data();
        var scope         = $('#DepartmentLocationFinder #departmentScope option:selected').text();
        var location      = $('#DepartmentLocationFinder #departmentLocation option:selected').text();

        $('#departmentOfficersListModal .departmentName').text(data.name);
        $('#departmentOfficersListModal .officerScope').text(scope);
        $('#departmentOfficersListModal .officerLocation').text(location);

        $('#departmentOfficersListModal #DepartmentLocationID').val(data.locid);

        var currentOfficers = self.officersData[data.locid];

        if (currentOfficers.length > 0) {
            $('#departmentOfficersListModal #result_message').text('').addClass('hidden');
            var template = '';
            $.each(currentOfficers, function(i,e){
                template += '<tr id="officer_row_'+e.AccountID+'_'+e.FunctionTypeID+'" data-id="'+e.id+'">';
                template += '<td><img class="logo-small" src="' + window.public_url() + 'assets/profile/' + (e.Photo != '' ? e.Photo : 'avatar_default.jpg') + '"></td>';
                template += '<td>' + e.MabuhayID + '</td>';
                template += '<td>' + e.FirstName + ' ' + e.LastName + '</td>';
                template += '<td><span class="officerFunction" data-pk="'+e.id+'">' + (e.FunctionTypeID > 0 ? $global.department_function_type[e.FunctionTypeID] : '') + '</span></td>';
                template += '<td><span class="officerPosition" data-pk="'+e.id+'">' + e.Position + '</span></td>';
                template += '<td>' + e.ContactNumber + '</td>';
                // template += '<td>' + e.Address + '</td>';
                template += '<td> \
                                <div class="box-tools"> \
                                    <div class="input-group pull-right" style="width: 10px;"> \
                                      <div class="input-group-btn"> \
                                        <button type="button" class="btn btn-xs btn-default" onClick="Department.removeOfficer(this)"><i class="fa fa-trash"></i></button> \
                                      </div> \
                                    </div> \
                                </div>  \
                            </td>';
                template += '</tr>';
            });
            $('#departmentOfficersListModal #tableBody').html(template);
            self.setEditableFunction($('#departmentOfficersListModal .officerFunction'));
            self.setEditablePosition($('#departmentOfficersListModal .officerPosition'));

            $('#departmentOfficersListModal #tableData').removeClass('hidden');
        } else {
            $('#departmentOfficersListModal #tableBody').html('');
            $('#departmentOfficersListModal #tableData').addClass('hidden');
            $('#departmentOfficersListModal #result_message').text('No officer assigned.').removeClass('hidden');
            
        }

        $('#departmentOfficersListModal').modal({
            backdrop : 'static',
            keyboard : false
        });

        $('#departmentOfficersListModal').on('hide.bs.modal', function (e) {
            // refresh list
            if (self.officerChanged) {
                self.getDepartmentLocations($('#DepartmentLocationFinder')[0]);
            }
        });
    }

    /**
    * set function editable
    */
    this.setEditableFunction = function($element)
    {
        $element.editable({
            type: 'select',
            source: Object.assign({0: ''}, $global.department_function_type),
            url: window.base_url('department/set_officer_function'),
            title: 'Function',
            emptytext : 'not set',
            success : function(response) {
                self.officerChanged = true;
            }
        });
    }

    /**
    * set position editable
    */
    this.setEditablePosition = function($element)
    {
        $element.editable({
            type: 'text',
            url: window.base_url('department/set_officer_position'),
            title: 'Position',
            emptytext : 'not set',
            success : function(response) {
                self.officerChanged = true;
            }
        });
    }

    /**
    * add department officer
    */
    this.addOfficer = function(element)
    {
        var row   = $(element).closest('tr');
        var locID = $('#departmentOfficersListModal #DepartmentLocationID').val();
        var data  = $('#DepartmentLocationResults #tableData tr#loc_' + locID).data();

        // reset form data
        $('#DepartmentOfficerForm').trigger("reset");

        // reset input erros
        $.each($('#DepartmentOfficerForm').find('input, select'), function(i,e){
            $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger').addClass('text-white');
            $(e).popover('destroy');
        });
        //clean error box
        $('#DepartmentOfficerForm').find('#error_message_box .error_messages').html('');
        $('#DepartmentOfficerForm').find('#error_message_box').addClass('hide');

        $('#DepartmentOfficerForm #DepartmentID').val(data.deptid);
        $('#DepartmentOfficerForm #SubDepartmentID').val(data.subdeptid);
        $('#DepartmentOfficerForm #DepartmentLocationID').val(locID);

        $('#departmentOfficerFormModal .forDepartment').text(data.code + ' | ' + data.name);

        var typeaheadTimeout;
        $('#DepartmentOfficerForm #accountFinder').typeahead({
            hint: false,
            minLength: 5,
        },
        {
            templates: {
                empty: [
                    '<div class="padding-left-10 empty-message">',
                      'Record not found.',
                    '</div>'
                ].join('\n'),
                suggestion: function (item) {
                    // return "<p><img style='width:40px;height:40px;margin: 0 auto;' src='" + window.public_url() + "assets/profile/"+item.photo+"' /> " + item.mabuhayID + " | " + item.fullname + "</p>";
                    return '<div class="row gutter-0">' +
                                '<div class="col-sm-12 col-lg-2">' +
                                    '<span>' +
                                    '<img style="width:50px;height:50px;margin:0 auto;" src="' + window.public_url() + "assets/profile/"+item.photo+'">' +
                                    '</span></div>' +
                                '<div class="col-sm-12 col-lg-10"><span>'+ item.mabuhayID +' - '+ item.fullname +
                                    '</br><small>'+ item.aclevel +'</small></span>' +
                                '</div>' +
                            '</div>';
                }
            },
            name: 'officers',
            display: 'fullname',
            source: function(query, syncResults, asyncResults) {
                try {clearTimeout(typeaheadTimeout);} catch (e) {}
                typeaheadTimeout = setTimeout(function (){
                    $.get(window.base_url('department/find_officer') + "?q=" + query, function(responseData) {
                        asyncResults(responseData);
                    });
                }, 500);
            }
        }).bind('typeahead:select', function(ev, item) {
            $('#DepartmentOfficerForm .photo').prop('src', window.public_url() + "assets/profile/" + item.photo);
            $('#DepartmentOfficerForm .accountInfo').html(
                '<h5><b>ID: </b> ' + item.mabuhayID + '</h5>' + 
                '<h5><b>Name: </b> <span class="selected-fullename">' + item.fullname + '</span></h5>' +
                '<h5><b>Level: </b> ' + item.aclevel + '</h5>' +
                '<h5><b>Address: </b> ' + item.address.join(', ') + '</h5>' +
                '<h5><b>Contact: </b> ' + item.contact + '</h5>'
            );
            $('#DepartmentOfficerForm .positionBox').removeClass('hidden');
            $('#DepartmentOfficerForm #SelectedAccountID').val(item.id);
        }).bind('typeahead:change', function(ev, value) {
          // finder value was changed
          if (value != $('#DepartmentOfficerForm .selected-fullename').text()) {
            // clear selected
            $('#DepartmentOfficerForm .positionBox').addClass('hidden');
            $('#DepartmentOfficerForm .accountInfo').html('');
            $('#DepartmentOfficerForm .photo').prop('src', '');
            $('#DepartmentOfficerForm #SelectedAccountID').val('');
          }
        });

        $('#departmentOfficerFormModal').modal({
            backdrop : 'static',
            keyboard : false
        });

        $('#departmentOfficerFormModal').on('hide.bs.modal', function (e) {
            $('#DepartmentOfficerForm #accountFinder').typeahead('destroy');
            $('#DepartmentOfficerForm .positionBox').addClass('hidden');
            $('#DepartmentOfficerForm .accountInfo').html('');
            $('#DepartmentOfficerForm .photo').prop('src', '');
            $('#DepartmentOfficerForm #SelectedAccountID').val('');
        });
    }

    /**
    * save department officer
    */
    this.saveDepartmentOfficer = function(form)
    {
        var selectedAccountID = $('#DepartmentOfficerForm #SelectedAccountID').val();
        var selectedFunctionID = $('#DepartmentOfficerForm #FunctionTypeID').val();
        if ($('#departmentOfficersListModal #officer_row_' + selectedAccountID+'_'+selectedFunctionID).length) {
            bootbox.alert('Officer is already assigned on this department with the same function.');
        } else {

            // prenvet multiple calls
            if ($(form).data('running')) {
                return false;
            }

            $(form).data('running', true);
            $(form).find('input').blur();
            $(form).LoadingOverlay("show");

            var formData = new FormData(form);
            
            // reset input erros
            $.each($(form).find('input, select'), function(i,e){
                $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger').addClass('text-white');
                $(e).popover('destroy');
            });
            //clean error box
            $(form).find('#error_message_box .error_messages').html('');
            $(form).find('#error_message_box').addClass('hide');

            $.ajax({
                url: $(form).prop('action'),
                type: 'POST',
                data: formData,
                success: function (response) {
                    if (response.status) {
                        $('#departmentOfficersListModal #result_message').text('').addClass('hidden');
                        $.each(response.data, function(i,e){
                            var row_id = e.AccountID+'_'+e.FunctionTypeID;
                            var template = '';
                                template += '<tr id="officer_row_'+row_id+'" data-id="'+e.id+'">';
                                template += '<td><img class="logo-small" src="' + window.public_url() + 'assets/profile/' + (e.Photo != '' ? e.Photo : 'avatar_default.jpg') + '"></td>';
                                template += '<td>' + e.MabuhayID + '</td>';
                                template += '<td>' + e.FirstName + ' ' + e.LastName + '</td>';
                                template += '<td><span class="officerFunction" data-pk="'+e.id+'">' + (e.FunctionTypeID > 0 ? $global.department_function_type[e.FunctionTypeID] : '') + '</span></td>';
                                template += '<td><span class="officerPosition" data-pk="'+e.id+'">' + e.Position + '</span></td>';
                                template += '<td>' + e.ContactNumber + '</td>';
                                // template += '<td>' + e.Address + '</td>';
                                template += '<td> \
                                                <div class="box-tools"> \
                                                    <div class="input-group pull-right" style="width: 10px;"> \
                                                      <div class="input-group-btn"> \
                                                        <button type="button" class="btn btn-xs btn-default" onClick="Department.removeOfficer(this)"><i class="fa fa-trash"></i></button> \
                                                      </div> \
                                                    </div> \
                                                </div>  \
                                            </td>';
                                template += '</tr>';
                            $('#departmentOfficersListModal #tableBody').append(template);
                            self.setEditableFunction($('#departmentOfficersListModal #officer_row_'+row_id+' .officerFunction'));
                            self.setEditablePosition($('#departmentOfficersListModal #officer_row_'+row_id+' .officerPosition'));
                        });
                        self.officerChanged = true;
                        $('#departmentOfficersListModal #tableData').removeClass('hidden');
                        $('#departmentOfficerFormModal').modal('hide');
                    } else {
                        // bootbox.alert(response.message);
                        $(form).find('#error_message_box .error_messages').append('<p><b>' + response.message + '</b></p>');

                        $.each(response.fields, function(i,e){
                            $(form).find('#'+i).prop('title', e).closest('div').addClass('has-error').find('label').removeClass('text-white').addClass('text-danger');
                            Utils.popover($('#'+i), {
                                t: 'hover',
                                p: 'top',
                                m: e
                            });
                            $(form).find('#error_message_box .error_messages').append('<p>' + e + '</p>');
                        });

                        $(form).find('#error_message_box').removeClass('hide');
                    }
                },
                complete: function() {
                    $(form).LoadingOverlay("hide");
                    $(form).data('running', false);
                },
                cache: false,
                contentType: false,
                processData: false
            });

        }
    }

    /*
    * remove officer from department
    */
    this.removeOfficer = function(element)
    {   
        var row   = $(element).closest('tr');
        var id    = row.data('id');
        var name  = row.find('td:nth-child(3)').text();
        var locID = $('#departmentOfficersListModal #DepartmentLocationID').val();

        bootbox.confirm('Are you sure you want to remove ' + name + ' from the list of officers?' , function(result){
            if (result) {
                $.get(window.base_url('department/remove_officer'), {'locid' : locID, 'o' : id}).done(function(response) {
                    if (response.status) {
                        row.fadeOut('fast', function(){
                            row.remove();
                            if (!$('#departmentOfficersListModal #tableBody tr').length) {
                                $('#departmentOfficersListModal #tableData').addClass('hidden');
                                $('#departmentOfficersListModal #result_message').text('No more officer assigned').removeClass('hidden');
                            }
                        });
                        self.officerChanged = true;
                    } else {
                        bootbox.alert(response.message);
                    }
                });
            }
        });
    }


    /**
    * ORGANIZATION SETUP
    */
    this.organizationSetup = function(parentID, subID)
    {   
        var data        = this.getSubDepartment(parentID, subID);
        var parentData  = this.getDepartment(parentID);

        if (data != false) {

            self.categorychanged = false;

            // reset form data
            $('#organizationSetupForm').trigger("reset");

            // reset input erros
            $.each($('#organizationSetupForm').find('input'), function(i,e){
                $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger').addClass('text-white');
                $(e).popover('destroy');
            });
            //clean error box
            $('#organizationSetupForm').find('#error_message_box .error_messages').html('');
            $('#organizationSetupForm').find('#error_message_box').addClass('hide');

            $('#organizationSetupForm').find('#addedBanners').html('');
            $('#organizationSetupForm').find('#addedPartners').html('');
            $('#organizationSetupForm').find('ul.category-list').html('');

            $('a[href="#banners"]').tab('show');

            if (data.OrganizationSetup) {
                var v = data.OrganizationSetup;

                // banners
                $.each(v.Banners, function (i,e){
                    var tpl = `'<tr class="sortable-row" id="${i}">
                                    <td><i class="drag-handle fa fa-arrows"></i></td>
                                    <td>
                                        <div class="banner image-upload-container">
                                          <img class="image-preview img-responsive" src="${window.public_url('assets/etc/' + e.Photo)}">
                                          <span class="hiddenFileInput hide">
                                            <input type="file" accept="image/*" data-default="${window.public_url('assets/etc/placeholder-banner.png')}" class="image-upload-input fieldBanner" name="Images[${i}]"/>
                                          </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" name="Banners[${i}][URL]" class="form-control fieldUrl input-sm" placeholder="Link URL" value="${e.URL}">
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" onClick="Department.removeBannerRow(this)"><i class="fa fa-trash"></i></button>
                                        <input type="hidden" class="item-order" name="Banners[${i}][Ordering]" value="${e.Ordering}">
                                    </td>
                                </tr>'`;

                    $('#organizationSetupForm').find('#addedBanners').append(tpl);
                });

                // partners
                $.each(v.Partners, function (i,e){
                    var tpl = `'<tr class="sortable-row" id="${i}">
                                    <td><i class="drag-handle fa fa-arrows"></i></td>
                                    <td>
                                        <div class="image-upload-container">
                                          <img class="image-preview img-responsive" src="${window.public_url('assets/etc/' + e.Photo)}">
                                          <span class="hiddenFileInput hide">
                                            <input type="file" accept="image/*" data-default="${window.public_url('assets/logo/blank-logo.png')}" class="image-upload-input fieldLogo" name="Images[${i}]"/>
                                          </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" name="Partners[${i}][Name]" class="form-control fieldName input-sm" placeholder="Name" value="${e.Name}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" name="Partners[${i}][URL]" class="form-control fieldUrl input-sm" placeholder="Link URL" value="${e.URL}">
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" onClick="Department.removePartnerRow(this)"><i class="fa fa-trash"></i></button>
                                        <input type="hidden" class="item-order" name="Partners[${i}][Ordering]" value="${e.Ordering}">
                                    </td>
                                </tr>'`;

                    $('#organizationSetupForm').find('#addedPartners').append(tpl);
                });

                $.each(v.Categories, function(i, e){
                    var checked = (e.Status == 1 ? 'checked' : '');
                    var tpl = `<li id="category-item-${e.id}" class="list-group-item">
                                <div class="row gutter-0">
                                  <div class="col-xs-8 catname">${e.Name}</div>
                                  <div class="col-xs-4 text-right">
                                    <input class="categoryStatusToggle" type="checkbox" ${checked}
                                      data-code="${e.id}"
                                      data-toggle="toggle" 
                                      data-on="Active" 
                                      data-off="Disabled" 
                                      data-size="mini" 
                                      data-width="60">
                                      
                                    <a href="javascript:;" class="btn btn-xs btn-warning pull-right offset-left-5" onClick="Department.editCategory(${e.id}, this)"><i class="fa fa-pencil"></i></a>
                                  </div>
                                </div>
                              </li>`;

                    $('#organizationSetupForm').find('ul.category-list').append(tpl);
                });


                self.setSortable('addedBanners');
                self.setSortable('addedPartners');
            }

            // banners
            var itemID = Utils.generateString();
            var tpl = `<tr class="info" id="${itemID}">
                          <td></td>
                          <td>
                              <div class="banner image-upload-container">
                                <img class="image-preview img-responsive" src="${window.public_url('assets/etc/placeholder-banner.png')}">
                                <span class="hiddenFileInput hide">
                                  <input type="file" accept="image/*" data-default="${window.public_url('assets/etc/placeholder-banner.png')}" class="image-upload-input fieldBanner" name="Images[${itemID}]"/>
                                </span>
                              </div>
                          </td>
                          <td>
                              <div class="form-group">
                                  <input type="text" name="Banners[${itemID}][URL]" class="form-control fieldUrl input-sm" placeholder="Link URL">
                              </div>
                          </td>
                          <td>
                              <button type="button" class="btn btn-success btn-sm" onclick="Department.addBannerRow()"><i class="fa fa-plus"></i></button>
                          </td>
                      </tr>`;

            $('#organizationSetupForm').find('#addedBanners').append(tpl);

            // partners
            var itemID = Utils.generateString();
            var tpl2 = `<tr class="info" id="${itemID}">
                          <td></td>
                          <td>
                              <div class="image-upload-container">
                                <img class="image-preview img-responsive" src="${window.public_url('assets/logo/blank-logo.png')}">
                                <span class="hiddenFileInput hide">
                                  <input type="file" accept="image/*" data-default="${window.public_url('assets/logo/blank-logo.png')}" class="image-upload-input fieldLogo" name="Images[${itemID}]"/>
                                </span>
                              </div>
                          </td>
                          <td>
                              <div class="form-group">
                                  <input type="text" name="Partners[${itemID}][Name]" class="form-control fieldName input-sm" placeholder="Name">
                              </div>
                          </td>
                          <td>
                              <div class="form-group">
                                  <input type="text" name="Partners[${itemID}][URL]" class="form-control fieldUrl input-sm" placeholder="Link URL">
                              </div>
                          </td>
                          <td>
                              <button type="button" class="btn btn-success btn-sm" onclick="Department.addPartnerRow()"><i class="fa fa-plus"></i></button>
                          </td>
                      </tr>`;

            $('#organizationSetupForm').find('#addedPartners').append(tpl2);

            $('#organizationSetupForm #DepartmentID').val(parentID);
            $('#organizationSetupForm #SubDepartmentID').val(subID);
            $('#organizationSetupForm #UniqueCode').val(data.UniqueCode);

            $('#organizationSetupModal .departmentName').text(data.Name);
            $('#organizationSetupModal').modal({
                backdrop : 'static',
                keyboard : false
            });


            // on modal events

            $('#organizationSetupModal').on('hide.bs.modal', function (e) {
                // refresh list
                if (self.categorychanged) {
                    location.reload();
                }
            });

            $('a[data-toggle="tab"]').off('shown.bs.tab').on('shown.bs.tab', function (e) {
              var target = $(e.target).attr("href") // activated tab
              if (target == '#categories') {
                $('#organizationSetupModal .modal-footer .submitb').hide();
              } else {
                $('#organizationSetupModal .modal-footer .submitb').show();
              }
            });

            $('.categoryStatusToggle').bootstrapToggle();

            $('.categoryStatusToggle').off('change').on('change', function(e) {
                self.updateOrganizationCategoryStatus(this);
            });

        }

    }

    /**
    * add banner
    */
    this.addBannerRow = function()
    {
        var form        = $('#organizationSetupForm').find('#addedBanners tr:last');
        var count       = ($('#organizationSetupForm').find('#addedBanners tr').length - 1);
        var banner      = form.find('.fieldBanner').val();

        if (banner) {
            var clone  = form.clone();
            var itemID = Utils.generateString();
            form.removeClass('info').addClass('sortable-row').find('td:first-child').prepend('<i class="drag-handle fa fa-arrows"></i>');
            form.find('td:last-child').html('<button type="button" class="btn btn-danger btn-sm" onClick="Department.removeBannerRow(this)"><i class="fa fa-trash"></i></button> \
                <input type="hidden" class="item-order" name="Banners['+form.prop('id')+'][Ordering]" value="'+(count + 1)+'">');

            clone.prop('id', itemID).addClass('info');
            clone.find('.fieldUrl').prop('name', 'Banners['+itemID+'][URL]').val('');
            clone.find('.fieldBanner').prop('name', 'Images['+itemID+']').val('').closest('div').find('.image-preview').prop('src', window.public_url('assets/etc') + '/placeholder-banner.png');
            $('#organizationSetupForm').find('#addedBanners').append(clone);

            self.setSortable('addedBanners');
        }
    }

    /**
    * remove banner row
    */
    this.removeBannerRow = function(elem)
    {
        var rowID   = $(elem).closest("tr").prop('id');
        $(elem).closest("tr").fadeOut('fast', function(){
            $(this).remove();
            self.setSortable('addedBanners');
        });
    }

    /**
    * add partner
    */
    this.addPartnerRow = function()
    {
        var form        = $('#organizationSetupForm').find('#addedPartners tr:last');
        var count       = ($('#organizationSetupForm').find('#addedPartners tr').length - 1);
        var logo      = form.find('.fieldLogo').val();

        if (logo) {
            var clone  = form.clone();
            var itemID = Utils.generateString();
            form.removeClass('info').addClass('sortable-row').find('td:first-child').prepend('<i class="drag-handle fa fa-arrows"></i>');
            form.find('td:last-child').html('<button type="button" class="btn btn-danger btn-sm" onClick="Department.removePartnerRow(this)"><i class="fa fa-trash"></i></button> \
                <input type="hidden" class="item-order" name="Partners['+form.prop('id')+'][Ordering]" value="'+(count + 1)+'">');

            clone.prop('id', itemID).addClass('info');
            clone.find('.fieldName').prop('name', 'Partners['+itemID+'][Name]').val('');
            clone.find('.fieldUrl').prop('name', 'Partners['+itemID+'][URL]').val('');
            clone.find('.fieldLogo').prop('name', 'Images['+itemID+']').val('').closest('div').find('.image-preview').prop('src', window.public_url('assets/logo') + '/blank-logo.png');
            $('#organizationSetupForm').find('#addedPartners').append(clone);

            self.setSortable('addedPartners');
        }
    }

    /**
    * remove partner row
    */
    this.removePartnerRow = function(elem)
    {
        var rowID   = $(elem).closest("tr").prop('id');
        $(elem).closest("tr").fadeOut('fast', function(){
            $(this).remove();
            self.setSortable('addedPartners');
        });
    }


    /**
    * save organization
    */
    this.saveOrganizationSetup = function(form)
    {
        // prenvet multiple calls
        if ($(form).data('running')) {
            return false;
        }

        $(form).data('running', true);
        $(form).find('input').blur();
        $(form).LoadingOverlay("show");

        var formData = new FormData(form);
        
        // reset input erros
        $.each($(form).find('input, select'), function(i,e){
            $(e).prop('title', '');
            $(e).popover('destroy');
        });
        //clean error box
        $(form).find('#error_message_box .error_messages').html('');
        $(form).find('#error_message_box').addClass('hide');
        $(form).find('.has-error').prop('title', '').popover('destroy').removeClass('has-error').find('label').removeClass('text-danger');

        $.ajax({
            url: $(form).prop('action'),
            type: 'POST',
            data: formData,
            success: function (response) {
                if (response.status) {
                    bootbox.alert(response.message, function(){
                        location.reload();
                    });
                } else {
                    // bootbox.alert(response.message);
                    $(form).find('#error_message_box .error_messages').append('<p><b>' + response.message + '</b></p>');

                    $.each(response.fields, function(i,e){
                        $(form).find('#'+i).prop('title', e).closest('div').addClass('has-error').find('label').addClass('text-danger');
                        Utils.popover($('#'+i), {
                            t: 'hover',
                            p: 'top',
                            m: e
                        });
                        $(form).find('#error_message_box .error_messages').append('<p>' + e + '</p>');
                    });

                    $(form).find('#error_message_box').removeClass('hide');
                }
            },
            complete: function() {
                $(form).LoadingOverlay("hide");
                $(form).data('running', false);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }


        /**
    * set group sortable
    */
    this.setSortable = function(elementID)
    {
        try {self.sortable[elementID].destroy();} catch(e) {}

        var container = document.getElementById(elementID);

        // refresh order on remove
        var items = $('#' + elementID + ' > .sortable-row');
        for (var i = 0; i < items.length; i++) {
            $(items[i]).data('order', (i + 1));
            $(items[i]).find('.item-order').first().val(i + 1);
        }

        self.sortable[elementID] = Sortable.create(container, {
            animation: 150,
            handle: '.drag-handle',
            draggable: ".sortable-row",
            onSort: function (e) {
                var items = e.to.children;
                for (var i = 0; i < items.length; i++) {
                    $(items[i]).data('order', (i + 1));
                    $(items[i]).find('.item-order').first().val(i + 1);
                }
            }
        });

    }

    /**
    * ORGANIZATION CATEGORY
    */
    this.addOrgCategory = function()
    {
        // reset form data
        $('#organizationCategoryForm').trigger("reset");

        // reset input erros
        $.each($('#organizationCategoryForm').find('input'), function(i,e){
            $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger').addClass('text-white');
            $(e).popover('destroy');
        });
        //clean error box
        $('#organizationCategoryForm').find('#error_message_box .error_messages').html('');
        $('#organizationCategoryForm').find('#error_message_box').addClass('hide');

        $('#organizationCategoryForm #id').val('');
        $('#organizationCategoryForm #orgcode').val($('#organizationSetupForm #UniqueCode').val());

        $('#organizationCategoryModal .modal-title').html('<b>Category</b> | Add');
        $('#organizationCategoryModal').modal({
            backdrop : 'static',
            keyboard : false
        });
    }

    this.editCategory = function(id, elem)
    {
        // reset form data
        $('#organizationCategoryForm').trigger("reset");

        // reset input erros
        $.each($('#organizationCategoryForm').find('input'), function(i,e){
            $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger').addClass('text-white');
            $(e).popover('destroy');
        });
        //clean error box
        $('#organizationCategoryForm').find('#error_message_box .error_messages').html('');
        $('#organizationCategoryForm').find('#error_message_box').addClass('hide');

        $('#organizationCategoryForm #id').val(id);
        $('#organizationCategoryForm #Name').val($(elem).closest('li').find('.catname').text());
        $('#organizationCategoryForm #orgcode').val($('#organizationSetupForm #UniqueCode').val());

        $('#organizationCategoryModal .modal-title').html('<b>Category</b> | Edit');
        $('#organizationCategoryModal').modal({
            backdrop : 'static',
            keyboard : false
        });
    }

    this.saveOrganizationCategory = function(form) 
    {
        // prenvet multiple calls
        if ($(form).data('running')) {
            return false;
        }

        $(form).data('running', true);
        $(form).find('input').blur();
        $(form).LoadingOverlay("show");

        var formData = new FormData(form);
        
        // reset input erros
        $.each($(form).find('input, select'), function(i,e){
            $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger').addClass('text-white');
            $(e).popover('destroy');
        });
        //clean error box
        $(form).find('#error_message_box .error_messages').html('');
        $(form).find('#error_message_box').addClass('hide');

        $.ajax({
            url: $(form).prop('action'),
            type: 'POST',
            data: formData,
            success: function (response) {
                if (response.status) {
                    self.categorychanged = true;
                    if (response.type == 'new') {
                        var e = response.data;
                        var tpl = `<li id="category-item-${e.id}" class="list-group-item">
                                <div class="row gutter-0">
                                  <div class="col-xs-8 catname">${e.Name}</div>
                                  <div class="col-xs-4 text-right">
                                    <input class="categoryStatusToggle" type="checkbox" checked
                                      data-code="${e.id}"
                                      data-toggle="toggle" 
                                      data-on="Active" 
                                      data-off="Disabled" 
                                      data-size="mini" 
                                      data-width="60">
                                      
                                    <a href="javascript:;" class="btn btn-xs btn-warning pull-right offset-left-5" onClick="Department.editCategory(${e.id}, this)"><i class="fa fa-pencil"></i></a>
                                  </div>
                                </div>
                              </li>`;

                        $('#organizationSetupForm').find('ul.category-list').append(tpl);
                        $("#category-item-" + e.id + ' .categoryStatusToggle').bootstrapToggle();
                        $("#category-item-" + e.id + ' .categoryStatusToggle').off('change').on('change', function(e) {
                            self.updateOrganizationCategoryStatus(this);
                        });

                    } else {
                        $('#category-item-' + response.data.id).find('.catname').text(response.data.Name);
                    }
                    $('#organizationCategoryModal').modal('hide');
                } else {
                    // bootbox.alert(response.message);
                    $(form).find('#error_message_box .error_messages').append('<p><b>' + response.message + '</b></p>');

                    $.each(response.fields, function(i,e){
                        $(form).find('#'+i).prop('title', e).closest('div').addClass('has-error').find('label').removeClass('text-white').addClass('text-danger');
                        Utils.popover($('#'+i), {
                            t: 'hover',
                            p: 'top',
                            m: e
                        });
                        $(form).find('#error_message_box .error_messages').append('<p>' + e + '</p>');
                    });

                    $(form).find('#error_message_box').removeClass('hide');
                }
            },
            complete: function() {
                $(form).LoadingOverlay("hide");
                $(form).data('running', false);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }

    this.updateOrganizationCategoryStatus = function(elem)
    {
        var checkbox    = $(elem);
        var data        = checkbox.data();
        var status      = checkbox.is(":checked");
        $.ajax({
            url: window.base_url('department/orgcategorystatus'),
            type: 'post',
            data: {
                'status' : status, 
                'id' : data.code,
                'code' : $('#organizationSetupForm #UniqueCode').val()
            },
            success: function (response) {
                self.categorychanged = true;
                if (!response.status) {
                    // failed
                    bootbox.alert(response.message, function(){
                        location.reload();
                    })
                }
            }
        });
    }

}


var Department = new Department();
$(document).ready(function(){
    Department._init();
});