/**
* This is different from account module that handle authentication
*/ 

function Accounts() {

    // because this is overwritten on jquery events
    var self = this;

    // initialize module variables
    this.accountData = {};
    this.accountLevels = {};

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
        $('#AccountApprovalForm').submit(function(e) {
            e.preventDefault();
            self.approveAccount(this);
        });

        $('#AccountForm').submit(function(e) {
            e.preventDefault();
            self.updateAccount(this);
        });
    }

    /**
    * usage of different libraries and plugins
    */
    this.set_configs = function()
    {
        $('#search_account_city').select2({
            width: '100%'
        });
    }

    this.setData = function(rawData)
    {
        this.accountData = rawData;
    }

    this.getAccount = function(id)
    {   

        var match = false;
        $.each(self.accountData, function(i,e){
            if (e.id == id) {
                match = e;
                return false;
            }
        });

        return match;
    }


    /**
    * show account details/edit modal
    */
    this.editAccount = function(id)
    {
        var data = self.getAccount(id);

        if (data != false) {

            // reset form data
            $('#AccountForm').trigger("reset");

            // reset input erros
            $.each($('#AccountForm').find('input, select'), function(i,e){
                $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger').addClass('text-white');
                $(e).popover('destroy');
            });

            //clean error box
            $('#AccountForm').find('#error_message_box .error_messages').html('');
            $('#AccountForm').find('#error_message_box').addClass('hide');

            // set data
            $('#AccountForm .mabuhay-id').text(data.mabuhay_id);
            $('#AccountForm #id').val(data.id);
            $('#AccountForm .image-preview').prop('src',window.public_url() + "assets/profile/" + data.photo + "?" + Date.now());
            $('#AccountForm #FirstName').val(data.firstname);
            $('#AccountForm #LastName').val(data.lastname);
            $('#AccountForm #MiddleName').val(data.middlename);
            $('#AccountForm #GenderID').val(data.gender_id);
            $('#AccountForm #BirthDate').val(data.birthdate);
            $('#AccountForm #ContactNumber').val(data.contact);
            $('#AccountForm #EmailAddress').val(data.email);
            $('#AccountForm #MaritalStatusID').val(data.marital_id);
            $('#AccountForm #LivelihoodStatusID').val(data.livelihood_id);
            $('#AccountForm #EducationalAttainmentID').val(data.education_id);
            $('#AccountForm #StreetPhase').val(data.street);
            $('#AccountForm #OrganizationID').val(data.organization);

            $('#AccountForm #MunicipalityCityID').val(data.city_id);
            self.loadBarangayOptions('#AccountForm #BarangayID', '#AccountForm #MunicipalityCityID', data.barangay_id);
            $('#AccountForm #BarangayID').val(data.barangay_id);
            // if (typeof(data.address.Barangay) != 'undefined') {
            //     $('#AccountForm #BarangayID').html('<option value="'+data.barangay_id+'">' + data.address.Barangay + '</option>');
            // }

            if (data.a_status_id == 0) {
                // exclude if status is pending
                $('#AccountForm #AccountTypeID').val(data.a_type_id).prop('disabled', true);
                $('#AccountForm #AccountLevelID').val(data.a_level_id).prop('disabled', true);
                $('#AccountForm #StatusID').val(data.a_status_id).prop('disabled', true);
                $('#AccountForm #account-level-container').hide();
            } else {
                $('#AccountForm #AccountTypeID').val(data.a_type_id).prop('disabled', false);

                self.setLevelOptions('#AccountForm #AccountTypeID');

                $('#AccountForm #AccountLevelID').val(data.a_level_id).prop('disabled', false);
                $('#AccountForm #StatusID').val(data.a_status_id).prop('disabled', false);
                $('#AccountForm #account-level-container').show();
            }

            $('#AccountForm #MunicipalityCityID, #AccountForm #OrganizationID').select2({
                width: '100%'
            });

            $('#accountModal').modal({
                backdrop : 'static',
                keyboard : false
            });

            $('#accountModal').on('hide.bs.modal', function (e) {
                if ($('#AccountForm #BarangayID').hasClass("select2-hidden-accessible")) {
                    $('#AccountForm #MunicipalityCityID').select2('destroy');
                    $('#AccountForm #BarangayID').select2('destroy');
                    $('#AccountForm #OrganizationID').select2('destroy');
                }
            });

        }

    }


    /**
    * update account
    */
    this.updateAccount = function(form)
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
        $.each($(form).find('input'), function(i,e){
            $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger').addClass('text-white');
            $(e).popover('destroy');
        });

        $.ajax({
            url: $(form).prop('action'),
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log(response);
                if (response.status) {
                    $(form).find('#error_message_box').addClass('hide');
                    $(form).find('#error_message_box').find('.error_messages').html('');
                    bootbox.alert(response.message, function(){
                        location.reload(); //easy way, just reload the page
                    });
                } else {
                    // bootbox.alert(response.message);
                    var errors = '';
                    $.each(response.fields, function(i,e){
                        $('#'+i).prop('title', e).closest('div').addClass('has-error').find('label').removeClass('text-white').addClass('text-danger');
                        Utils.popover($('#'+i), {
                            t: 'hover',
                            p: 'top',
                            m: e
                        });
                        errors += '<p>' + e + '</p>';
                    });

                    $(form).find('#error_message_box').find('.error_messages').html(errors);
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
    * get city barangay
    */
    this.loadBarangayOptions = function(target, e, selected = false)
    {
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
    * approve account
    * set type and level
    */
    this.prepareAccountApproval = function(id)
    {
        var data = self.getAccount(id);
        
        if (data != false) {

            // reset form data
            $('#AccountApprovalForm').trigger("reset");

            // reset input erros
            $.each($('#AccountApprovalForm').find('input, select'), function(i,e){
                $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger').addClass('text-white');
                $(e).popover('destroy');
            });

            //clean error box
            $('#AccountApprovalForm').find('#error_message_box .error_messages').html('');
            $('#AccountApprovalForm').find('#error_message_box').addClass('hide');

            $('#AccountApprovalForm #id').val(data.id);

            $('#AccountApprovalForm .photo').prop('src', window.public_url() + "assets/profile/" + data.photo);
            $('#AccountApprovalForm .accountInfo').html(
                '<h5><b>ID: </b> ' + data.mabuhay_id + '</h5>' + 
                '<h5><b>Name: </b> <span class="selected-fullename">' + data.fullname + '</span></h5>' +
                '<h5><b>Address: </b> ' + Object.values(data.address).join(', ') + '</h5>' +
                '<h5><b>Contact: </b> ' + data.contact + '</h5>'
            );
            $('#AccountApprovalForm .accountInfoCont').removeClass('hidden');

            self.setLevelOptions('#AccountApprovalForm #AccountTypeID');

            $('#approveAccountModal').modal({
                backdrop : 'static',
                keyboard : false
            });

        }

    }

    /**
    * save account approval
    */
    this.approveAccount = function(form)
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
                    bootbox.alert(response.message, function(){
                        location.reload(); //easy way, just reload the page
                    });
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
    * delete account
    */
    this.deleteAccount = function (id)
    {
        var data = self.getAccount(id);
        if (data) {
            bootbox.confirm('Are you sure you want to <label class="label label-danger">delete</label> <b>' + data.fullname + '</b> account?', function(r){
                if (r) {
                    $.LoadingOverlay("show", {zIndex: 999});
                    $.ajax({
                        url: window.base_url('accounts/delete/' + data.reg_id),
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
    * prepare account level options base on type
    */
    this.setLevelOptions = function(elem)
    {
        var type = $(elem).val();
        var options = '';
        $.each(self.accountLevels, function(i,e){
            if (type == e.AccountTypeID) {
                options += '<option value="' +e.id+ '">' + e.LevelName + '</option> \n';
            }
        });
        $(elem).closest('form').find('#AccountLevelID').html(options);
    }

}


var Accounts = new Accounts();
$(document).ready(function(){
    Accounts._init();
});