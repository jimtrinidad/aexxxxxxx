function Organization() {

    // because this is overwritten on jquery events
    var self = this;

    this.selectedService;
    this.services = {};
    this.selectedViolations = {};

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

        $('#projectForm').submit(function(e){
            e.preventDefault();
            self.saveProject(this);
        });

        $('.add-violation-item').click(function(){
            self.addViolation();
        });

        $('#added-violation-list').on('click', '.remove_violation', function() {
            delete self.selectedViolations[$(this).data('id')];
            self.updateViolationList();
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

                if (response.status) {

                    var data = response.data;
                    $.each(data, function(i,e){
                        var tpl = `<div class="categorybox col-xs-12 col-sm-6 offset-top-10">
                                    <div class="bg-white">
                                        <div class="org-category">${e.category}</div>
                                        <div class="categoryitemcont row gutter-5">`;

                        $.each(e.items, function(j,k) {
                            var logo = window.public_url('assets/logo/' + k.Logo)
                            tpl += `<div class="col-xs-3 categoryitem">
                                        <div class="org-item" onclick="Organization.openServiceApplication('${k.Code}')">
                                            <div class="image" style="background-image: url(${logo});"></div>
                                            <div class='name'>${k.MenuName}</div>
                                        </div>
                                    </div>`;

                            self.services[k.Code] = k;
                        });

                        tpl += '</div></div></div>';

                        $('#LoadMainBody').append(tpl);
                    });

                    $('#LoadMainBody').imagesLoaded( function(){
                        var $grid = $('#LoadMainBody').isotope({
                          itemSelector : '.categorybox'
                        });

                        $('.categoryitemcont').isotope({
                            itemSelector : '.categoryitem'
                        });
                    });

                    // fix when reloading via ajax, to refresh the grid
                    var iso = $('#LoadMainBody').data('isotope');
                    if (typeof(iso) != 'undefined') {
                        $('#LoadMainBody').isotope('reloadItems');
                    }

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
        self.selectedViolations = {};
        self.updateViolationList();
        self.selectedService = code;
        $('#violation-list-items').val('').trigger('change');

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

                    if (v.Fee && v.Fee > 0) {
                        serviceTemplate.find('span.ServiceFee .fee-amount').text('P' + Utils.numberWithCommas(v.Fee));
                        serviceTemplate.find('span.ServiceFee').show();
                    } else {
                        serviceTemplate.find('span.ServiceFee').hide();
                    }

                    if (v.SubDepartment) {
                        serviceTemplate.find('span.DeptName').text(v.SubDepartment.Name);
                        serviceTemplate.find('img.DepartmentLogo').prop('src', window.public_url('assets/logo/' + v.SubDepartment.Logo));
                    } else {
                        serviceTemplate.find('span.DeptName').text(v.Department.Name);
                        serviceTemplate.find('img.DepartmentLogo').prop('src', window.public_url('assets/logo/' + v.Department.Logo));
                    }
                    serviceTemplate.find('img.ServiceLogo').prop('src', window.public_url('assets/logo/' + v.Logo));

                    serviceTemplate.find('.ServiceTags').html('');
                    $.each(v.Tags, function(i, e) {
                        serviceTemplate.find('.ServiceTags').append(' <label class="label label-'+Utils.getRandomItem(['info','success','danger','warning','primary'])+'">' + e + '</label> ');
                    });

                    if (v.ServiceType == 13) {
                        $('#serviceApplicationModal').find('div.additional-violation-box').removeClass('hide');
                    } else {
                        $('#serviceApplicationModal').find('div.additional-violation-box').addClass('hide');
                    }

                    // reset input erros
                    $.each($('#ServiceReportForm').find('input'), function(i,e){
                        $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger');
                        $(e).popover('destroy');
                    });

                    var additionalFields = '';
                    $.each(v.ExtraFields, function(i, e) {
                        additionalFields += '<div class="col-xs-6 col-sm-4"><div class="form-group" title="'+e.FieldLabel+'"><label class="control-label">'+e.FieldLabel+'</label>';
                        if (e.FieldType == 1) {
                            additionalFields += '<input type="text" id="'+e.FieldID+'" name="ExtraField['+e.FieldID+']" class="form-control input-sm" placeholder="'+e.FieldLabel+'" value="'+e.DefaultValue+'">';
                        } else if (e.FieldType == 2) {
                            additionalFields += '<textarea rows="1" class="form-control input-sm" id="'+e.FieldID+'" name="ExtraField['+e.FieldID+']" placeholder="'+e.FieldLabel+'">'+e.DefaultValue+'</textarea>';
                        } else if (e.FieldType == 3) {
                            additionalFields += '<input type="file" id="'+e.FieldID+'" name="Image['+e.FieldID+']" class="form-control input-sm" placeholder="'+e.FieldLabel+'">';
                        } else if (e.FieldType == 4 || e.FieldType == 5) {
                            var options = '';

                            if (e.DefaultValue) {
                                if (e.FieldType == 4) {
                                    options += `<option value=''>-- Please select --</option>`;
                                }
                                $.each(e.DefaultValue.split('|'), function(k,j){
                                    options += `<option>${j.trim()}</option>`;
                                });
                            } else {
                                options += `<option>--</option>`;
                            }

                            if (e.FieldType == 5) {
                                multiple = 'multiple="multiple"';
                                additionalFields += `<select class="form-control input-sm" multiple="multiple" id="${e.FieldID}" name="ExtraField[${e.FieldID}][]">${options}</select>`;
                            } else if (e.FieldType == 4) {
                                additionalFields += `<select class="form-control input-sm" id="${e.FieldID}" name="ExtraField[${e.FieldID}]">${options}</select>`;
                            }
                        }
                        additionalFields += '</div></div>';
                    });
                    if (additionalFields != '') {
                        $('#serviceApplicationModal #serviceAdditionalFieldsCont').html('<div class="post-items bg-white padding-10"> \
                                                                                            <h2 class="text-cyan text-bold offset-bottom-10">Required Data</h2> \
                                                                                            <div class="row gutter-5">'
                                                                                             + additionalFields + 
                                                                                            '</div> \
                                                                                        </div>');
                        $('#serviceApplicationModal #serviceAdditionalFieldsCont select[multiple="multiple"]').multiselect({
                            enableFiltering: true,
                            enableCaseInsensitiveFiltering: true,
                            filterBehavior: 'text',
                            nonSelectedText: '-- Please select --',
                            maxHeight: 300,
                            buttonWidth: '100%',
                            buttonClass: 'btn btn-default btn-sm text-left',
                            numberDisplayed: 1
                        });
                    } else {
                        $('#serviceApplicationModal #serviceAdditionalFieldsCont').html('');
                    }

                    var requirementDocFields = '';
                    $.each(v.Requirements, function (j, k) {
                        var documentFields = '';
                        $.each(k.extraFields, function(i, e) {
                            documentFields += '<div class="col-xs-6 col-sm-4"><div class="form-group" title="'+e.label+'"><label class="control-label">'+e.label+'</label>';
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
                            requirementDocFields += '<div class="post-items bg-white padding-10 offset-top-10"> \
                                                        <h2 class="text-cyan text-bold offset-bottom-10">'+k.Name +' Required Fields</h2> \
                                                        <div class="row gutter-5">'
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
    * add violation to current application
    */
    this.addViolation = function()
    {
        var selected = $('#violation-list-items').val();
        if (selected != '' && selected != self.selectedService && !(selected in self.selectedViolations)) {
            self.selectedViolations[selected] = self.services[selected];
            self.updateViolationList();
        }
    }

    this.updateViolationList = function()
    {
        var tpl = '';
        $.each(self.selectedViolations, function(i,v) {
            tpl += `<div class="padding-5">
                        <button style="color: red; font-weight: bold" type="button" data-id="${v.Code}" class="close remove_violation" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h2><span class="text-bold text-green">${v.Name} - ${v.MenuName}</span></h2>
                        <span style="font-family:Trebuchet MS; font-size:12px;">${v.Description}</span>
                        <span style="font-family:Trebuchet MS; font-size:12px;">
                            <br>Fee: <span class="fee-amount text-orange">P${v.Fee}</span>
                        </span>
                    </div>`;
        });

        $('#added-violation-list').html(tpl);
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

        formData.append('addon-violation', Object.keys(self.selectedViolations));
        
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


    /**
    * add project
    */
    this.addProject = function()
    {   
        // reset form data
        $('#projectForm').trigger("reset");

        // reset input erros
        $.each($('#projectForm').find('input'), function(i,e){
            $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger');
            $(e).popover('destroy');
        });
        //clean error box
        $('#projectForm').find('#error_message_box .error_messages').html('');
        $('#projectForm').find('#error_message_box').addClass('hide');

        $('#projectForm #id').val('');

        $('#projectModal .modal-title').html('<b>Project</b> | Add');
        $('#projectModal').modal({
            backdrop : 'static',
            keyboard : false
        });
    }

}


var Organization = new Organization();
$(document).ready(function(){
    Organization._init();
});