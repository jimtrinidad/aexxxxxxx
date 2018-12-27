function Zones() {

    // because this is overwritten on jquery events
    var self = this;

    this.sortable = {};

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
        $('#ZoneForm').submit(function(e) {
            e.preventDefault();
            self.save(this);
        });

        $('#PublicOfficeSetupForm').submit(function(e) {
            e.preventDefault();
            self.savePublicOffice(this);
        });
    }

    /**
    * usage of different libraries and plugins
    */
    this.set_configs = function()
    {
        var form = $('#PublicOfficeSetupForm');
        if (form.length) {
            form.children("div.div-wizard").steps({
                headerTag: "h3",
                bodyTag: "fieldset",
                transitionEffect: "fade",
                stepsOrientation: "vertical",
                enableAllSteps: true,
                titleTemplate: '<div class="title"><span class="step-number">#index#</span><span class="step-text">#title#</span></div>',
                labels: {
                    previous: 'Previous',
                    next: 'Next',
                    finish: 'Save',
                    current: ''
                },
                onStepChanging: function(event, currentIndex, newIndex) {
                    return true;
                },
                onFinished: function(event, currentIndex) {
                    form.trigger('submit');
                },
                onStepChanged: function(event, currentIndex, priorIndex) {
                    switch(currentIndex) {
                        case 2: 
                            self.setSortable('addedPublicServants');
                            break;
                        case 3: 
                            self.setSortable('addedBanners');
                            break;
                    }
                    autosize.update($('textarea'));
                    return true;
                },
                onInit: function() {
                    form.removeClass('hidden');
                    autosize($('textarea'));
                }
            });
        }
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
    * edit document
    */
    this.editZone = function(obj)
    {
        var data = $(obj).closest('tr').data();
        if (data) {
            // reset form data
            $('#ZoneForm').trigger("reset");

            // reset input erros
            $.each($('#ZoneForm').find('input'), function(i,e){
                $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger').addClass('text-white');
                $(e).popover('destroy');
            });
            //clean error box
            $('#ZoneForm').find('#error_message_box .error_messages').html('');
            $('#ZoneForm').find('#error_message_box').addClass('hide');

            $('#ZoneForm').find('#Name').val(data.name);
            $('#ZoneForm').find('#Type').val(data.type);
            $('#ZoneForm').find('#zonepsgc').val(data.psgc);
            $('#ZoneForm').find('#zonetype').val(data.zonetype);

            $('#ZoneForm .image-preview').prop('src', window.public_url() + 'assets/logo/' + data.logo);

            // $('#zoneModal .modal-title .header-action').html('Edit');
            $('#zoneModal').modal({
                backdrop : 'static',
                keyboard : false
            });
        }
    }

    /**
    * post save
    */
    this.save = function(form)
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
    * add public servant row
    */
    this.addPublicServantRow = function()
    {
        var form        = $('#PublicOfficeSetupForm').find('#addedPublicServants tr:last');
        var count       = ($('#PublicOfficeSetupForm').find('#addedPublicServants tr').length - 1);
        var position    = form.find('.fieldPosition').val();
        var firstname   = form.find('.fieldFirstname').val();
        var lastname    = form.find('.fieldLastname').val();

        if (position != '' && firstname != '' && lastname != '') {
            var clone  = form.clone();
            var itemID = Utils.generateString();
            form.removeClass('info').addClass('sortable-row').find('td:first-child').prepend('<i class="drag-handle fa fa-arrows"></i>');
            form.find('td:last-child').html('<button type="button" class="btn btn-danger btn-sm" onClick="Zones.removePublicServantRow(this)"><i class="fa fa-trash"></i></button> \
                <input type="hidden" class="item-order" name="Servant['+form.prop('id')+'][Ordering]" value="'+(count + 1)+'">');

            clone.prop('id', itemID).addClass('info');
            clone.find('.fieldPosition').prop('name', 'Servant['+itemID+'][Position]').val('');
            clone.find('.fieldFirstname').prop('name', 'Servant['+itemID+'][Firstname]').val('');
            clone.find('.fieldLastname').prop('name', 'Servant['+itemID+'][Lastname]').val('');
            clone.find('.fieldPhoto').prop('name', 'Images['+itemID+']').val('').closest('div').find('.image-preview').prop('src', window.public_url('assets/profile') + '/avatar_default.jpg');
            $('#PublicOfficeSetupForm').find('#addedPublicServants').append(clone);

            self.setSortable('addedPublicServants');
        }
    }

    /**
    * remove public servant row
    */
    this.removePublicServantRow = function(elem)
    {
        var rowID   = $(elem).closest("tr").prop('id');
        $(elem).closest("tr").fadeOut('fast', function(){
            $(this).remove();
            self.setSortable('addedPublicServants');
        });
    }

    /**
    * add banner
    */
    this.addBannerRow = function()
    {
        var form        = $('#PublicOfficeSetupForm').find('#addedBanners tr:last');
        var count       = ($('#PublicOfficeSetupForm').find('#addedBanners tr').length - 1);
        var banner      = form.find('.fieldBanner').val();

        if (banner) {
            var clone  = form.clone();
            var itemID = Utils.generateString();
            form.removeClass('info').addClass('sortable-row').find('td:first-child').prepend('<i class="drag-handle fa fa-arrows"></i>');
            form.find('td:last-child').html('<button type="button" class="btn btn-danger btn-sm" onClick="Zones.removeBannerRow(this)"><i class="fa fa-trash"></i></button> \
                <input type="hidden" class="item-order" name="Banners['+form.prop('id')+'][Ordering]" value="'+(count + 1)+'">');

            clone.prop('id', itemID).addClass('info');
            clone.find('.fieldUrl').prop('name', 'Banners['+itemID+'][URL]').val('');
            clone.find('.fieldBanner').prop('name', 'Images['+itemID+']').val('').closest('div').find('.image-preview').prop('src', window.public_url('assets/etc') + '/placeholder-banner.png');
            $('#PublicOfficeSetupForm').find('#addedBanners').append(clone);

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
    * save public office setup changes
    */
    this.savePublicOffice = function(form)
    {   
        // prenvet multiple calls
        if ($(form).data('running')) {
            return false;
        }

        $(form).data('running', true);
        $(form).find('input').blur();
        $('section.content.container-fluid').LoadingOverlay("show");

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
                    bootbox.alert(response.message);
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
                $('section.content.container-fluid').LoadingOverlay("hide");
                $(form).data('running', false);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }

}


var Zones = new Zones();
$(document).ready(function(){
    Zones._init();
});