function Utils() {
    // because this is overwritten on jquery events
    var self = this;

    /**
     * Initialize events
     */
    this._init = function()
    {

        self.set_events();
        self.set_configs();

        // Stick footer at the bottom
        Utils.stickFooter();

        // keep scroll position on script reload
        if (sessionStorage.scrollTop != "undefined") {
            $(window).scrollTop(sessionStorage.scrollTop);
        }

    },

    /**
    * events delaration
    */
    this.set_events = function()
    {

        /**
        * image upload preview
        */
        $(document).on('change', 'input.image-upload-input', function(){
            self.previewImageFile(this);
        });
        $('img.image-preview').prop('title', 'Click to upload');
        $(document).on('click', 'img.image-preview', function(){
            $(this).closest('.image-upload-container').find('input.image-upload-input').trigger('click');
        });

        // sticky footer on bottom
        $(window).resize(function(){
            Utils.stickFooter();
        });

        // keep scroll position on script reload
        $(window).scroll(function() {
            sessionStorage.scrollTop = $(this).scrollTop();
        });


        $('.open-mak-id').click(function(){
            $('.id-overlay').show();
            $('#mak-id').show();
        });
        
        $('.id-overlay').click(function(){
            $(this).hide();
            $('#mak-id').hide();
        });

        $("input, textarea").attr('autocomplete', 'off');

    }

    /**
    * usage of different libraries and plugins
    */
    this.set_configs = function()
    {

    },


    /**
    * generate random string
    */
    this.generateString = function(length = 6, withNumber = false)
    {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

        if (withNumber) {
            possible += '0123456789';
        }

        for (var i = 0; i < length; i++) {
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        }

        return text;
    }

    /**
    * get random item from array
    */
    this.getRandomItem = function(items)
    {
        return items[Math.floor(Math.random()*items.length)];
    }


    /**
    * shuffle array
    */
    this.shuffle = function(array) {
        var currentIndex = array.length, temporaryValue, randomIndex;

        // While there remain elements to shuffle...
        while (0 !== currentIndex) {

            // Pick a remaining element...
            randomIndex = Math.floor(Math.random() * currentIndex);
            currentIndex -= 1;

            // And swap it with the current element.
            temporaryValue = array[currentIndex];
            array[currentIndex] = array[randomIndex];
            array[randomIndex] = temporaryValue;

        }

        return array;
    }


    /**
    * preview image upload
    */
    this.previewImageFile = function(input)
    {
        if (input.files && input.files[0]) {
            var file = input.files[0];
            var ValidImageTypes = ["image/gif", "image/jpeg", "image/png"];
            if ($.inArray(file.type, ValidImageTypes) >= 0) {
                if(file.size>2097152) {
                    bootbox.alert('File size is larger than 2MB!');
                    this.resetPreviewImageFile(input);
                } else {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $(input).closest('.image-upload-container').find('img.image-preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            } else {
                bootbox.alert('Invalid file type.');
                this.resetPreviewImageFile(input);
            }
        } else {
            this.resetPreviewImageFile(input);
        }
    },

    this.resetPreviewImageFile = function(input)
    {
        $(input).val('');
        var default_img = $(input).data('default');
        $(input).closest('.image-upload-container').find('img.image-preview').attr('src', (default_img ? default_img : window.public_url('assets/profile/') + 'avatar_default.jpg'));
    }


    /*
    * show popover message
    */
    this.popover = function($obj, params)
    {
        $obj.popover({
              trigger: params.t,
              placement: params.p,
              content: params.m,
              template: "<div class=\"popover\"><div class=\"arrow\"></div><div class=\"popover-inner\"><div class=\"text-danger popover-content\"><p></p></div></div></div>"
            });
        if (params.t == 'manual') {
            $obj.popover("show");
        }

        $obj.one('focus', function (){
            $(this).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger');
            $(this).popover('destroy');
        });
    }

    /**
    * keep footer on bottom on small window
    */
    this.stickFooter = function()
    {
        var bodyH = $('body').height();
        var headH = $('#header-wrapper').height();
        var mainH = $('#main-wrapper').height();
        var footH = $('#footer-wrapper').height();
        $('#main-wrapper').css('min-height', (bodyH-(headH+footH)) + 'px');
        $('#footer-wrapper').find('.footer').css('visibility','visible');
    }

    this.highlightMatch = function($obj, term)
    {
        // remove any old highlighted terms
        $obj.removeHighlight();
        // disable highlighting if empty
        if (term != '') {
            $obj.highlight( term );
        }
    }
}

var Utils = new Utils();
$(document).ready(function(){
    Utils._init();
});