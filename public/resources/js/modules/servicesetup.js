function ServiceSetup() {

    // because this is overwritten on jquery events
    var self = this;

    this.form = '#ServiceSetupForm';
    this.sortable = [];
    this.currentRequirements = [];
    this.currentRequirementFunctions = {};
    this.officerFinderSelected = [];
    this.officerAssigned = {};

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
        $(self.form).submit(function(e) {
            e.preventDefault();
            var form = this;
            if ($(self.form).find('#edit-mode').length) {
                bootbox.confirm('Are you sure you want to apply changes made?', function(r){
                    if (r) {
                        self.saveService(form);
                    }
                });
            } else {
                self.saveService(form);
            }
        });
    }

    /**
    * usage of different libraries and plugins
    */
    this.set_configs = function()
    {

        /**
        * initialize form wizard layout
        */
        var form = $(self.form);
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
                    case 1: 
                        self.setSortable('createdFields');
                        break;
                    case 3: 
                        self.setSortable('createdFunctions');
                        $.each($('#createdFunctions .requirementFunctionSubTable'), function(i,e) {
                            self.setSortable($(e).find('tbody').prop('id'));
                        });
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

        // service information tags
        $('#Tags').select2({
            width: '100%'
        });

        $('#citySelector').select2({
            width: '100%'
        });

    }

    /**
    * get city barangay
    */
    this.loadBarangayOptions = function(target, e, selected = false, callback = false)
    {
        $(self.form).find('#LocationCode').html('').prop('disabled', true);
        if ($(self.form).find('#LocationCode').hasClass("select2-hidden-accessible")) {
            $(self.form).find('#LocationCode').select2('destroy');
        }

        $(target).html(window.emptySelectOption).prop('disabled', true);
        if ($(target).hasClass("select2-hidden-accessible")) {
            $(target).select2('destroy');
        }
        $.get(window.public_url('get/barangay'), {'citymunCode' : $(e).val()}).done(function(response) {
            if (response.status) {
                var options = window.emptySelectOption;
                $.each(response.data, function(i, e){
                    options += '<option value="' + e.brgyCode + '" '+ (selected == e.brgyCode ? 'selected="selected"' : '') +' >' + e.brgyDesc + '</option> \n';
                });
                $(target).html(options).prop('disabled', false).select2({
                    width: '100%'
                });

                if (callback) {
                    callback();
                }

            } else {
                $(target).html(window.emptySelectOption);
            }
        });
    }


    /**
    * set location dropdown by scope
    */
    this.setLocationSelector = function(e, defaultLocation = false, defaultCity = false, callback = false)
    {   

        // Regional to barangay
        var scope = $(e).val();
        $(self.form).find('#LocationCode').html('').prop('disabled', true);
        if ($(self.form).find('#LocationCode').hasClass("select2-hidden-accessible")) {
            $(self.form).find('#LocationCode').select2('destroy');
        }
        if ($(self.form).find('#citySelector').hasClass("select2-hidden-accessible")) {
            $(self.form).find('#citySelector').select2('destroy');
        }
        $('#citySelectorCont').addClass('hide');
        if (scope > 1) {
            if (scope == 6) {
                $('#citySelectorCont').removeClass('hide');
                $('#citySelector').val(defaultCity).select2({
                    width: '100%'
                });
            } else {
                $(self.form).LoadingOverlay("show");
                $(self.form).find('#LocationCode').html(window.emptySelectOption);
                $.get(window.base_url('department/get_scope_locations'), {'scope' : scope}).done(function(data) {
                    var options = window.emptySelectOption;
                    $.each(data, function(i, e){
                        var key   = false;
                        var value = false;
                        switch(scope) {
                            case '2': 
                                key   = e.regCode;
                                value = e.regDesc;
                                break;
                            case '3': 
                                key   = e.provCode;
                                value = e.provDesc;
                                break;
                            case '4': 
                            case '5': 
                                key   = e.citymunCode;
                                value = e.provDesc + ' | ' + e.citymunDesc;
                                break;
                            case '6': 
                                key   = e.brgyCode;
                                value = e.brgyDesc;
                                break;
                        }

                        if (key != false) {
                            options += '<option value="' + key + '" '+ (defaultLocation == key ? 'selected="selected"' : '') +'>' + value + '</option> \n';
                        }
                    });
                    $(self.form).find('#LocationCode').html(options).prop('disabled', false).select2({
                        width: '100%'
                    });

                    if (callback) {
                        callback();
                    }
                    $(self.form).LoadingOverlay("hide");
                });
            }
        } else if (scope == 1) {
            if (callback) {
                callback();
            }
        }
    }


    /**
    * set available department base on scope and location
    */
    this.setDepartmentSelector = function(selectedDept = false)
    {
        var locationCode = $(self.form).find('#LocationCode').val();
        var scope        = $(self.form).find('#LocationScope').val();

        $(self.form).find('#DepartmentScope').html('').prop('disabled', true);
        if ($(self.form).find('#DepartmentScope').hasClass("select2-hidden-accessible")) {
            $(self.form).find('#DepartmentScope').select2('destroy');
        }

        if ((scope && locationCode) || scope == 1) {
            $(self.form).LoadingOverlay("show");
            $(self.form).find('#DepartmentScope').html(window.emptySelectOption);
            $.get(window.base_url('department/get_departments_by_scope_location'), {'scope' : scope, 'location' : locationCode}).done(function(data) {
                var options = window.emptySelectOption;
                $.each(data, function(i, e){
                    var key = e.id;
                    var value = e.parent.Name;
                    var data = 'data-logo="' + e.parent.Logo + '"';
                    options += '<option value="' + key + '" '+data+' '+ (selectedDept == key ? 'selected="selected"' : '') +'>' + value + '</option> \n';
                });
                $(self.form).find('#DepartmentScope').html(options).prop('disabled', false).select2({
                    width: '100%',
                    templateResult: function (selection) {
                        if (!selection.id) { return selection.text; }
                        var logo = $(selection.element).data('logo');
                        if(!logo){
                            return selection.text;
                        } else {
                            return $('<img style="width: 25px;height: 25px" src="' + window.public_url() + 'assets/logo/' + logo + '" alt=""> \
                                                    <span class="img-changer-text"> ' + $(selection.element).text() + '</span>');
                        }
                    }
                });
                $(self.form).LoadingOverlay("hide");
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
    * service extra form fields add row
    */
    this.addFieldRow = function()
    {   
        var form    = $(self.form).find('#addFormFields');
        var group   = form.find('.fieldGroup').val();
        var type    = form.find('.fieldType').val();
        var label   = form.find('.fieldLabel').val();

        if (group != '' && type != '' && label.trim() != '') {
            var clone  = form.children().clone();
            var itemID = Utils.generateString();
            var count  = $(self.form).find('#createdFields tr').length;
            clone.removeClass('info').addClass('sortable-row');
            clone.prop('id', itemID);
            clone.find('td:first-child').prepend('<i class="drag-handle fa fa-arrows"></i>');
            clone.find('.fieldGroup').val(group).prop('name', 'Field['+itemID+'][Group]').addClass('fGroup');
            clone.find('.fieldType').val(type).prop('name', 'Field['+itemID+'][Type]').addClass('fType');
            clone.find('.fieldLabel').val(label).prop('name', 'Field['+itemID+'][Label]').addClass('fLabel').closest('td').append('<input type="hidden" class="item-order" name="Field['+itemID+'][Ordering]" value="'+(count + 1)+'">');
            clone.find('td:last-child').html('<button type="button" class="btn btn-danger btn-sm" onClick="ServiceSetup.removeFieldRow(this)"><i class="fa fa-trash"></i></button>');
            $(self.form).find('#createdFields').append(clone);

            // reset
            form.find('.fieldGroup').val(1);
            form.find('.fieldType').val(1);
            form.find('.fieldLabel').val('');

            self.setSortable('createdFields');
        }
    }

    /**
    * service extra form fields, remove row
    */
    this.removeFieldRow = function(elem)
    {
        $(elem).closest("tr").fadeOut('slow', function(){
            $(this).remove();
            self.setSortable('createdFields');
        });
    }


    /**
    * add service requirement
    */
    this.addRequirementRow = function(params = false)
    {
        var form    = $(self.form).find('#addRequirementsRow');

        if (params != false) {
            var docid   = params.docid;
            var desc    = params.desc;
            var docName = params.docname;
            var itemID  = params.id;
        } else {
            var docid   = form.find('.requirementDoc').val();
            var desc    = form.find('.requirementDesc').val();
            var docName = form.find('.requirementDoc option:selected').text();
            var itemID  = Utils.generateString();
        }

        if (docid != '' && !~$.inArray(docid, self.currentRequirements)) {
            self.currentRequirements.push(docid);
            var clone   = form.children().clone();

            clone.removeClass('info');
            clone.prop('id', itemID)
            clone.data('docid', docid);
            clone.find('.requirementDoc').closest('td').css('padding-top','13px').html('<input type="hidden" name="Requirement['+itemID+'][DocID]" value="'+docid+'"><b>' + docName + '</b>');
            clone.find('.requirementDesc').val(desc).prop('name', 'Requirement['+itemID+'][Desc]');
            clone.find('td:last-child').html('<button type="button" class="btn btn-danger btn-sm" onClick="ServiceSetup.removeRequirementRow(this)"><i class="fa fa-trash"></i></button>');
            $(self.form).find('#createdRequirements').append(clone);

            autosize($('#createdRequirements textarea'));

            // add function for option
            $('select.functionFor optgroup').append('<option value="'+itemID+'">' + docName + '</option>');

            // reset
            form.find('.requirementDoc').val('');
            form.find('.requirementDesc').val('');
            autosize.update(form.find('.requirementDesc'));

        }
    }

    /**
    * remove added service requirement row
    */
    this.removeRequirementRow = function(elem)
    {
        var docid = $(elem).closest("tr").data('docid');
        var recID = $(elem).closest("tr").prop('id');
        $(elem).closest("tr").fadeOut('fast', function(){
            position = $.inArray(docid, self.currentRequirements);
            if (~position) {
                self.currentRequirements.splice(position, 1);
                $(this).remove();

                // remove requirement from functionFor options
                $('select.functionFor optgroup option[value='+recID+']').remove();
                // remove function row if already added
                $(self.form).find('#createdFunctions tr#' + recID).remove();

            }
        });
    }

    /**
    * add function row
    */
    this.addFunctionRow = function(params = false)
    {
        var form    = $(self.form).find('#addFunctionRow');

        if (params != false) {
            var fncFor  = params.fncfor;
            var fncType = params.fnctype;
            var fncDesc = params.fncdesc;
            var forTxt  = params.fortxt;
            var typeTxt = params.typetxt;
            var itemID  = params.id;
        } else {
            var fncFor  = form.find('.functionFor').val();
            var fncType = form.find('.functionType').val();
            var fncDesc = form.find('.functionDesc').val();
            var forTxt  = form.find('.functionFor option:selected').text();
            var typeTxt = form.find('.functionType option:selected').text(); 
            var itemID  = Utils.generateString();
        }

        if (fncFor != '' && fncType != '' && !~$.inArray(fncType, self.currentRequirementFunctions[fncFor])) {
            if (typeof(self.currentRequirementFunctions[fncFor]) == 'undefined') {
                self.currentRequirementFunctions[fncFor] = [];
            }
            self.currentRequirementFunctions[fncFor].push(fncType);

            var clone  = form.children().clone();
            var count  = $(self.form).find('#createdFunctions').children().length;

            clone.removeClass('info').addClass('sortable-row');
            clone.prop('id', itemID)
            clone.data('fncFor', fncFor);
            clone.data('fncType', fncType);

            clone.find('.functionType').closest('td').css('padding-top','13px').html('<input type="hidden" name="Function['+itemID+'][Type]" value="'+fncType+'">' + typeTxt);
            clone.find('td.func-desc-td').prop('colspan', 1).after('<td class="officersContainer"> \
                                                                    <div class="row gutter-5"> \
                                                                        <div class="col-sm-8"> \
                                                                          <div class="input-group input-group-sm" style="width: 100%"> \
                                                                            <input type="text" autocomplete="off" id="'+itemID+'" class="form-control accountFinder" placeholder="find officer by id or name"> \
                                                                            <span class="input-group-btn"> \
                                                                              <button class="btn btn-default" type="button" onClick="ServiceSetup.addOfficer(\''+itemID+'\')"><span class="visible-lg-inline">Add </span><i class="fa fa-plus"></i></button> \
                                                                            </span> \
                                                                          </div> \
                                                                        </div> \
                                                                        <div class="col-sm-4 text-right"> \
                                                                            <button type="button" class="btn btn-sm btn-info" onClick="ServiceSetup.showOfficer(\''+itemID+'\')"><span class="visible-lg-inline"><i class="fa fa-users"></i> </span>(<b class="officerCount">0</b>) <i class="fa fa-search"></i></button>\
                                                                        </div> \
                                                                    </td>');
            clone.find('.functionDesc').val(fncDesc).prop('name', 'Function['+itemID+'][Desc]');
            clone.find('td:last-child').html('<button type="button" class="btn btn-danger btn-sm" onClick="ServiceSetup.removeFunctionRow(this)"><i class="fa fa-trash"></i></button>');

            // limit sortable for main service function, and function row of each requirements
            // sorting of requirements should be done on requirement tab
            if (fncFor == 'Main') {

                clone.find('td:first-child').prepend('<i class="drag-handle fa fa-arrows"></i>');
                clone.find('.functionFor').closest('td').css('padding-top','13px').html('<input type="hidden" name="Function['+itemID+'][For]" value="'+fncFor+'"> \
                                                                                        <input type="hidden" class="item-order" name="Function['+itemID+'][Ordering]" value="'+(count + 1)+'"> \
                                                                                        <b>' + forTxt + '</b>');
                $(self.form).find('#createdFunctions').append(clone);

            } else {

                var reqTableID  = 'req-'+fncFor+'-FunctionTable';
                var reqTableTpl = '<table id="'+reqTableID+'" class="requirementFunctionSubTable table table-responsive"> \
                                        <thead> \
                                            <th style="width: 150px;"></th> \
                                            <th style="width: 10px"></th> \
                                            <th style="width: 192px"></th> \
                                            <th style="width: 250px"></th> \
                                            <th style="min-width: 150px"></th> \
                                            <th style="width: 30px;"></th> \
                                        </thead> \
                                        <tbody id="req-'+fncFor+'-Functions"> \
                                        </tbody> \
                                    </table>';

                if (!$(self.form).find('#createdFunctions #' + reqTableID).length) {
                    $(self.form).find('#createdFunctions').append('<tr class="sortable-row" id="'+fncFor+'"> \
                                                                        <td colspan="6" style="padding-right: 0;"><div> \
                                                                            <input type="hidden" class="item-order" name="Requirement['+fncFor+'][Ordering]" value="'+(count + 1)+'"> \
                                                                            <i class="drag-handle fa fa-arrows"></i> &nbsp;&nbsp; \
                                                                            <b>' + forTxt + '<b></div>' + reqTableTpl + ' \
                                                                        </td></tr>');
                }

                var subcount  = $(self.form).find('#createdFunctions #req-'+fncFor+'-Functions').children().length;

                clone.find('.functionFor').closest('td').html('<i class="drag-handle fa fa-arrows"></i> \
                                                                <input type="hidden" name="Function['+itemID+'][For]" value="'+fncFor+'"> \
                                                                <input type="hidden" class="item-order" name="Function['+itemID+'][Ordering]" value="'+(subcount + 1)+'">'
                                                            );

                $(self.form).find('#req-'+fncFor+'-Functions').append(clone);
                self.setSortable('req-'+fncFor+'-Functions');

            }

            autosize($('#createdFunctions textarea'));

            self.officerFinder($('#createdFunctions').find('.accountFinder'));

            // reset
            form.find('.functionFor').val('Main');
            form.find('.functionType').val('');
            form.find('.functionDesc').val('');
            autosize.update(form.find('.functionDesc'));

            self.setSortable('createdFunctions');

        }

    }

    /**
    * remove function row
    */
    this.removeFunctionRow = function(elem)
    {
        var rowID   = $(elem).closest("tr").prop('id');
        var fncFor  = $(elem).closest("tr").data('fncFor');
        var fncType = $(elem).closest("tr").data('fncType');
        $(elem).closest("tr").fadeOut('fast', function(){
            position = $.inArray(fncType, self.currentRequirementFunctions[fncFor]);
            if (~position) {
                self.currentRequirementFunctions[fncFor].splice(position, 1);
                // remove parent row on empty
                if (fncFor != 'Main' && !self.currentRequirementFunctions[fncFor].length) {
                    $(this).closest('table').closest('tr').remove();
                } else {
                    $(this).remove();
                    if (fncFor != 'Main') {
                        self.setSortable('req-'+fncFor+'-Functions');
                    }
                }
                self.setSortable('createdFunctions');
            }
        });
    }

    /**
    * officer finder
    */
    this.officerFinder = function(elem)
    {   
        try { $(elem).typeahead('destroy'); } catch(e) {}
        $(elem).typeahead({
            hint: false,
            minLength: 5,
        },
        {
            templates: {
                empty: [
                    '<div class="padding-left-10 empty-message">',
                      'Record not found.',
                    '</div>'
                ].join('\n'),
                suggestion: function (item) {
                    // return "<p><img style='width:40px;height:40px;margin: 0 auto;' src='" + window.public_url() + "assets/profile/"+item.photo+"' />" + item.mabuhayID + " | " + item.fullname + "</p>";
                    item.address.pop();
                    return '<div class="row gutter-0">' +
                                '<div class="col-sm-12 col-lg-2">' +
                                    '<span>' +
                                    '<img style="width:50px;height:50px;margin:0 auto;" src="' + window.public_url() + "assets/profile/"+item.photo+'">' +
                                    '</span></div>' +
                                '<div class="col-sm-12 col-lg-10">' +
                                    '<div>'+ item.mabuhayID +' - '+ item.fullname + '</div>' +
                                    '<div><small>'+ item.aclevel +'</small></div>' +
                                    '<div><small>'+ item.address.join(', ') +'</small></div>' +
                                '</div>' +
                            '</div>';
                }
            },
            name: 'officers',
            display: 'fullname',
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
            self.officerFinderSelected[id] = item;
        }).bind('typeahead:change', function(ev, value) {
            var id = $(ev.target).prop('id');
            if (!(typeof(self.officerFinderSelected[id]) !== 'undefined' && self.officerFinderSelected[id].fullname == value)) {
                delete self.officerFinderSelected[id];
                $(ev.target).typeahead('val', '');
            }
        });
    }


    /**
    * update assigned officers backround data and count
    */
    this.updateAssignedOfficers = function(id)
    {
        // update assigned count
        $('#createdFunctions tr#'+id).find('.officerCount').text(self.officerAssigned[id].length);

        // add hidden element input
        var tpl = '';
        $.each(self.officerAssigned, function(functionID, items){
            items.forEach(function(data) {
              tpl += '<input type="hidden" name="Officer['+functionID+'][]" value="'+data.id+'">\n';
            });
        });
        $('#assignedOfficersHidden').html(tpl);
    }

    /**
    * assign selected officer
    */
    this.addOfficer = function(id)
    {
        if (typeof(self.officerFinderSelected[id]) !== 'undefined') {
            
            var exists = false;
            if (typeof(self.officerAssigned[id]) == 'undefined') {
                self.officerAssigned[id] = [];
            }

            $.each(self.officerAssigned[id], function(i,e){
                if (self.officerFinderSelected[id].id == e.id) {
                    exists = true
                    return false;
                }
            })

            if (!exists) {
                self.officerAssigned[id].push(self.officerFinderSelected[id]);
            }

            delete self.officerFinderSelected[id];
            $('#createdFunctions').find('.accountFinder#'+id).typeahead('val', '');

            // update assigned count
            self.updateAssignedOfficers(id);


        }

    }


    /**
    * show assigned officers
    */
    this.showOfficer = function(id)
    {
        if (typeof(self.officerAssigned[id]) != 'undefined' && self.officerAssigned[id].length) {

            var rows_tpl = '';

            $.each(self.officerAssigned[id], function(i,e){
                var template = '';
                    template += '<tr id="officer_row_'+e.id+'" data-function="'+id+'" data-id="'+e.id+'" data-name="'+e.fullname+'">';
                        template += '<td><img class="logo-small" src="' + window.public_url() + 'assets/profile/' + (e.photo != '' ? e.photo : 'avatar_default.jpg') + '"></td>';
                        template += '<td>' + e.mabuhayID + '</td>';
                        template += '<td>' + e.fullname + '</td>';
                        template += '<td class="hidden-xs hidden-sm">' + e.contact + '</td>';
                        template += '<td class="visible-lg">' + e.address[0] + ' ' + e.address[1] + ', ' + e.address[2] + '</td>';
                        template += '<td> \
                                        <div class="box-tools"> \
                                            <div class="input-group pull-right" style="width: 10px;"> \
                                              <div class="input-group-btn"> \
                                                <button type="button" class="btn btn-xs btn-default" onClick="ServiceSetup.removeOfficer(this)"><i class="fa fa-trash"></i></button> \
                                              </div> \
                                            </div> \
                                        </div>  \
                                    </td>';
                    template += '</tr>';

                rows_tpl += template;
            });

            var tpl = '<table id="assignedOfficersTable" class="table table-condensed tableData"> \
                          <thead> \
                            <tr> \
                              <td style="width: 20px;"></td> \
                              <th>ID</th> \
                              <th>Name</th> \
                              <th class="hidden-xs hidden-sm">Contact</th> \
                              <th class="visible-lg">Address</th> \
                              <th class="c"></th> \
                            </tr> \
                          </thead> \
                          <tbody id="tableBody"> \
                            '+rows_tpl+' \
                          </tbody> \
                        </table>';

            bootbox.alert({
                size: 'large',
                title: 'Assigned Officers',
                message: tpl
            });

        }
    }

    /**
    * remove assigned officer
    */
    this.removeOfficer = function(elem)
    {
        var row = $(elem).closest('tr');
        var id  = row.data('function');
        bootbox.confirm('Are you sure you want to remove ' + row.data('name') + '?', function(r){
            if (r) {
                $.each(self.officerAssigned[id], function(i,e){
                    if (e.id == row.data('id')) {
                        self.officerAssigned[id].splice(i, 1);
                        row.fadeOut('fast', function(){
                            row.remove();
                        });
                        return false;
                    }
                });
                // update assigned count
                self.updateAssignedOfficers(id);
            }
        });
    }




    /**
    * save service setup changes
    */
    this.saveService = function(form)
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
                    if (response.type == 'new') {
                        bootbox.confirm({
                            message: response.message + ' Do you want to add another one?',
                            buttons: {
                                confirm: {
                                    label: 'Yes',
                                    className: 'btn-success'
                                },
                                cancel: {
                                    label: 'Return to services list',
                                    className: 'btn-info'
                                }
                            },
                            callback: function(r){
                                if (r) {
                                    window.location = window.base_url('services/setup'); 
                                } else {
                                    window.location = window.base_url('services'); 
                                }
                            }
                        });
                    } else {
                        bootbox.alert(response.message, function(){
                            location.reload();
                        });
                    }
                } else {
                    // bootbox.alert(response.message);
                    $(form).find('#error_message_box .error_messages').append('<p><b>' + response.message + '</b></p>');

                    $.each(response.fields, function(i,e){
                        if (response.group == 2) {
                            $.each(e, function(j, k){
                                $(form).find('#'+i).find('.'+j).closest('td').prop('title', k).addClass('has-error').find('label').addClass('text-danger');
                                Utils.popover($(form).find('#'+i).find('.'+j), {
                                    t: 'hover',
                                    p: 'top',
                                    m: k
                                });
                            });
                        } else {
                            if (response.group == 3) {
                                $(form).find('#'+i).find('.accountFinder').prop('title', e).closest('td').addClass('has-error').find('label').addClass('text-danger');
                                Utils.popover($(form).find('#'+i).find('.accountFinder'), {
                                    t: 'hover',
                                    p: 'top',
                                    m: e
                                });
                            } else {
                                $(form).find('#'+i).prop('title', e).closest('div').addClass('has-error').find('label').addClass('text-danger');
                                $(form).find('#error_message_box .error_messages').append('<p>' + e + '</p>');
                                Utils.popover($('#'+i), {
                                    t: 'hover',
                                    p: 'top',
                                    m: e
                                });
                            }
                            
                        }
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


var ServiceSetup = new ServiceSetup();
$(document).ready(function(){
    ServiceSetup._init();
});