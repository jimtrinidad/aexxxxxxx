function Documents() {

    // because this is overwritten on jquery events
    var self = this;

    this.documentsData = {};
    this.extraFormFields = {};

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
        $('#DocumentForm').submit(function(e) {
            e.preventDefault();
            self.saveDocument(this);
        });
        $('#ExtraFieldForm').submit(function(e) {
            e.preventDefault();
            self.saveExtraFormField(this);
        });
        $('.previewButton').click(function(e) {
            e.preventDefault();
            self.openTemplatePreview($(this).prop('id'));
        });
    }

    /**
    * usage of different libraries and plugins
    */
    this.set_configs = function()
    {
        
    }

    this.getDocuments = function(id)
    {   
        var match = false;
        $.each(self.documentsData, function(i,e){
            if (e.id == id) {
                match = e;
                return false;
            }
        });

        return match;
    }

    this.getExtraField = function(id)
    {   
        var match = false;
        $.each(self.extraFormFields, function(i,e){
            if (i == id) {
                match = e;
                return false;
            }
        });

        return match;
    }

    /**
    * add new document
    */
    this.addDocument = function()
    {   
        // reset form data
        $('#DocumentForm').trigger("reset");

        // reset input erros
        $.each($('#DocumentForm').find('input,select,textarea'), function(i,e){
            $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger').addClass('text-white');
            $(e).popover('destroy');
        });
        //clean error box
        $('#DocumentForm').find('#error_message_box .error_messages').html('');
        $('#DocumentForm').find('#error_message_box').addClass('hide');

        $('#DocumentForm #id').val('');
        $('#DocumentForm .image-preview').prop('src', window.public_url() + 'assets/logo/blank-logo.png');

        if ($('#DocumentForm #DepartmentID').hasClass("select2-hidden-accessible")) {
            $('#DocumentForm #DepartmentID').select2('destroy');
        }
        $('#DocumentForm #DepartmentID').select2({
            width: '100%'
        });

        $('#documentModal .modal-title .header-action').html('Add');
        $('#documentModal').modal({
            backdrop : 'static',
            keyboard : false
        });
    }

    /**
    * edit document
    */
    this.editDocument = function(id)
    {
        var data = this.getDocuments(id);
        if (data) {
            // reset form data
            $('#DocumentForm').trigger("reset");

            // reset input erros
            $.each($('#DocumentForm').find('input,select,textarea'), function(i,e){
                $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger').addClass('text-white');
                $(e).popover('destroy');
            });
            //clean error box
            $('#DocumentForm').find('#error_message_box .error_messages').html('');
            $('#DocumentForm').find('#error_message_box').addClass('hide');

            $('#DocumentForm #id').val(data.id);
            $('#DocumentForm #Type').val(data.Type);
            $('#DocumentForm #Name').val(data.Name);
            $('#DocumentForm #Description').val(data.Description);
            $('#DocumentForm #Validity').val(data.Validity);
            if (data.SubDepartment) {
                $('#DocumentForm #DepartmentID').val(data.DepartmentID + '-' + data.SubDepartmentID);
            } else {
                $('#DocumentForm #DepartmentID').val(data.DepartmentID);
            }

            $('#DocumentForm .image-preview').prop('src', window.public_url() + 'assets/logo/' + data.Logo);

            if ($('#DocumentForm #DepartmentID').hasClass("select2-hidden-accessible")) {
                $('#DocumentForm #DepartmentID').select2('destroy');
            }
            $('#DocumentForm #DepartmentID').select2({
                width: '100%'
            });
            $('#documentModal .modal-title .header-action').html('Edit');
            $('#documentModal').modal({
                backdrop : 'static',
                keyboard : false
            });
        }
    }

    /**
    * save document
    */
    this.saveDocument = function(form)
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
    * delete
    */
    this.deleteDocument = function (id)
    {
        var data = this.getDocuments(id);
        if (data) {
            bootbox.confirm('Are you sure you want to <label class="label label-danger">delete</label> document template <b>' + data.Name + '</b>?', function(r){
                if (r) {
                    $.LoadingOverlay("show", {zIndex: 999});
                    $.ajax({
                        url: window.base_url('documents/delete/' + data.Code),
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
    * add new extra fields
    */
    this.addExtraField = function()
    {
        // reset form data
        $('#ExtraFieldForm').trigger("reset");

        // reset input erros
        $.each($('#ExtraFieldForm').find('input'), function(i,e){
            $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger').addClass('text-white');
            $(e).popover('destroy');
        });
        //clean error box
        $('#ExtraFieldForm').find('#error_message_box .error_messages').html('');
        $('#ExtraFieldForm').find('#error_message_box').addClass('hide');

        $('#ExtraFieldForm #id').val('');

        $('#extraFieldModal .modal-title .header-action').html('Add');
        $('#extraFieldModal').modal({
            backdrop : 'static',
            keyboard : false
        });
    }

    /**
    * save extra fields
    */
    this.saveExtraFormField = function(form)
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
                    self.extraFormFields = response.data;
                    self.setExtraFieldPanel();
                    $('#extraFieldModal').modal('hide');
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
    * remove extra field
    */
    this.removeExtraField = function(code, id)
    {
        var field = self.getExtraField(id);
        if (field) {
            bootbox.confirm('Are you sure you want to remove additonal field, <label class="label label-info">' + field.keyword + '</label>?', function(r) {
                if (r) {
                    $.LoadingOverlay("show", {zIndex: 999});
                    $.ajax({
                        url: window.base_url('documents/remove_extra_field/' + code + '/' + id),
                        type: 'GET',
                        success: function (response) {
                            if (response.status) {
                                self.extraFormFields = response.data;
                                self.setExtraFieldPanel();
                                $.LoadingOverlay("hide");
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
    * generate extra field panel
    */
    this.setExtraFieldPanel = function ()
    {

        var code     = $('input#document-code').val();
        var keywords = '';
        var images   = '';
        $.each (self.extraFormFields, function(i, e) {
            if (e.type != 3) {
                keywords += '<div class="col-xs-6"> { #'+ e.keyword +' } - <small>'+ e.label +'</small> \
                                &nbsp;&nbsp;&nbsp;<a href="javascript:;" onClick="Documents.removeExtraField(\''+code+'\',\''+i+'\')"><i class="fa fa-trash"></i></a> \
                            </div>';
            } else {
                images += '<div class="col-xs-12"> { '+ e.url +' } - <small>'+ e.label +'</small> \
                                &nbsp;&nbsp;&nbsp;<a href="javascript:;" onClick="Documents.removeExtraField(\''+code+'\',\''+i+'\')"><i class="fa fa-trash"></i></a> \
                            </div>'
            }
        });

        var template = '<div class="panel panel-default"> \
                            <div class="panel-heading">Additional Fields Data</div> \
                                <div class="panel-body">';
                                if (keywords != '') {
                                    template += '<b>Keywords</b><div class="row"> ' + keywords + '</div>';
                                }
                                if (images != '') {
                                    template += '<b>Images</b><div class="row"> ' + images + '</div>';
                                }
            template += '</div></div>';
        if (Object.keys(self.extraFormFields).length) {
            $('#extra-form-panel-cont').html(template);
        } else {
            $('#extra-form-panel-cont').html('');
        }
    }

    /**
    * open template preview on new window
    */
    this.openTemplatePreview = function(type)
    {
        var templatehtml = tinyMCE.get('document_template').getContent();
        if (type == 'pdf_preview') {
            $('<form action="'+window.base_url('documents/template_preview/pdf/')+'" method="post" target="_blank"><textarea name="template_html">'+templatehtml+'</textarea></form>').appendTo('body').submit().remove();
        } else if (type == 'html_preview') {
            $('<form action="'+window.base_url('documents/template_preview/html/')+'" method="post" target="_blank"><textarea name="template_html">'+templatehtml+'</textarea></form>').appendTo('body').submit().remove();
        }
    }

}


var Documents = new Documents();
$(document).ready(function(){
    Documents._init();
});