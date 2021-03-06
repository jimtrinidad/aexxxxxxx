function Services() {

    // because this is overwritten on jquery events
    var self = this;

    this.servicesData = {};
    this.organizationCategoriesData = {};
    this.supportChanged = false;
    this.officerFinderSelected;

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
        $('#organizationForm').submit(function(e) {
            e.preventDefault();
            self.saveOrganization(this);
        });
    }

    /**
    * usage of different libraries and plugins
    */
    this.set_configs = function()
    {
        $('.serviceStatusToggle').change(function(e){
            self.updateServiceStatus(this);
        });
    }

    this.getService = function(id)
    {   
        var match = false;
        $.each(self.servicesData, function(i,e){
            if (e.id == id) {
                match = e;
                return false;
            }
        });

        return match;
    }

    this.updateServiceStatus = function(elem)
    {
        var checkbox    = $(elem);
        var data        = checkbox.data();
        var status      = checkbox.is(":checked");
        $.ajax({
            url: window.base_url('services/status/' + data.code),
            type: 'post',
            data: {'status' : status},
            success: function (response) {
                if (!response.status) {
                    // failed
                    bootbox.alert(response.message, function(){
                        location.reload();
                    })
                }
            }
        });
    }

    /**
    * save account approval
    */
    this.approveService = function(id)
    {
        var serviceData = self.getService(id);
        if (serviceData) {
            bootbox.confirm('Approve and enable service <b>' + serviceData.Name + ' (<span class="text-red">' + serviceData.Code + '</span>)</b>?', function(r){
                if (r) {
                    $.LoadingOverlay("show", {zIndex: 999});
                    $.ajax({
                        url: window.base_url('services/approve/' + serviceData.Code),
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
    * delete service
    */
    this.deleteService = function (id)
    {
        var serviceData = self.getService(id);
        if (serviceData) {
            bootbox.confirm('Are you sure you want to <label class="label label-danger">delete</label> service <b>' + serviceData.Name + ' (<span class="text-red">' + serviceData.Code + '</span>)</b>?', function(r){
                if (r) {
                    $.LoadingOverlay("show", {zIndex: 999});
                    $.ajax({
                        url: window.base_url('services/delete/' + serviceData.Code),
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
    * show service supports
    */
    this.showSupports = function (id)
    {
        var serviceData = self.getService(id);
        if (serviceData) {

            self.supportChanged = false;
            self.officerFinderSelected = null;
            $('#findOfficerBox').val('');

            $('#supportListModal .serviceName').text(serviceData.Name);
            $('#supportListModal #ServiceCode').val(serviceData.Code);

            var currentOfficers = serviceData.Supports;

            if (currentOfficers.length > 0) {
                $('#supportListModal #result_message').text('').addClass('hidden');
                var template = '';
                $.each(currentOfficers, function(i,e){
                    template += '<tr id="officer_row_'+e.id+'" data-id="'+e.id+'">';
                    template += '<td><img class="logo-small" src="' + window.public_url() + 'assets/profile/' + e.Photo + '"></td>';
                    template += '<td>' + e.MabuhayID + '</td>';
                    template += '<td>' + e.FirstName + ' ' + e.LastName + '</td>';
                    template += '<td>' + e.AccountLevel + '</td>';
                    template += '<td> \
                                    <div class="box-tools"> \
                                        <div class="input-group pull-right" style="width: 10px;"> \
                                          <div class="input-group-btn"> \
                                            <button type="button" class="btn btn-xs btn-default" onClick="Services.removeSupport(this)"><i class="fa fa-trash"></i></button> \
                                          </div> \
                                        </div> \
                                    </div>  \
                                </td>';
                    template += '</tr>';
                });
                $('#supportListModal #tableBody').html(template);
                $('#supportListModal #tableData').removeClass('hidden');
            } else {
                $('#supportListModal #tableBody').html('');
                $('#supportListModal #tableData').addClass('hidden');
                $('#supportListModal #result_message').text('No support assigned.').removeClass('hidden');
                
            }


            try { $('#findOfficerBox').typeahead('destroy'); } catch(e) {}
            $('#findOfficerBox').typeahead({
                hint: false,
                minLength: 5,
            },
            {
                templates: {
                    empty: [
                        '<div class="padding-left-10 empty-message">',
                          'No match found.',
                        '</div>'
                    ].join('\n'),
                    suggestion: function (item) {
                        item.address.pop();
                        return '<div class="row gutter-0">' +
                                    '<div class="col-xs-2">' +
                                        '<span>' +
                                        '<img style="width:45px;height:45px;margin: 0 auto;" src="' + window.public_url() + "assets/profile/"+item.photo+'">' +
                                        '</span></div>' +
                                    '<div class="col-xs-10 small" style="padding-left: 10px;">' +
                                        '<div>'+ item.mabuhayID +'<small> - '+ item.fullname + '</small></div>' +
                                        '<div><small>'+ item.aclevel +'</small></div>' +
                                        '<div><small>'+ item.address.join(', ') +'</small></div>' +
                                    '</div>' +
                                '</div>';
                    }
                },
                name: 'user',
                display: 'mabuhayID',
                source: function(query, syncResults, asyncResults) {
                    try {clearTimeout(typeaheadTimeout);} catch (e) {}
                    typeaheadTimeout = setTimeout(function (){
                        $.get(window.base_url('services/find_officer') + "?q=" + query, function(responseData) {
                            asyncResults(responseData);
                        });
                    }, 500);
                }
            }).bind('typeahead:select', function(ev, item) {
                var id = $(ev.target).prop('id');
                self.officerFinderSelected = item;
            }).bind('typeahead:change', function(ev, value) {
                var id = $(ev.target).prop('id');
                if (self.officerFinderSelected && self.officerFinderSelected.mabuhayID != value) {
                    self.officerFinderSelected = null;
                    $(ev.target).typeahead('val', '');
                }
            });

            $('#supportListModal').modal({
                backdrop : 'static',
                keyboard : false
            });

            $('#supportListModal').on('hide.bs.modal', function (e) {
                // refresh list
                if (self.supportChanged) {
                    location.reload();
                }
            });
        }
    }

    this.addSupport = function()
    {
        var code = $('#supportListModal #ServiceCode').val();
        if (self.officerFinderSelected && code) {
            $('#supportListModal .modal-body').LoadingOverlay('show');
            $.ajax({
                url: window.base_url('services/add_support/' + code),
                type: 'GET',
                data: {'officer' : self.officerFinderSelected.id},
                success: function (response) {
                    if (response.status) {
                        var template = '';
                        var e = self.officerFinderSelected;
                        template += '<tr id="officer_row_'+e.id+'" data-id="'+e.id+'">';
                        template += '<td><img class="logo-small" src="' + window.public_url() + 'assets/profile/' + e.photo + '"></td>';
                        template += '<td>' + e.mabuhayID + '</td>';
                        template += '<td>' + e.fullname + '</td>';
                        template += '<td>' + e.aclevel + '</td>';
                        template += '<td> \
                                        <div class="box-tools"> \
                                            <div class="input-group pull-right" style="width: 10px;"> \
                                              <div class="input-group-btn"> \
                                                <button type="button" class="btn btn-xs btn-default" onClick="Services.removeSupport(this)"><i class="fa fa-trash"></i></button> \
                                              </div> \
                                            </div> \
                                        </div>  \
                                    </td>';
                        template += '</tr>';

                        $('#supportListModal #result_message').text('').addClass('hidden');
                        $('#supportListModal #tableBody').append(template);
                        $('#supportListModal #tableData').removeClass('hidden');

                        self.supportChanged = true;
                    }
                }, 
                complete: function() {
                    $('#supportListModal .modal-body').LoadingOverlay('hide');
                    $('#findOfficerBox').typeahead('val', '');
                }
            });
        }
    }


    this.removeSupport = function(element)
    {
        var row   = $(element).closest('tr');
        var id    = row.data('id');
        var name  = row.find('td:nth-child(3)').text();
        var code  = $('#supportListModal #ServiceCode').val();

        bootbox.confirm('Are you sure you want to remove ' + name + ' from supports?' , function(result){
            if (result) {
                $.get(window.base_url('services/remove_support/' + code), {'officer' : id}).done(function(response) {
                    if (response.status) {
                        row.fadeOut('fast', function(){
                            row.remove();
                            if (!$('#supportListModal #tableBody tr').length) {
                                $('#supportListModal #tableData').addClass('hidden');
                                $('#supportListModal #result_message').text('No more officer assigned').removeClass('hidden');
                            }
                        });
                        self.supportChanged = true;
                    } else {
                        bootbox.alert(response.message);
                    }
                });
            }
        });
    }

    // END SUPPORT


    /**
    * Organization setup
    */

    this.showOrganization = function(id)
    {
        var serviceData = self.getService(id);
        if (serviceData) {
            // console.log(serviceData);
            // reset form data
            $('#organizationForm').trigger("reset");

            // reset input erros
            $.each($('#organizationForm').find('input, select'), function(i,e){
                $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger');
                $(e).popover('destroy');
            });

            //clean error box
            $('#organizationForm').find('#error_message_box .error_messages').html('');
            $('#organizationForm').find('#error_message_box').addClass('hide');

            $('#organizationModal .serviceLogo').prop('src', window.public_url() + "assets/logo/" + serviceData.Logo);

            var org = (serviceData.SubDepartment ? serviceData.SubDepartment.Name : serviceData.Department.Name);
            $('#organizationModal .infoBox').html(
                '<h5><b>Service Name: </b> ' + serviceData.Name + '</h5>' +
                '<h5><b>Organization: </b> ' + org + '</h5>' +
                '<h5><b>Address: </b> ' + Object.values(serviceData.Location).slice(-2) + '</h5>'
            );

            var orgCategories = self.organizationCategoriesData[serviceData.SubDepartmentID];
            var orgCatOpts = '';
            $.each(orgCategories, function(i,e) {
                orgCatOpts += `<option value='${i}'>${e}</option>`;
            });
            $('#organizationForm #Category').html(orgCatOpts);

            if (serviceData.Organization) {
                var org = serviceData.Organization;
                $('#organizationForm #id').val(org.id);
                $('#organizationForm #MenuName').val(org.MenuName);
                $('#organizationForm #Keyword').val(org.Keyword);
                $('#organizationForm #Category').val(org.Category);
            }

            $('#organizationForm #Status').val(serviceData.InOrganization);
            $('#organizationForm #ServiceID').val(serviceData.id);

            $('#organizationModal .serviceCode').text(serviceData.Code);
            $('#organizationModal').modal({
                backdrop : 'static',
                keyboard : false
            });
        }
    }

    this.saveOrganization = function(form)
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
                console.log(response);
                if (response.status) {
                    bootbox.alert(response.message, function(){
                        location.reload(); //easy way, just reload the page
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


var Services = new Services();
$(document).ready(function(){
    Services._init();
});