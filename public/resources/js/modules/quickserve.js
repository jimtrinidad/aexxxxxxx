function Quickserve() {
    // because this is overwritten on jquery events
    var self = this;
    this.items = {};

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

    	$('#approveForm').submit(function(e) {
            e.preventDefault();
            self.saveForm(this);
        });

        $('#declineForm').submit(function(e) {
            e.preventDefault();
            self.saveForm(this);
        });

    }

    /**
    * usage of different libraries and plugins
    */
    this.set_configs = function()
    {
        
    }

    /**
    * get item data
    */
    this.getItem = function(id)
    {   
        var match = false;
        $.each(self.items, function(i,e){
            if (e.safID == id) {
                match = e;
                return false;
            }
        });

        return match;
    }


    /**
    * view applicant and application details
    */
    this.viewDetails = function(elem)
    {
        var safID = $(elem).closest('tr').data('safid');
        var data = self.getItem(safID);

        console.log(data);

        $.LoadingOverlay("show", {zIndex: 999});
        $.ajax({
            url: window.base_url('quickserve/details/' + data.ApplicationCode),
            type: 'GET',
            success: function (response) {
                console.log(response);
                if (response.status) {

                    var detailsCont = $('#detailsModal .modal-body');
                    var service     = response.data.service;
                    var user        = response.data.applicant;
                    var application = response.data.application;

                    // service info
                    detailsCont.find('span.DeptName').text(service.Department.Name);
                    detailsCont.find('span.ServiceName').text(service.Name);
                    detailsCont.find('span.ServiceDesc').text(service.Description);
                    detailsCont.find('span.ServiceZone').text(Object.values(service.Location).join(' > '));
                    detailsCont.find('span.ServiceCode').text(service.Code);
                    detailsCont.find('img.DepartmentLogo').prop('src', window.public_url('assets/logo/' + service.Department.Logo));
                    detailsCont.find('img.ServiceLogo').prop('src', window.public_url('assets/logo/' + service.Logo));

                    detailsCont.find('.ServiceTags').html('');
                    $.each(service.Tags, function(i, e) {
                        detailsCont.find('.ServiceTags').append(' <label class="label label-'+Utils.getRandomItem(['info','success','danger','warning','primary'])+'">' + e + '</label> ');
                    });

                    // applicant info
                    detailsCont.find('span.info-mabuhayID').text(user.MabuhayID);
                    detailsCont.find('span.info-name').text(user.Fullname);
                    detailsCont.find('span.info-birthday').text(user.BirthDate);
                    detailsCont.find('span.info-civil').text(user.Civil);
                    detailsCont.find('span.info-email').text(user.EmailAddress);
                    detailsCont.find('span.info-gender').text(user.Gender);
                    detailsCont.find('span.info-contact').text(user.ContactNumber);
                    detailsCont.find('span.info-education').text(user.Education);
                    detailsCont.find('span.info-livelihood').text(user.Livelihood);
                    detailsCont.find('span.info-address').text(user.Address);
                    detailsCont.find('img.info-photo').prop('src', window.public_url('assets/profile/' + user.Photo));

                    // other data
                    if (application.otherFields.length) {
                        detailsCont.find('#otherDataCont').find('dl.items').html('');
                        $.each(application.otherFields, function(i, e) {
                            if (e.type == 3) {
                                detailsCont.find('#otherDataCont').find('dl.items').append('<dt class="text-bold padding-5">' + e.label + '</dt><dd class="padding-5"><img class="img-responsive" src="'+ e.value +'" style="max-width: 80px;"></dd>');
                            } else {
                                detailsCont.find('#otherDataCont').find('dl.items').append('<dt class="text-bold padding-5">' + e.label + '</dt><dd class="padding-5">'+ e.value +'</dd>');
                            }
                        });
                        detailsCont.find('#otherDataCont').show();
                    } else {
                        detailsCont.find('#otherDataCont').hide();
                    }

                    // requirements
                    if (application.requirements.length) {
                        detailsCont.find('#requirementsCont').find('tbody.items').html('');
                        $.each(application.requirements, function(i, e) {
                            detailsCont.find('#requirementsCont').find('tbody.items').append(
                                    '<tr valign="center">' +
                                        '<td><img class="img-responsive" src="'+ window.public_url('assets/logo/' + e.logo) +'" style="max-width: 50px;"></td>' +
                                        '<td>' + e.name + '</td>' +
                                        '<td>' + e.desc + '</td>' +
                                        '<td>' + e.status + '</td>' +
                                        '<td>' + e.update + '</td>' +
                                        '<td><a class="btn btn-sm btn-info" href="'+window.public_url('get/application_doc/' + e.code + '/' + user.id)+'" target="_blank">View</a></td>' +
                                    '</tr>'
                                );
                        });
                        detailsCont.find('#requirementsCont').show();
                    } else {
                        detailsCont.find('#requirementsCont').hide();
                    }

                    $('#detailsModal').modal({
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
    * approve application function form
    */
    this.approveProcess = function(elem)
    {
    	var safID = $(elem).closest('tr').data('safid');
    	var data = self.getItem(safID);

    	// reset form data
        $('#approveForm').trigger("reset");

        //clean error box
        $('#approveForm').find('#error_message_box .error_messages').html('');
        $('#approveForm').find('#error_message_box').addClass('hide');

        $('#approveForm #safID').val(safID);

        $('#approveModal .modal-title .taskName').html(data.documentName + ' ( '+data.FunctionName+' )');
        $('#approveModal').modal({
            backdrop : 'static',
            keyboard : false
        });

    }

    /**
    * decline application function form
    */
    this.declineProcess = function(elem)
    {
    	var safID = $(elem).closest('tr').data('safid');
    	var data = self.getItem(safID);

    	// reset form data
        $('#declineForm').trigger("reset");

        //clean error box
        $('#declineForm').find('#error_message_box .error_messages').html('');
        $('#declineForm').find('#error_message_box').addClass('hide');

        $('#declineForm #safID').val(safID);

        $('#declineModal .modal-title .taskName').html(data.documentName + ' ( '+data.FunctionName+' )');
        $('#declineModal').modal({
            backdrop : 'static',
            keyboard : false
        });

    }


    /**
    * save approve or decline
    */
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
        
        $.ajax({
            url: $(form).prop('action'),
            type: 'POST',
            data: formData,
            success: function (response) {
            	// console.log(response);
                if (response.status) {
                	bootbox.alert(response.message, function() {
                		location.reload(); //easy way, just reload the page
                	});
                } else {
                    // bootbox.alert(response.message);
                    $(form).find('#error_message_box .error_messages').append('<p><b>' + response.message + '</b></p>');
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

var Quickserve = new Quickserve();
$(document).ready(function(){
    Quickserve._init();
});