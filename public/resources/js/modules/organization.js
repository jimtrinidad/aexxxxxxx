function Organization() {

    // because this is overwritten on jquery events
    var self = this;

    this.selectedService;

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
        $('#ServiceReportForm').submit(function(e){
            e.preventDefault();
            self.submitServiceApplication(this);
        });
    }

    /**
    * usage of different libraries and plugins
    */
    this.set_configs = function()
    {

    },


    /**
    * find and get services
    */
    this.getServices = function(selectedService = false)
    {
        var params = {
            'keyword'    : $('#searchForm #keyword').val(),
        };

        $('#LoadMainBody').html(''); //reset content
        $('#LoadMainBodyCont').css('min-height', '70px').LoadingOverlay("show", {
            text: 'Loading services...', 
            textClass: 'text-gray',
            textResizeFactor: 1,
            imageResizeFactor: 2,
            size: 20,
            fade: [400, 50],
            zIndex: 9999
        });

        $.ajax({
            url  : window.base_url('organization/services'),
            type : 'post',
            data : params,
            success : function(response) {

                $('#LoadMainBody').LoadingOverlay("hide");

                if (response.status) {

                    var data = response.data;
                    $.each(data, function(i,e){
                        var tpl = `<div class="col-xs-12 col-sm-6 offset-top-10">
                                    <div class="bg-white">
                                        <div class="org-category">${e.category}</div>
                                        <div class="row gutter-5">`;

                        $.each(e.items, function(j,k) {
                            var logo = window.public_url('assets/logo/' + k.Logo)
                            tpl += `<div class="col-xs-3">
                                        <div class="org-item" onclick="Organization.openServiceApplication('${k.Code}')">
                                            <div class="image" style="background-image: url(${logo});"></div>
                                            <div class='name'>${k.MenuName}</div>
                                        </div>
                                    </div>`;
                        });

                        tpl += '</div></div></div>';

                        $('#LoadMainBody').append(tpl);
                    });

                } else {
                    // clear content, add empty message
                    $('#LoadMainBody').html('<div class="col-xs-12"><div class="feedItem bg-white post-items padding-20"> \
                                               <div class="row"> \
                                                  <div class="col-xs-12 padding-20"> \
                                                    <h2 class="text-bold text-gray">No record found</h2> \
                                                  </div> \
                                                </div> \
                                            </div></div> \
                                                  ');
                }

            },
            complete : function() {
                $('#LoadMainBodyCont').LoadingOverlay("hide");
            }
        });
    }


    /**
    * open service details when clicked from service list
    */
    this.openServiceApplication = function(code)
    {
        $.LoadingOverlay("show", {zIndex: 999});
        $.ajax({
            url: window.base_url('services/view/' + code),
            type: 'GET',
            success: function (response) {
                if (response.status) {

                    // reset form data
                    $('#ServiceReportForm').trigger("reset");
                    
                    var v = response.data;

                    var serviceTemplate = $('#serviceApplicationModal .serviceDetails');

                    serviceTemplate.find('span.ServiceName').text(v.Name);
                    serviceTemplate.find('span.ServiceDesc').text(v.Description);
                    serviceTemplate.find('span.ServiceZone').text(Object.values(v.Location).join(' > '));
                    serviceTemplate.find('span.ServiceCode').text(v.Code);
                    serviceTemplate.find('span.serviceProvided').text(v.serviceProvided);

                    if (v.Fee) {
                        serviceTemplate.find('span.ServiceFee .fee-amount').text('P' + v.Fee);
                        serviceTemplate.find('span.ServiceFee').show();
                    } else {
                        serviceTemplate.find('span.ServiceFee').hide();
                    }

                    // if (v.SubDepartment) {
                    //     serviceTemplate.find('span.DeptName').text(v.SubDepartment.Name);
                    //     serviceTemplate.find('img.DepartmentLogo').prop('src', window.public_url('assets/logo/' + v.SubDepartment.Logo));
                    // } else {
                        serviceTemplate.find('span.DeptName').text(v.Department.Name);
                        serviceTemplate.find('img.DepartmentLogo').prop('src', window.public_url('assets/logo/' + v.Department.Logo));
                    // }
                    serviceTemplate.find('img.ServiceLogo').prop('src', window.public_url('assets/logo/' + v.Logo));

                    serviceTemplate.find('.ServiceTags').html('');
                    $.each(v.Tags, function(i, e) {
                        serviceTemplate.find('.ServiceTags').append(' <label class="label label-'+Utils.getRandomItem(['info','success','danger','warning','primary'])+'">' + e + '</label> ');
                    });

                    // reset input erros
                    $.each($('#ServiceReportForm').find('input'), function(i,e){
                        $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger').addClass('text-white');
                        $(e).popover('destroy');
                    });

                    var additionalFields = '';
                    $.each(v.ExtraFields, function(i, e) {
                        additionalFields += '<div class="col-xs-6 col-sm-4"><div class="form-group"><label class="control-label">'+e.FieldLabel+'</label>';
                        if (e.FieldType == 1) {
                            additionalFields += '<input type="text" id="'+e.FieldID+'" name="ExtraField['+e.FieldID+']" class="form-control input-sm" placeholder="'+e.FieldLabel+'">';
                        } else if (e.FieldType == 2) {
                            additionalFields += '<textarea rows="1" class="form-control input-sm" id="'+e.FieldID+'" name="ExtraField['+e.FieldID+']" placeholder="'+e.FieldLabel+'"></textarea>';
                        } else if (e.FieldType == 3) {
                            additionalFields += '<input type="file" id="'+e.FieldID+'" name="Image['+e.FieldID+']" class="form-control input-sm" placeholder="'+e.FieldLabel+'">';
                        }
                        additionalFields += '</div></div>';
                    });
                    if (additionalFields != '') {
                        $('#serviceApplicationModal #serviceAdditionalFieldsCont').html('<div class="post-items bg-white padding-10"> \
                                                                                            <h2 class="text-cyan text-bold offset-bottom-10">Report Data</h2> \
                                                                                            <div class="row">'
                                                                                             + additionalFields + 
                                                                                            '</div> \
                                                                                        </div>');
                    } else {
                        $('#serviceApplicationModal #serviceAdditionalFieldsCont').html('');
                    }

                    var requirementDocFields = '';
                    $.each(v.Requirements, function (j, k) {
                        var documentFields = '';
                        $.each(k.extraFields, function(i, e) {
                            documentFields += '<div class="col-xs-6 col-sm-4"><div class="form-group"><label class="control-label">'+e.label+'</label>';
                            if (e.type == 1) {
                                documentFields += '<input type="text" id="'+i+'" name="RequirementField['+k.id+']['+i+']" class="form-control input-sm" placeholder="'+e.label+'">';
                            } else if (e.type == 2) {
                                documentFields += '<textarea rows="1" class="form-control input-sm" id="'+i+'" name="RequirementField['+k.id+']['+i+']" placeholder="'+e.label+'"></textarea>';
                            } else if (e.type == 3) {
                                documentFields += '<input type="file" id="'+i+'" name="Image['+i+']" class="form-control input-sm" placeholder="'+e.label+'">';
                            }
                            documentFields += '</div></div>';
                        });
                        if (documentFields != '') {
                            requirementDocFields += '<div class="post-items bg-white padding-10"> \
                                                        <h2 class="text-cyan text-bold offset-bottom-10">'+k.Name +' Required Fields</h2> \
                                                        <div class="row">'
                                                         + documentFields + 
                                                        '</div> \
                                                    </div>';
                        }
                    });

                    if (requirementDocFields != '') {
                        $('#serviceApplicationModal #documentAdditionalFieldsCont').html(requirementDocFields);
                    } else {
                        $('#serviceApplicationModal #documentAdditionalFieldsCont').html('');
                    }

                    //clean error box
                    $('#ServiceReportForm').find('#error_message_box .error_messages').html('');
                    $('#ServiceReportForm').find('#error_message_box').addClass('hide');

                    $('#ServiceReportForm #ServiceCode').val(v.Code);

                    $('#serviceApplicationModal').modal({
                        backdrop : 'static',
                        keyboard : false
                    });
                    
                }
            },
            complete: function() {
                $.LoadingOverlay("hide");
            }
        });
    }

    /**
    * save application
    */
    this.submitServiceApplication = function(form)
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
                    var msgimg = $('#header-office-title').find('img').prop('src');
                    if (typeof(msgimg) == 'undefined') {
                        msgimg = $('.DepartmentLogo').prop('src');
                    }
                    msgimg2 = $('.ServiceLogo').prop('src');
                    var msgtpl = `<div class="row gutter-5">
                                    <div class="col-xs-2">
                                        <img style="max-width: 50px;margin: 5px auto;" src="${msgimg}">
                                    </div>
                                    <div class="col-xs-8 padding-top-10">
                                        Your Report has been Submitted and will be processed. <br>
                                        Thank you for using Mobile Government Integrated System of the Philippines.
                                    </div>
                                    <div class="col-xs-2">
                                        <img style="max-width: 50px;margin: 5px auto;" src="${msgimg2}">
                                    </div>
                                </div>`;
                    bootbox.alert({ 
                        size: null,
                        message: msgtpl, 
                        callback: function() {
                            $('#serviceApplicationModal').modal('hide');
                        }
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

}


var Organization = new Organization();
$(document).ready(function(){
    Organization._init();
});