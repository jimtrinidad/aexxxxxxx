function Business() {

    // because this is overwritten on jquery events
    var self = this;

    this.itemData = {};

    /**
     * Initialize events
     */
    this._init = function() 
    {

        self.set_events();
        self.set_configs();

    },

    /**
    * events delaration
    */
    this.set_events = function()
    {
        $('#itemForm').submit(function(e){
            e.preventDefault();
            self.saveForm(this);
        });
    }

    /**
    * usage of different libraries and plugins
    */
    this.set_configs = function()
    {

    },


    this.getData = function(id)
    {   
        var match = false;
        $.each(self.itemData, function(i,e){
            if (e.id == id) {
                match = e;
                return false;
            }
        });

        return match;
    }


    /**
    * add
    */
    this.addItem = function()
    {   
        // reset form data
        $('#itemForm').trigger("reset");

        // reset input erros
        $.each($('#itemForm').find('input, textarea, select'), function(i,e){
            $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger');
            $(e).popover('destroy');
        });
        //clean error box
        $('#itemForm').find('#error_message_box .error_messages').html('');
        $('#itemForm').find('#error_message_box').addClass('hide');

        $('#DepartmentForm .image-preview').prop('src', window.public_url() + 'assets/logo/blank-logo.png');

        $('#itemForm #Code').val('');

        $('#itemModal .modal-title').html('<b>Product</b> | Add');
        $('#itemModal').modal({
            backdrop : 'static',
            keyboard : false
        });
    }


    this.editItem = function(id)
    {
        var data  = self.getProject(id);

        if (data) {
            // reset form data
            $('#itemForm').trigger("reset");

            // reset input erros
            $.each($('#itemForm').find('input, textarea, select'), function(i,e){
                $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger');
                $(e).popover('destroy');
            });
            //clean error box
            $('#itemForm').find('#error_message_box .error_messages').html('');
            $('#itemForm').find('#error_message_box').addClass('hide');

            $('#itemModal .modal-title').html('<b>Product</b> | Update');
            $('#itemModal').modal({
                backdrop : 'static',
                keyboard : false
            });
        }
    }

    this.deleteItem = function(id)
    {
        var data = self.getProject(id);
        if (data) {
            bootbox.confirm('Are you sure you want to <label class="label label-danger">delete</label> ' + data.Name, function(r){
                if (r) {
                    $.LoadingOverlay("show", {zIndex: 999});
                    $.ajax({
                        url: window.base_url('businesses/deleteitem/' + data.Code),
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


    this.saveForm = function(form)
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
        $.each($(form).find('input, select, textarea'), function(i,e){
            $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger');
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
                    // bootbox.alert(response.message, function(){
                        location.reload();
                    // });
                } else {
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

}


var Business = new Business();
$(document).ready(function(){
    Business._init();
});