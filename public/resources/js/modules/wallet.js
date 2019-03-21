function Wallet() {

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
        $('#depositForm').submit(function(e){
            e.preventDefault();
            self.saveForm(this);
        });

        $('#paymentForm').submit(function(e){
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
    this.addDeposit = function()
    {   
        // reset form data
        $('#depositForm').trigger("reset");

        // reset input erros
        $.each($('#depositForm').find('input, textarea, select'), function(i,e){
            $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger');
            $(e).popover('destroy');
        });
        //clean error box
        $('#depositForm').find('#error_message_box .error_messages').html('');
        $('#depositForm').find('#error_message_box').addClass('hide');

        $('#depositModal .modal-title').html('<b>Deposit</b>');
        $('#depositModal').modal({
            backdrop : 'static',
            keyboard : false
        });
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
                    bootbox.alert(response.message, function(){
                        location.reload();
                    });
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


    this.addPayment = function()
    {
        // reset form data
        $('#paymentForm').trigger("reset");

        // reset input erros
        $.each($('#paymentForm').find('input, textarea, select'), function(i,e){
            $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger');
            $(e).popover('destroy');
        });
        //clean error box
        $('#paymentForm').find('#error_message_box .error_messages').html('');
        $('#paymentForm').find('#error_message_box').addClass('hide');

        $('#paymentModal .modal-title').html('<b>Payment</b>');
        $('#paymentModal').modal({
            backdrop : 'static',
            keyboard : false
        });
    }

}


var Wallet = new Wallet();
$(document).ready(function(){
    Wallet._init();
});