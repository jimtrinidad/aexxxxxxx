function Quickserve() {
    // because this is overwritten on jquery events
    var self = this;

    this.items = {};
    this.currentUser;
    this.paymentChange;

    /**
     * Initialize events
     */
    this._init = function()
    {

        self.set_events();
		self.set_configs();

        self.currentUser = $('#chat_current_user').val();

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

        $('#paymentForm').submit(function(e) {
            e.preventDefault();
            self.savePayment(this);
        });

        $('#feedbackForm').submit(function(e) {
            e.preventDefault();
            self.addFeedback(this);
        });

        $('.paymentPreviewButton').click(function(e) {
            e.preventDefault();
            self.paymentReceipt($(this).data('id'));
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

        $.LoadingOverlay("show", {zIndex: 999});
        $.ajax({
            url: window.base_url('quickserve/details/' + data.ApplicationCode),
            type: 'GET',
            success: function (response) {
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

        $(form).find('#error_message_box .error_messages').html('');
        $(form).find('#error_message_box').addClass('hide');

        var formData = new FormData(form);
        
        $.ajax({
            url: $(form).prop('action'),
            type: 'POST',
            data: formData,
            success: function (response) {

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


    /**
    * PAYMENT
    */

    this.setPayment = function(elem)
    {
        var safID = $(elem).closest('tr').data('safid');
        var data = self.getItem(safID);

        // reset form data
        $('#paymentForm').trigger("reset");

        self.paymentChange = false;

        //clean error box
        $('#paymentForm').find('#error_message_box .error_messages').html('');
        $('#paymentForm').find('#error_message_box').addClass('hide');

        if (data.paymentInfo) {
            var pinfo = data.paymentInfo;
            $('#paymentForm').find('#scope').val(pinfo.scope);
            $('#paymentForm').find('#date').val(pinfo.date);
            $('#paymentForm').find('#type').val(pinfo.type);
            $('#paymentForm').find('#treasurer').val(pinfo.treasurer);

            if (pinfo.payor) {
                $('#paymentForm').find('#payor').val(pinfo.payor);
            } else {
                if (data.isReport && typeof(data.ExtraFields[0]) != 'undefined') {
                    $('#paymentForm').find('#payor').val(data.ExtraFields[0]);
                } else {
                    $('#paymentForm').find('#payor').val(data.FirstName + ' ' + data.LastName);
                }
            }

            var rows = ''
            $.each(pinfo.collections, function(i, e){
                rows += `<tr>
                            <td><input type="text" autocomplete="off" name="collectionName[]" class="form-control input-sm" value="${e.name}"></td>
                            <td><input type="text" autocomplete="off" name="collectionCode[]" class="form-control input-sm"  value="${e.code}"></td>
                            <td><input type="number" step=".01" autocomplete="off" name="collectionAmount[]" class="form-control input-sm" value="${e.amount}"></td>
                            <td style="vertical-align:middle;"></td>
                        </tr>`;
            });

            $('#paymentForm #collectionBody').html(rows);

            if ($('#paymentForm #collectionBody tr').length > 1) {
                $.each($('#paymentForm #collectionBody tr'), function(i,e) {
                    $(e).find('td:last-child').html('<a class="btn btn-xs btn-danger" onclick="Quickserve.removeCollectionRow(this)" href="javascript:;"><i class="fa fa-remove"><i></a>');
                });
            }

            $('#paymentForm #paymentPreviewButtonCont').removeClass('hide');
            $('#paymentForm .paymentPreviewButton').data('id', pinfo.id);

        } else {
            $('#paymentForm').find('#scope').val(data.Scope);
            $('#paymentForm').find('#date').val(moment().format("MM/DD/YYYY"));
            $('#paymentForm').find('#treasurer').val($('#current_user_name').val());

            if (data.isReport && typeof(data.ExtraFields[0]) != 'undefined') {
                $('#paymentForm').find('#payor').val(data.ExtraFields[0]);
            } else {
                $('#paymentForm').find('#payor').val(data.FirstName + ' ' + data.LastName);
            }

            var amount = '';
            if (data.Fee) {
                amount = data.Fee;
            }

            var row = `<tr>
                            <td><input type="text" autocomplete="off" name="collectionName[]" class="form-control input-sm" value="${data.ServiceName}"></td>
                            <td><input type="text" autocomplete="off" name="collectionCode[]" class="form-control input-sm"  value=""></td>
                            <td><input type="number" step=".01" autocomplete="off" name="collectionAmount[]" class="form-control input-sm" value="${amount}"></td>
                            <td style="vertical-align:middle;"></td>
                        </tr>`;

            $('#paymentForm #collectionBody').html(row);

            $('#paymentForm #paymentPreviewButtonCont').addClass('hide');
        }
        $('#paymentForm #safID').val(safID);

        $('#paymentModal .modal-title .taskName').html('<b>' + data.documentName + '</b> - Payment details');
        $('#paymentModal').modal({
            backdrop : 'static',
            keyboard : false
        });

        $('#paymentModal').on('hide.bs.modal', function (e) {
            // refresh list
            if (self.paymentChange) {
                location.reload();
            }
        });
    }

    /**
    * save approve or decline
    */
    this.savePayment = function(form)
    {
        // prenvet multiple calls
        if ($(form).data('running')) {
            return false;
        }

        $(form).data('running', true);
        $(form).find('input').blur();
        $(form).LoadingOverlay("show");

        $(form).find('#error_message_box .error_messages').html('');
        $(form).find('#error_message_box').addClass('hide');

        var formData = new FormData(form);
        
        $.ajax({
            url: $(form).prop('action'),
            type: 'POST',
            data: formData,
            success: function (response) {

                if (response.status) {
                    var msgtpl = `<div>
                                    <div>Service payment details has been saved successfully. </div>
                                    <button type="button" onclick="Quickserve.paymentReceipt(${response.id})" class="btn bg-orange btn-xs text-white offset-top-5"><i class="fa fa-file-text"></i> View Receipt</button>
                                </div>`;
                    bootbox.alert(msgtpl);

                    self.paymentChange = true;

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

    this.paymentReceipt = function(payment_id)
    {

        bootbox.hideAll();
        var _c = new Date().getTime();
        $("#receiptModal iframe").contents().find("body").html("");
        $("#receiptModal iframe").attr({'src': window.public_url('quickserve/payment_preview') + '/?c='+_c+'&id='+payment_id});

        $('#receiptModal').modal({
            backdrop : 'static',
            keyboard : false,
        });
    }

    this.addCollectionRow = function()
    {
        var clone = $('#paymentForm #collectionBody').find('tr:eq(0)').clone();
        clone.find('input').val('');
        $('#paymentForm #collectionBody').append(clone);
        if ($('#paymentForm #collectionBody tr').length > 1) {
            $.each($('#paymentForm #collectionBody tr'), function(i,e) {
                $(e).find('td:last-child').html('<a class="btn btn-xs btn-danger" onclick="Quickserve.removeCollectionRow(this)" href="javascript:;"><i class="fa fa-remove"><i></a>');
            });
        }
    }

    this.removeCollectionRow = function(elem)
    {   
        $(elem).closest('tr').remove();
        if ($('#paymentForm #collectionBody tr').length <= 1) {
            $.each($('#paymentForm #collectionBody tr'), function(i,e) {
                $(e).find('td:last-child').html('');
            });
        }
    }

    // END PAYMENT


    /**
    * FEEDBACK
    */
    this.viewFeedbacks = function(elem)
    {
        var safID = $(elem).closest('tr').data('safid');
        var data = self.getItem(safID);

        // reset form data
        $('#feedbackForm').trigger("reset");

        $('#feedbackModal').find('.userName').text(data.FirstName + ' ' + data.LastName);
        $('#feedbackModal').find('#mID').val(data.MabuhayID);

        $('#feedbackModal .comments-list').html('');

        $('#feedbackModal').modal({
            backdrop : 'static',
            keyboard : false
        });

        var uploadField = document.getElementById("Attachment");
        if (uploadField) {
            uploadField.onchange = function() {
                if(this.files[0].size > 2097152){
                   bootbox.alert("Selected file is too big.");
                   this.value = "";
                };
            };

            autosize($('#feedbackModal textarea'));
        }

        self.loadFeedbacks(data.MabuhayID)
    }

    this.loadFeedbacks = function(mabuhayID)
    {
        $('#feedbackModal .comments-list').LoadingOverlay("show");
        $.ajax({
            url: window.base_url('quickserve/user_feedbacks'),
            type: 'POST',
            data: {
                mID: mabuhayID
            },
            success: function (response) {
                if (response.status) {
                    var tpl = '';
                    $.each(response.data, function(i,e){
                        tpl += self.showFeedbackItem(e);
                    });
                    
                    $('#feedbackModal .comments-list').html(tpl);

                    $('#feedbackModal .norecord').addClass('hide');
                    $('#feedbackModal .comments-list').removeClass('hide');
                } else {
                    $('#feedbackModal .norecord').removeClass('hide');
                    $('#feedbackModal .comments-list').addClass('hide');
                }
            },
            complete: function() {
                $('#feedbackModal .comments-list').LoadingOverlay("hide");
            }
        });
    }

    this.addFeedback = function(form)
    {
        // prenvet multiple calls
        if ($(form).data('running')) {
            return false;
        }

        $(form).data('running', true);
        $(form).find('input').blur();
        $(form).LoadingOverlay("show");

        $(form).find('#error_message_box .error_messages').html('');
        $(form).find('#error_message_box').addClass('hide');

        var formData = new FormData(form);
        
        $.ajax({
            url: $(form).prop('action'),
            type: 'POST',
            data: formData,
            success: function (response) {
                if (response.status) {
                    $('#feedbackForm').trigger("reset");
                    var tpl = self.showFeedbackItem(response.data);
                    $('#feedbackModal .comments-list').prepend(tpl);

                    autosize.update($('#feedbackForm textarea'));

                    $('#feedbackModal .norecord').addClass('hide');
                    $('#feedbackModal .comments-list').removeClass('hide');

                } else {
                    // bootbox.alert(response.message);
                    $.each(response.fields, function(i,e){
                        $(form).find('#'+i).prop('title', e).closest('div').addClass('has-error').find('label').removeClass('text-white').addClass('text-danger');
                        Utils.popover($('#'+i), {
                            t: 'hover',
                            p: 'top',
                            m: e
                        });
                        $(form).find('#error_message_box .error_messages').append('<p>' + e + '</p>');
                    });
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

    this.removeFeedback = function(id)
    {
        bootbox.confirm('Are you sure you want to remove this feedback?', function(r){
            if (r) {
                $('#feedbackModal .comments-list').LoadingOverlay("show");
                $.ajax({
                    url: window.base_url('quickserve/remove_feedback/' + id),
                    type: 'GET',
                    success: function (response) {
                        if (response.status) {
                            $('#feedback_item_' + id).fadeOut();
                        }
                    },
                    complete: function() {
                        $('#feedbackModal .comments-list').LoadingOverlay("hide");
                    }
                });
            }
        });
    }

    this.showFeedbackItem = function(e)
    {
        var ctime = moment(e.DateAdded).fromNow();
        var photo = window.public_url('assets/profile/' + e.Photo);
        var attachment = '';
        var removeButton = '';

        if (e.Attachment) {
            var url = window.public_url('assets/etc/' + e.Attachment);
            attachment = `<p class="padding-top-5"><small class="small">Attachment:<br><i class="fa fa-paperclip" aria-hidden="true"></i> <a target="_blank" href="${url}">${e.Attachment}</a></small></p>`;
        }

        if (e.PostedBy == self.currentUser) {
            removeButton = `<div class="text-right padding-top-10"><a href="javascript:;" onclick="Quickserve.removeFeedback(${e.id})">X</a></div>`;
        }

        return `<div class="media" id="feedback_item_${e.id}">
                    <div class="pull-right">
                        <small>${ctime}</small>
                        ${removeButton}
                    </div>
                    <a class="media-left" href="#">
                        <img src="${photo}" style="max-width: 50px;">
                    </a>
                    <div class="media-body">
                        <h4 class="media-heading user_name">${e.Sender}</h4>
                        ${e.Message}
                        ${attachment}
                    </div>
                </div>`;
    }


}

var Quickserve = new Quickserve();
$(document).ready(function(){
    Quickserve._init();
});