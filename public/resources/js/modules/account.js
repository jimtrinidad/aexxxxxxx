function Account() {
    // because this is overwritten on jquery events
    var self = this;

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

        /**
        * login submit
        */
        $('#loginForm').submit(function(e) {
            e.preventDefault();
            self.login($(this));
        });

        /**
        * registration submit
        */
        $('#RegistrationForm').submit(function(e) {
            e.preventDefault();
            self.register(this);
        });

        $('#ChangePasswordForm').submit(function(e) {
            e.preventDefault();
            self.changePassword(this);
        });

        $('#ChangeProfileForm').submit(function(e) {
            e.preventDefault();
            self.changeProfile(this);
        }) 

        self.showhide_registration_elem();
        $(window).resize(function(){
            self.showhide_registration_elem();
        });

    }

    this.showhide_registration_elem = function()
    {
        if ($('.showonmd').is(':visible')) {
            $('.showonmd').find('input,select').prop('disabled', false);
            $('.showonsm').find('input,select').prop('disabled', true);
        } else {
            $('.showonmd').find('input,select').prop('disabled', true);
            $('.showonsm').find('input,select').prop('disabled', false);
        }
    }

    /**
    * usage of different libraries and plugins
    */
    this.set_configs = function()
    {

        if ( $.isFunction($.fn.select2) ) {
            $('#OrganizationID').select2({
                width: '100%',
                templateResult: function (selection) {
                    if (!selection.id) { return selection.text; }
                    var logo = $(selection.element).data('logo');
                    if(!logo){
                        return selection.text;
                    } else {
                        return $('<img style="width: 25px;height: 25px" src="' + window.public_url() + 'assets/logo/' + logo + '" alt=""> \
                                                <span class="img-changer-text"> ' + $(selection.element).text() + '</span>');
                    }
                }
            });
        }
    },


    /**
    * login request
    */
    this.login = function(e)
    {
        
        // prenvet multiple calls
        if ($(e).data('running')) {
            return false;
        }
        $(e).data('running', true);
        $('.box-content').LoadingOverlay("show", {
            background              : "rgba(255, 255, 255, 0.1)"
        })

        $.post(e.prop('action'), e.serialize()).done(function(response) {
            if (response.status) {
                // $('html').css('background', 'white').find('.modal-overs').remove();
                $('#error_message_box').text(response.message).addClass('hide');
                window.location = window.base_url(); 
            } else {
                $('#password').val('');
                $('#error_message_box').text(response.message).removeClass('hide');

                // no need to remove loading on success, let it redirect
                $('.box-content').LoadingOverlay("hide");
                $(e).data('running', false);
            }
        });

    },

    /**
    * register request
    */
    this.register = function(e)
    {
            
        // prenvet multiple calls
        if ($(e).data('running')) {
            return false;
        }
        $(e).data('running', true);

        $('.modal-content').LoadingOverlay("show", {
            background              : "rgba(255, 255, 255, 0.1)"
        })

        var formData = new FormData(e);
        
        // reset input erros
        $.each($(e).find('input'), function(i,e){
            $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger').addClass('text-white');
            $(e).popover('destroy');
        });
        //clean error box
        // $('#error_message_box .error_messages').html('');
        // $('#error_message_box').addClass('hide');

        $.ajax({
            url: $(e).prop('action'),
            type: 'POST',
            data: formData,
            success: function (response) {
                if (response.status) {
                    $('#error_message_box').addClass('hide');
                    bootbox.alert(response.message, function(){
                        window.location = window.base_url('account/signin'); 
                    });
                } else {
                    // bootbox.alert(response.message);
                    var errors = '';
                    $.each(response.fields, function(i,e){
                        $('#'+i+',.'+i).prop('title', e).closest('div').addClass('has-error').find('label').removeClass('text-white').addClass('text-danger');
                        Utils.popover($('#'+i+',.'+i), {
                            t: 'hover',
                            p: 'top',
                            m: e
                        });
                        errors += '<p>' + e + '</p>';
                    });

                    $('#error_message_box .error_messages').html(errors);
                    $('#error_message_box').removeClass('hide');
                }
            },
            complete: function() {
                $('.modal-content').LoadingOverlay("hide");
                $(e).data('running', false);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }

    /**
    * get city barangay
    */
    this.loadBarangayOptions = function(target, e)
    {
        $(target).html(window.emptySelectOption).prop('disabled', true);
        if ($(target).hasClass("select2-hidden-accessible")) {
            $(target).select2('destroy');
        }
        $.get(window.public_url('get/barangay'), {'citymunCode' : $(e).val()}).done(function(response) {
            if (response.status) {
                var options = window.emptySelectOption;
                $.each(response.data, function(i, e){
                    options += '<option value="' + e.brgyCode + '">' + e.brgyDesc + '</option> \n';
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
    * change password form modal
    */
    this.changePasswordOpen = function()
    {
        // reset form data
        $('#ChangePasswordForm').trigger("reset");

        // reset input erros
        $.each($('#ChangePasswordForm').find('input,select,textarea'), function(i,e){
            $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger');
            $(e).popover('destroy');
        });
        //clean error box
        $('#ChangePasswordForm').find('#error_message_box .error_messages').html('');
        $('#ChangePasswordForm').find('#error_message_box').addClass('hide');

        $('#changePasswordModal .modal-title .header-action').html('Add');
        $('#changePasswordModal').modal({
            backdrop : 'static',
            keyboard : false
        });
    }

    this.changePassword = function(form)
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
                    bootbox.alert(response.message);
                    $('#changePasswordModal').modal('hide');
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

    /**
    * change profile pic form modal
    */
    this.changeProfileOpen = function()
    {
        // reset form data
        $('#ChangeProfileForm').trigger("reset");

        // reset input erros
        $.each($('#ChangeProfileForm').find('input,select,textarea'), function(i,e){
            $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger');
            $(e).popover('destroy');
        });
        //clean error box
        $('#ChangeProfileForm').find('#error_message_box .error_messages').html('');
        $('#ChangeProfileForm').find('#error_message_box').addClass('hide');

        $('#changeProfileModal .modal-title .header-action').html('Change Profile');
        $('#changeProfileModal').modal({
            backdrop : 'static',
            keyboard : false
        });
    }

    this.changeProfile = function(form)
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
                    bootbox.alert(response.message);
                    $('#changeProfileModal').modal('hide');
                    var imgsrc = $('.profile-img').find('img').prop('src') + "?" + Date.now();
                    // profile
                    $('.profile-img').find('img.i-profile').prop('src', imgsrc);
                    // modal image
                    $('#changeProfileModal').find('.image-preview').prop('src', imgsrc);
                    // id
                    $('.profile-photo img.avatar').prop('src', imgsrc);
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

var Account = new Account();
$(document).ready(function(){
    Account._init();
});