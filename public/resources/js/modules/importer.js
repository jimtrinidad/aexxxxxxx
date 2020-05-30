function Importer() {

    // because this is overwritten on jquery events
    var self = this;

    // initialize module variables
    this.importData = {}

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

        $('#UploadForm').submit(function(e) {
            e.preventDefault();
            self.saveGroup(this);
        });

    }

    /**
    * usage of different libraries and plugins
    */
    this.set_configs = function()
    {
        
    }

    /**
    * add new department
    */
    this.uploadGroup = function()
    {   
        // reset form data
        $('#UploadForm').trigger("reset");

        // reset input erros
        $.each($('#UploadForm').find('input'), function(i,e){
            $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger').addClass('text-white');
            $(e).popover('destroy');
        });
        //clean error box
        $('#UploadForm').find('#error_message_box .error_messages').html('');
        $('#UploadForm').find('#error_message_box').addClass('hide');

        $('#UploadForm #id').val('');

        $('#uploadModal .modal-title').html('<b>Upload Group</b> | Add');
        $('#uploadModal').modal({
            backdrop : 'static',
            keyboard : false
        });
    }

    /**
    * delete department
    */
    this.deleteGroup = function(id)
    {   
        var data = self.getDepartment(id);
        if (data) {
            bootbox.confirm('Are you sure you want to <label class="label label-danger">delete</label> ' + data.name, function(r){
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
    * save new or edit department 
    */
    this.saveGroup = function(form)
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
    * set location dropdown by scope
    */
    this.setLocationSelector = function(e)
    {   
        console.log('trigger');
        // reset location value
        if ($(self.form).find('#uploadModal #location').hasClass("select2-hidden-accessible")) {
            $(self.form).find('#uploadModal #location').select2('destroy');
        }
        if ($(self.form).find('#citySelector').hasClass("select2-hidden-accessible")) {
            $(self.form).find('#citySelector').select2('destroy');
        }
        $('#uploadModal #location').val('').html('').prop('disabled', true);
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
                $('#uploadModal').LoadingOverlay("show");
                $('#uploadModal #location').html(window.emptySelectOption);
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
                    $('#uploadModal #location').html(options).prop('disabled', false).select2({
                        width: '100%'
                    });
                    $('#uploadModal').LoadingOverlay("hide");
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
        if ($(self.form).find('#uploadModal #location').hasClass("select2-hidden-accessible")) {
            $(self.form).find('#uploadModal #location').select2('destroy');
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

}


var Importer = new Importer();
$(document).ready(function(){
    Importer._init();
});