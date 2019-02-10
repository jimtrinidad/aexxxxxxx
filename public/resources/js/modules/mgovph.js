function Mgovph() {

    // because this is overwritten on jquery events
    var self = this;

    this.feedTimeout;
    this.selectedService;

    /**
     * Initialize events
     */
    this._init = function() 
    {

        self.set_events();
        self.set_configs();
        self.load_govt_ranking();
        self.load_trending_service();
        self.load_organization_ranking();
        self.load_currencies();

    },

    /**
    * events delaration
    */
    this.set_events = function()
    {
        /**
        * on service click
        * open details
        */
        $('#LoadMainBody').on('click', '.serviceItem .DepartmentService', function(){
            self.openServiceDetails(this);
        });

        $('#ServiceApplicationForm').submit(function(e){
            e.preventDefault();
            self.submitServiceApplication(this);
        });

        self.moveSidebar();

        $(window).resize(function(){
            self.moveSidebar();
        });

    }

    /**
    * usage of different libraries and plugins
    */
    this.set_configs = function()
    {

        $('#home-banners').carousel();

        var serverTime = window.timestamp*1000;
        var localTime = +Date.now();
        var timeDiff = serverTime - localTime;
        setInterval(function () {
            $('#system-time').html(moment(+Date.now() + timeDiff).format('dddd, MMMM DD, YYYY, h:mm:ss A'));
        }, 1000);

    },


    /**
    * get and set government performance ranking
    */
    this.load_govt_ranking = function()
    {
        if ($('#govt-ranking-cont').length) {
            $('#govt-ranking-cont').LoadingOverlay("show");
            $.ajax({
                url  : window.base_url('get/performance_ranking'),
                type : 'get',
                cache: true,
                success : function(response) {
                    if (response.status) {
                        var dept_rows = '';
                        $.each(response.data.department, function(i, e) {
                            dept_rows += '<li>'+ e.code +'<span>'+ e.count +'</span></li>';
                        });
                        $('#govt-ranking-cont-dept').html(dept_rows);
                        var city_rows = '';
                        $.each(response.data.city, function(i, e) {
                            city_rows += '<li>'+ e.name +'<span>'+ e.count +'</span></li>';
                        });
                        $('#govt-ranking-cont-city').html(city_rows);
                    }
                },
                complete: function() {
                    $('#govt-ranking-cont').LoadingOverlay("hide");
                }
            });
        }
    }

    /**
    * get and set government performance ranking
    */
    this.load_trending_service = function()
    {
        if ($('#trending-service-cont').length) {
            $('#trending-service-cont').LoadingOverlay("show");
            $.ajax({
                url  : window.base_url('get/get_trending_services'),
                type : 'get',
                success : function(response) {
                    if (response.status) {
                        $('#trending-service-cont').removeClass('hide');
                        var rows = '';
                        $.each(response.data, function(i, e) {
                            rows += '<li><a href="'+public_url('services?v=' + e.Code)+'">'+ e.Name + '</a></li>';
                        });
                        $('#trending-service-items').html(rows);
                    }
                },
                complete: function() {
                    $('#trending-service-cont').LoadingOverlay("hide");
                }
            });
        }
    }

    /**
    * get and set government performance ranking
    */
    this.load_organization_ranking = function()
    {
        var cont = $('#org-ranking-cont');
        if (cont.length) {
            cont.LoadingOverlay("show");
            var all = false;
            var view = cont.find('.org-ranking-view-button').data('view');
            if (view == 'all') {
                all = true;
                cont.find('.org-ranking-view-button').data('view', 'partial');
                cont.find('.org-ranking-view-button').text('Top 20');
            } else {
                cont.find('.org-ranking-view-button').data('view', 'all');
                cont.find('.org-ranking-view-button').text('View All');
            }
            $.ajax({
                url  : window.base_url('get/organization_report_rank' + (all ? '?all' : '')),
                type : 'get',
                cache: true,
                success : function(response) {
                    if (response.status) {
                        cont.find('h2').text(response.name + ' Performance');
                        // var tpl = `<div class="col-xs-6">`;

                        // tpl += `<ul>`;

                        // $.each(response.data, function(i,e) {
                        //     tpl += `<li>${e.FirstName.substr(0, 1)}. ${e.LastName}<span>${e.Applications}</span></li>`;
                        //     if (i == 9) {
                        //         tpl += `</ul></div><div class="col-xs-6"><ul>`;
                        //     }
                        // });

                        // tpl += `</ul></div>`;

                        cont.find('div.row').html(self.generate_organization_ranking(response.data));

                        cont.removeClass('hide');
                    }
                },
                complete: function() {
                    cont.LoadingOverlay("hide");
                }
            });
        }
    }

    this.generate_organization_ranking = function(data)
    {

        mid = Math.ceil(data.length/2),
        obj = {
            left: data.slice(0, mid),
            right: data.slice(mid)
        };

        var tpl = '';
        var ctr = 1;
        $.each(obj, function(j,k){
            tpl += `<div class="col-xs-6"><ul>`;
            $.each(k, function(i,e){
                tpl += `<li><i class="small text-cyan">${ctr})</i>&nbsp; ${e.FirstName.substr(0, 1)}. ${e.LastName}<span>${e.Applications}</span></li>`;
                ctr++;
            });
            tpl += `</ul></div>`;
        });

        return tpl;
    }

    /**
    * load currencies
    */
    this.load_currencies = function()
    {
        var cont = $('#currency-cont');
        if (cont.length) {
            cont.LoadingOverlay("show");
            $.ajax({
                url  : window.base_url('get/currencies'),
                type : 'get',
                cache: true,
                success : function(response) {
                    if (response.status) {

                        cont.find('.date-rate').text(response.date);
                        var tpl = '';

                        $.each(response.data, function(i,e) {
                            tpl += `<tr>
                                        <td>${e.name}</td>
                                        <td>${e.rate}</td>
                                        <td>${e.toPHP}</td>
                                    </tr>`;
                        });

                        cont.find('#currency-data').html(tpl);

                        cont.removeClass('hide');
                    } else {
                        cont.hide();
                    }
                },
                complete: function() {
                    cont.LoadingOverlay("hide");
                }
            });
        }
    }

    /**
    * move sidebar on the middle of feeds or service if sm or xs viewport
    */
    this.moveSidebar = function()
    {   
        if ($('#LoadMainBody').is(':visible') && $('#LoadMainBody .post-items').length > 5) {
            $('.side-bar-content-clone').remove();
            var cloneCont = $('<div class="side-bar-content-clone"></div>');
            if (Utils.isBreakpoint('sm') || Utils.isBreakpoint('xs')) {
                if (!$('.side-bar-content-clone').length) {
                    var clone = $('#side-bar-content').clone();
                    $('#side-bar-content').addClass('hide');

                    clone.removeClass('hide');
                    clone.prop('id', 'side-bar-content-fake');
                    cloneCont.html(clone);
                    $('#LoadMainBody').children(':eq(2)').after(cloneCont);
                }
            } else {
                $('#side-bar-content').removeClass('hide');
            }
        } else {
            $('#side-bar-content').removeClass('hide');
        }
    }

    /**
    * filter feed results
    */
    this.searchFeeds = function()
    {
        // clear previous feed refresh
        clearTimeout(self.feedTimeout);

        var params = {
            'latest'     : 0,
            'keyword'    : $('#searchForm #keyword').val(),
            'department' : $('#searchForm #DepartmentID').val(),
            'locScope'   : $('#searchForm #LocationScopeID').val()
        };

        Mgovph.getFeeds(params, true, $global.livefeed_interval);
    }, 

    /**
    * get and display feeds
    */
    this.getFeeds = function(params, firstload = false, interval = false)
    {

        if (firstload) {
            $('#LoadMainBody').html(''); //reset content
            $('#LoadMainBody').css('min-height', '70px').LoadingOverlay("show", {
                backgroundClass: 'bg-grey', 
                text: 'Loading feeds...', 
                textClass: 'text-gray',
                textResizeFactor: 1,
                imageResizeFactor: 2,
                size: 20,
                fade: [400, 50],
                zIndex: 99999
            });
        }

        $.ajax({
            url  : window.base_url('get/feeds'),
            type : 'post',
            data : params,
            success : function(response) {

                if (firstload) {
                    $('#LoadMainBody').LoadingOverlay("hide");
                }

                if (response.status) {

                    $('#lastFeed').val(response.timestamp);
                    params.latest = response.timestamp;

                    setTimeout(function(){

                        $.each(response.data, function(i, e){

                            var feedTemplate = $('.templates-container .feedItem').clone();
                            feedTemplate.prop('id', 'feed-' + e.ApplicationCode);
                            // feedTemplate.find('img.userAvatar').prop('src', e.userAvatar);
                            feedTemplate.find('img.userAvatar').prop('src', e.serviceQR);
                            // feedTemplate.find('span.feedID').text(e.ApplicationCode);
                            feedTemplate.find('span.userFullname').text(e.userFullname);
                            feedTemplate.find('span.userAddress').text(e.userAddress);
                            feedTemplate.find('span.feedServiceName').text(e.ServiceName);
                            feedTemplate.find('span.feedDepartmentName').text(e.departmentName);
                            feedTemplate.find('span.feedDescription').text(e.Description);
                            feedTemplate.find('span.feedServiceCode').text(e.ApplicationCode);
                            feedTemplate.find('span.feedServiceProvided').text(e.serviceProvided);
                            feedTemplate.find('img.serviceLogo').prop('src', e.Logo);
                            feedTemplate.find('.feedServiceDate').text(e.serviceDate);

                            feedTemplate.find('div.transaction-provider-pic').html('');
                            $.each(Utils.shuffle(e.serviceProvider).slice(0, 3), function(j,k){
                                feedTemplate.find('div.transaction-provider-pic').append('<img title="'+k.Name+'" src="'+k.Photo+'">');
                            });

                            if (firstload){
                                $('#LoadMainBody').append(feedTemplate);
                                feedTemplate.hide().removeClass('hide').fadeIn('slow');
                            } else {
                                feedTemplate.addClass('bg-cyan');
                                $('#LoadMainBody').prepend(feedTemplate);
                                feedTemplate.removeClass('hide').hide().fadeIn('slow');
                            }

                            $('#LoadMainBody .feedItem:gt('+ $global.livefeed_limit +')').fadeOut('slow', function() {
                                $(this).remove();
                            });

                        });

                    }, 10);

                    setTimeout(function(){
                        $('#LoadMainBody .feedItem').removeClass('bg-cyan');
                    }, 7000);

                } else {
                    if (firstload){
                        // clear content, add empty message
                        $('#LoadMainBody').html('<div class="feedItem post-items bg-grey padding-20"> \
                                                   <div class="row"> \
                                                      <div class="col-xs-12 padding-20"> \
                                                        <h2 class="text-bold text-white">No record found</h2> \
                                                      </div> \
                                                    </div> \
                                                </div> \
                                                      ');
                    }
                }

            },
            complete : function() {
                if (firstload) {
                    setTimeout(function(){
                        $('#LoadMainBody').LoadingOverlay("hide");

                        self.moveSidebar();
                    }, 200);
                }

                if (interval != false) {
                    self.feedTimeout = setTimeout(function() {
                        self.getFeeds(params, false, interval)
                    }, interval);
                }
            }
        });

    }


    /**
    * find and get services
    */
    this.getServices = function(selectedService = false)
    {
        var params = {
            'keyword'    : $('#searchForm #keyword').val(),
            'department' : $('#searchForm #DepartmentID').val(),
            'locScope'   : $('#searchForm #LocationScopeID').val()
        };

        $('#LoadMainBody').html(''); //reset content
        $('#LoadMainBody').css('min-height', '70px').LoadingOverlay("show", {
            backgroundClass: 'bg-grey', 
            text: 'Loading services...', 
            textClass: 'text-gray',
            textResizeFactor: 1,
            imageResizeFactor: 2,
            size: 20,
            fade: [400, 50],
            zIndex: 9999
        });

        $.ajax({
            url  : window.base_url('get/services'),
            type : 'post',
            data : params,
            success : function(response) {

                $('#LoadMainBody').LoadingOverlay("hide");

                if (response.status) {

                    if (selectedService) {
                        $('#LoadMainBody').hide();
                    }

                    setTimeout(function(){

                        $.each(response.data, function(i, e){

                            var serviceTemplate = $('.templates-container .serviceItem').clone();
                            serviceTemplate.find('img.DepartmentLogo').prop('src', e.Logo);
                            serviceTemplate.find('h2.DepartmentName').text(e.Name);

                            var servicesContainer   = serviceTemplate.find('.DepartmentServices');
                            var serviceItemCopy     = servicesContainer.find('.DepartmentService').clone();
                            servicesContainer.html(''); // reset
                            $.each(e.services, function(k,v) {
                                var serviceItem         = serviceItemCopy.clone();

                                serviceItem.data('id', v.id);
                                serviceItem.data('code', v.Code);
                                serviceItem.prop('id', 'service-item-' + v.Code);
                                serviceItem.find('span.ServiceName').text(v.Name + ' - ' + $global.location_scope[v.LocationScopeID]);
                                serviceItem.find('span.ServiceDesc').text(v.Description);
                                serviceItem.find('span.ServiceZone').text(v.AddressInfo.join(' > '));
                                serviceItem.find('span.ServiceCode').text(v.Code);
                                serviceItem.find('span.serviceProvided').text(e.serviceProvided);
                                serviceItem.find('img.serviceLogo').prop('src', v.Logo);
                                serviceItem.find('div.transaction-provider-pic').html('');
                                $.each(Utils.shuffle(v.serviceProvider).slice(0, 3), function(j,k){
                                    serviceItem.find('div.transaction-provider-pic').append('<img title="'+k.Name+'" src="'+k.Photo+'">');
                                });

                                servicesContainer.append(serviceItem);
                            });

                            $('#LoadMainBody').append(serviceTemplate);
                            serviceTemplate.hide().removeClass('hide').fadeIn('slow');

                        });

                    }, 50);

                    if (selectedService) {
                        setTimeout(function(){
                            $('#LoadMainBody').hide();
                            $('#service-item-' + selectedService).click();
                        }, 100);
                    }

                } else {
                    // clear content, add empty message
                    $('#LoadMainBody').html('<div class="feedItem post-items bg-grey padding-20"> \
                                               <div class="row"> \
                                                  <div class="col-xs-12 padding-20"> \
                                                    <h2 class="text-bold text-white">No record found</h2> \
                                                  </div> \
                                                </div> \
                                            </div> \
                                                  ');
                }

            },
            complete : function() {
                setTimeout(function(){
                    $('#LoadMainBody').LoadingOverlay("hide");
                    
                    self.moveSidebar();
                }, 200);
            }
        });
    }

    /**
    * open service details when clicked from service list
    */
    this.openServiceDetails = function(elem)
    {
        $('#LoadMainBody').LoadingOverlay("show", {zIndex: 999});
        $.ajax({
            url: window.base_url('services/view/' + $(elem).data('code')),
            type: 'GET',
            success: function (response) {
                if (response.status) {

                    var v = response.data;
                    var serviceTemplate = $('.templates-container .serviceDetailsTemplate').clone();
                    sessionStorage.serviceScroll = $(window).scrollTop();

                    serviceTemplate.data('id', v.id);
                    serviceTemplate.data('code', v.Code);
                    serviceTemplate.prop('id', 'service-item-' + v.Code);
                    serviceTemplate.find('span.ServiceName').text(v.Name);
                    serviceTemplate.find('span.ServiceDesc').text(v.Description);
                    serviceTemplate.find('span.ServiceZone').text(Object.values(v.Location).join(' > '));
                    serviceTemplate.find('span.ServiceCode').text(v.Code);
                    serviceTemplate.find('span.serviceProvided').text(v.serviceProvided);

                    serviceTemplate.find('div.transaction-provider-pic').html('');
                    $.each(Utils.shuffle(v.serviceProvider).slice(0, 3), function(j,k){
                        serviceTemplate.find('div.transaction-provider-pic').append('<img title="'+k.Name+'" src="'+k.Photo+'">');
                    });

                    // if (v.SubDepartment) {
                    //     serviceTemplate.find('img.DepartmentLogo').prop('src', window.public_url('assets/logo/' + v.SubDepartment.Logo));
                    // } else {
                        serviceTemplate.find('img.DepartmentLogo').prop('src', window.public_url('assets/logo/' + v.Department.Logo));
                    // }
                    serviceTemplate.find('img.ServiceLogo').prop('src', window.public_url('assets/logo/' + v.Logo));

                    if (v.hasPending) {
                        serviceTemplate.find('div.service-apply-button').html('<button style="cursor: default" class="btn btn-sm btn-info offset-bottom-10 offset-top-10">Your application is on process</button>');
                    }

                    $.each(v.Tags, function(i, e) {
                        serviceTemplate.find('.ServiceTags').append(' <label class="label label-'+Utils.getRandomItem(['info','success','danger','warning','primary'])+'">' + e + '</label> ');
                    });

                    var reqTpl = '';
                    $.each(v.Requirements, function(i, e) {
                        reqTpl += '<li class="media"> \
                                     <div class="media-left media-middle"> \
                                     ' + (typeof(e.status) !== 'undefined' ?
                                            '<a href="'+window.public_url('get/application_doc/' + e.app_req)+'" target="_blank">' :
                                            '<a href="#">' )
                                        + '<img style="max-height:40px;" class="media-object" src="' + window.public_url('assets/logo/' + e.Logo) + '"> \
                                       </a> \
                                     </div> \
                                     <div class="media-body media-middle"> \
                                       <h4 class="media-heading text-bold text-cyan" style="margin-bottom: 0;">'+ e.Name +'</h4> \
                                       ' + e.Description + ' \
                                     </div>';

                            if (typeof(e.status) !== 'undefined') {
                                reqTpl += '<div class="media-right media-middle" style="min-width: 150px;"> \
                                                <div class="row gutter-0"> \
                                                    <div class="col-xs-6"><label class="label label-warning">'+ e.status +'</label></div> \
                                                    <div class="col-xs-6 small">'+ e.last_updated +'</div> \
                                                </div> \
                                            </div>';
                            }

                        reqTpl += '</li>';
                    });

                    if (reqTpl != '') {
                        serviceTemplate.find('ul.requirement-items').html(reqTpl);
                    } else {
                        serviceTemplate.find('.list-requirements').hide();
                    }

                    self.selectedService = v;

                    $('#LoadMainBody').hide();
                    $('#searchForm').hide();
                    $('#ServiceDetailsBody').html(serviceTemplate).find('.serviceDetailsTemplate').hide().removeClass('hide').fadeIn('slow');
                    $(window).scrollTop(0);

                    self.moveSidebar();

                    // auto trigger apply, teporary!!!
                    // self.openServiceApplication(serviceTemplate.find('button'));
                }
            },
            complete: function() {
                $('#LoadMainBody').LoadingOverlay("hide");
            }
        });
    }

    /**
    * close service details
    */
    this.closeServiceDetails = function()
    {
        $('#LoadMainBody').show();
        $('#searchForm').show();
        $('#ServiceDetailsBody').html('');
        $(window).scrollTop(sessionStorage.serviceScroll);
        self.selectedService = false;

        self.moveSidebar();
    }

    /**
    * open service application modal
    */
    this.openServiceApplication = function(elem)
    {
        if (self.selectedService) {

            // reset form data
            $('#ServiceApplicationForm').trigger("reset");

            var v = self.selectedService;
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
            $.each($('#ServiceApplicationForm').find('input'), function(i,e){
                $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger').addClass('text-white');
                $(e).popover('destroy');
            });

            var additionalFields = '';
            $.each(v.ExtraFields, function(i, e) {
                additionalFields += '<div class="col-xs-6 col-sm-4"><div class="form-group"><label class="control-label">'+e.FieldLabel+'</label>';
                if (e.FieldType == 1) {
                    additionalFields += '<input type="text" id="'+e.FieldID+'" name="ExtraField['+e.FieldID+']" class="form-control input-sm" placeholder="'+e.FieldLabel+'" value="'+e.DefaultValue+'">';
                } else if (e.FieldType == 2) {
                    additionalFields += '<textarea rows="1" class="form-control input-sm" id="'+e.FieldID+'" name="ExtraField['+e.FieldID+']" placeholder="'+e.FieldLabel+'">'+e.DefaultValue+'</textarea>';
                } else if (e.FieldType == 3) {
                    additionalFields += '<input type="file" id="'+e.FieldID+'" name="Image['+e.FieldID+']" class="form-control input-sm" placeholder="'+e.FieldLabel+'">';
                } else if (e.FieldType == 4) {
                    var options = '';
                    if (e.DefaultValue) {
                        $.each(e.DefaultValue.split('|'), function(k,j){
                            options += `<option>${j.trim()}</option>`;
                        });
                    } else {
                        options += `<option>--</option>`;
                    }
                    additionalFields += `<select class="form-control input-sm" name="ExtraField[${e.FieldID}]">${options}</select>`;
                }
                additionalFields += '</div></div>';
            });
            if (additionalFields != '') {
                $('#serviceApplicationModal #serviceAdditionalFieldsCont').html('<div class="post-items bg-white padding-10"> \
                                                                                    <h2 class="text-cyan text-bold offset-bottom-10">Other Information Needed to Complete Your Request</h2> \
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
                                                <h2 class="text-cyan text-bold offset-bottom-10">'+k.Name +' Required Information</h2> \
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
            $('#ServiceApplicationForm').find('#error_message_box .error_messages').html('');
            $('#ServiceApplicationForm').find('#error_message_box').addClass('hide');

            $('#ServiceApplicationForm #ServiceCode').val(v.Code);

            $('#serviceApplicationModal').modal({
                backdrop : 'static',
                keyboard : false
            });
        }
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
                    var msgtpl = `<div class="row">
                                    <div class="col-xs-2">
                                        <img style="max-width: 80px;margin: 10px auto;" src="${msgimg}">
                                    </div>
                                    <div class="col-xs-10">
                                        Your Application Form and Attachments has been Submitted and will be processed. <br>
                                        Kindly Settle Payments IF only required by the verified Government Office as you claim your requested service or document. Thank you for using Mobile Government Integrated System of the Philippines.
                                    </div>
                                </div>`;
                    bootbox.alert({ 
                        size: null,
                        message: msgtpl, 
                        callback: function() {
                            self.openServiceDetails('<div data-code="'+$(form).find('#ServiceCode').val()+'"></div>');
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
    * GOVT PERFORMANCE PAGE
    * set province selector on gov performance report
    */
    this.set_gov_report_province_selector = function(obj, callback = false)
    {   
        $('#gov_report_province_selector').LoadingOverlay('show');
        var target = '#gov_report_province_selector';
        $.get(window.public_url('get/provinces'), {'regCode' : $(obj).val()}).done(function(response) {
            if (response.status) {
                var options = '<option value="">Select Province</option>';
                $.each(response.data, function(i, e){
                    options += '<option value="' + e.provCode + '">' + e.provDesc + '</option> \n';
                });
                $(target).html(options).prop('disabled', false);
                if (callback != false) {
                    callback();
                }
            } else {
                $(target).html('<option value="">Select Province</option>');
            }

            $('#gov_report_province_selector').LoadingOverlay('hide');
        });
    }

    /**
    * GOVT PERFORMANCE PAGE
    * display province performance report
    */
    this.get_gov_province_performance_report = function(obj)
    {
        $('#gov_performance_report_cont').LoadingOverlay('show');
        var target = '#gov_report_province_selector';
        $.get(window.public_url('statistics/get_province_transactions'), {'provinceCode' : $(obj).val()}).done(function(response) {
            if (response.status) {
                var pData = response.data;
                var rows = '<tr class="treegrid-' + pData.code + '">';
                        rows += '<td class="text-green text-bold">' + pData.name + '</td>';
                        rows += '<td>' + pData.services + '</td>';
                        rows += '<td>' + pData.counts.transactions + '</td>';
                        rows += '<td>' + pData.counts.processed + '</td>';
                        rows += '<td>' + pData.counts.approved + '</td>';
                        rows += '<td>' + pData.counts.denied + '</td>';
                        rows += '<td>' + pData.counts.pending + '</td>';
                    rows += '</tr>';

                $.each(pData.cities, function(i, cData){
                    rows += '<tr class="treegrid-' + cData.code + ' treegrid-parent-' + pData.code + '">';
                        rows += '<td class="text-cyan">' + cData.name + '</td>';
                        rows += '<td>' + cData.services + '</td>';
                        rows += '<td>' + cData.counts.transactions + '</td>';
                        rows += '<td>' + cData.counts.processed + '</td>';
                        rows += '<td>' + cData.counts.approved + '</td>';
                        rows += '<td>' + cData.counts.denied + '</td>';
                        rows += '<td>' + cData.counts.pending + '</td>';
                    rows += '</tr>';

                    $.each(cData.barangay, function(i, bData){
                        rows += '<tr class="treegrid-' + bData.code + ' treegrid-parent-' + cData.code + '">';
                            rows += '<td>' + bData.name + '</td>';
                            rows += '<td>' + bData.services + '</td>';
                            rows += '<td>' + bData.counts.transactions + '</td>';
                            rows += '<td>' + bData.counts.processed + '</td>';
                            rows += '<td>' + bData.counts.approved + '</td>';
                            rows += '<td>' + bData.counts.denied + '</td>';
                            rows += '<td>' + bData.counts.pending + '</td>';
                        rows += '</tr>';
                    });
                });

                $('#gov_performance_report_cont').find('table tbody').html(rows);
                $('#gov_performance_report_cont').find('table').treegrid({
                    initialState: 'expanded'
                });
            }
            $('#gov_performance_report_cont').LoadingOverlay('hide');
        });
    }
}


var Mgovph = new Mgovph();
$(document).ready(function(){
    Mgovph._init();
});