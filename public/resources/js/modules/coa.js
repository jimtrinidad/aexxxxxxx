function Coa() {

    // because this is overwritten on jquery events
    var self = this;

    this.projectData = {};
    this.supplierChanged;

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
        $('#projectForm').submit(function(e){
            e.preventDefault();
            self.saveForm(this);
        });

        $('#projectCategoryForm').submit(function(e){
            e.preventDefault();
            self.saveForm(this);
        });

        $('#categoryItemForm').submit(function(e){
            e.preventDefault();
            self.saveForm(this);
        });


        $('a.findSupplier').click(function(e){
            e.preventDefault();
            self.findSuppliers(this);
        });
    }

    /**
    * usage of different libraries and plugins
    */
    this.set_configs = function()
    {

    },


    this.getProject = function(id)
    {   
        var match = false;
        $.each(self.projectData, function(i,e){
            if (e.id == id) {
                match = e;
                return false;
            }
        });

        return match;
    }


    /**
    * add project
    */
    this.addProject = function()
    {   
        // reset form data
        $('#projectForm').trigger("reset");

        // reset input erros
        $.each($('#projectForm').find('input, textarea, select'), function(i,e){
            $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger');
            $(e).popover('destroy');
        });
        //clean error box
        $('#projectForm').find('#error_message_box .error_messages').html('');
        $('#projectForm').find('#error_message_box').addClass('hide');

        $('#projectForm #Code').val('');

        $('#projectModal .modal-title').html('<b>Project</b> | Add');
        $('#projectModal').modal({
            backdrop : 'static',
            keyboard : false
        });
    }


    this.editProject = function(id)
    {
        var data  = self.getProject(id);

        if (data) {
            // reset form data
            $('#projectForm').trigger("reset");

            // reset input erros
            $.each($('#projectForm').find('input, textarea, select'), function(i,e){
                $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger');
                $(e).popover('destroy');
            });
            //clean error box
            $('#projectForm').find('#error_message_box .error_messages').html('');
            $('#projectForm').find('#error_message_box').addClass('hide');

            $('#projectForm #Code').val(data.Code);
            $('#projectForm #Name').val(data.Name);
            $('#projectForm #Description').val(data.Description);
            $('#projectForm #LocationScopeID').val(data.Scope);

            $('#projectModal .modal-title').html('<b>Project</b> | Update');
            $('#projectModal').modal({
                backdrop : 'static',
                keyboard : false
            });
        }
    }

    this.deleteProject = function(id)
    {
        var data = self.getProject(id);
        if (data) {
            bootbox.confirm('Are you sure you want to <label class="label label-danger">delete</label> ' + data.Name, function(r){
                if (r) {
                    $.LoadingOverlay("show", {zIndex: 999});
                    $.ajax({
                        url: window.base_url('coa/deleteproject/' + data.Code),
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
        
        // reset input erros
        $.each($(form).find('input, select, textarea'), function(i,e){
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
                    // bootbox.alert(response.message, function(){
                        location.reload();
                    // });
                } else {
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


    this.addCategory = function(code)
    {
        // reset form data
        $('#projectCategoryForm').trigger("reset");

        // reset input erros
        $.each($('#projectCategoryForm').find('input, select, textarea'), function(i,e){
            $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger');
            $(e).popover('destroy');
        });
        //clean error box
        $('#projectCategoryForm').find('#error_message_box .error_messages').html('');
        $('#projectCategoryForm').find('#error_message_box').addClass('hide');

        $('#projectCategoryForm #Code').val(code);

        $('#projectCategoryModal .modal-title').html('<b>Project Category</b> | Add');
        $('#projectCategoryModal').modal({
            backdrop : 'static',
            keyboard : false
        });
    }

    this.disableCategory = function(code, psid)
    {
        bootbox.confirm('Are you sure you want to <label class="label label-danger">disable</label> this category?', function(r){
            if (r) {
                $.LoadingOverlay("show", {zIndex: 999});
                $.ajax({
                    url: window.base_url('coa/projectcategorystatus/' + code + '/'+ psid +'/0'),
                    type: 'GET',
                    success: function (response) {
                        if (response.status) {
                            // bootbox.alert(response.message, function(){
                                location.reload(); //easy way, just reload the page
                            // });
                        } else {
                            bootbox.alert(response.message);
                            $.LoadingOverlay("hide");
                        }
                    }
                });
            }
        });
    }

    this.activateCategory = function(code, psid)
    {
        bootbox.confirm('Are you sure you want to <label class="label label-success">enable</label> this category?', function(r){
            if (r) {
                $.LoadingOverlay("show", {zIndex: 999});
                $.ajax({
                    url: window.base_url('coa/projectcategorystatus/' + code + '/'+ psid +'/1'),
                    type: 'GET',
                    success: function (response) {
                        if (response.status) {
                            // bootbox.alert(response.message, function(){
                                location.reload(); //easy way, just reload the page
                            // });
                        } else {
                            bootbox.alert(response.message);
                            $.LoadingOverlay("hide");
                        }
                    }
                });
            }
        });
    }

    this.addCategoryItem = function(psid)
    {
        // reset form data
        $('#categoryItemForm').trigger("reset");

        // reset input erros
        $.each($('#categoryItemForm').find('input, select, textarea'), function(i,e){
            $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger');
            $(e).popover('destroy');
        });
        //clean error box
        $('#categoryItemForm').find('#error_message_box .error_messages').html('');
        $('#categoryItemForm').find('#error_message_box').addClass('hide');

        $('#categoryItemForm #ProjectServiceID').val(psid);
        $('#categoryItemForm #id').val('');

        $('#categoryItemModal .modal-title').html('<b>Category Item</b> | Add');
        $('#categoryItemModal').modal({
            backdrop : 'static',
            keyboard : false
        });
    }

    this.editCategoryItem = function(elem)
    {
        // reset form data
        $('#categoryItemForm').trigger("reset");

        // reset input erros
        $.each($('#categoryItemForm').find('input, select, textarea'), function(i,e){
            $(e).prop('title', '').closest('div').removeClass('has-error').find('label').removeClass('text-danger');
            $(e).popover('destroy');
        });
        //clean error box
        $('#categoryItemForm').find('#error_message_box .error_messages').html('');
        $('#categoryItemForm').find('#error_message_box').addClass('hide');

        var tr = $(elem).closest('tr');
        var psid = tr.data('psid');
        var id = tr.data('id');
        $('#categoryItemForm #Name').val(tr.find('td:eq(0)').text());
        $('#categoryItemForm #Description').val(tr.find('td:eq(1)').text());
        $('#categoryItemForm #Quantity').val(tr.find('td:eq(2)').text());
        $('#categoryItemForm #Allocation').val(tr.find('td:eq(3)').data('allocation'));

        $('#categoryItemForm #ProjectServiceID').val(psid);
        $('#categoryItemForm #id').val(id);

        $('#categoryItemModal .modal-title').html('<b>Category Item</b> | Add');
        $('#categoryItemModal').modal({
            backdrop : 'static',
            keyboard : false
        });
    }

    this.deleteCategoryItem = function(elem)
    {
        var tr = $(elem).closest('tr');
        var name = tr.find('td:eq(0)').text();
        var id = tr.data('id');
        bootbox.confirm('Are you sure you want to <label class="label label-danger">delete</label> item ' + name + '?', function(r){
            if (r) {
                $.LoadingOverlay("show", {zIndex: 999});
                $.ajax({
                    url: window.base_url('coa/deletecategoryitem/' + id),
                    type: 'GET',
                    success: function (response) {
                        if (response.status) {
                            // bootbox.alert(response.message, function(){
                                location.reload(); //easy way, just reload the page
                            // });
                        } else {
                            bootbox.alert(response.message);
                            $.LoadingOverlay("hide");
                        }
                    }
                });
            }
        });
    }





    /**
    * Procurement
    */
    this.findSuppliers = function(elem) {

        var data = $(elem).data();

        self.supplierChanged = false;

        $('#supplierItemFinderModal').find('.matchRows').html('');
        $('#supplierItemFinderModal').find('.nonMatchRows').html('');
        $('#supplierItemFinderModal').find('.matchedItems').css({height: '150px'});
        $('#supplierItemFinderModal').find('.matchedItems').LoadingOverlay('show');

        $.ajax({
            url: window.base_url('coa/findsupplieritems/'),
            type: 'POST',
            data,
            success: function (response) {
                $('#supplierItemFinderModal').find('.matchedItems').css({height: 'auto'});
                var tpl = '';
                var nonmatchtpl = '';
                if (response.status) {
                    if (response.data.hits.length) {
                        $.each(response.data.hits, function(i,e){
                            tpl += `<tr>
                                        <td><img src="${e.imageurl}" style="max-width: 40px;max-height: 40px;"></td>
                                        <td class="text-green">${e.Name}</td>
                                        <td>${e.Measurement}</td>
                                        <td class="text-red">P${e.Price}</td>
                                        <td>${e.supplierInfo['Company Name']}</td>
                                        <td class="text-right"><a href="javascript:;" class="btn btn-xs btn-info" onClick="Coa.setSupplier(${data.item},${data.rank},${e.id},${e.BusinessID})">Select</a></td>
                                    </tr>`;
                        });
                    } else {
                        tpl += `<tr><td class="text-center text-bold padding-10 text-cyan" colspan="6">No item found.</td></tr>`;
                    }

                    if (response.data.others.length) {
                        $.each(response.data.others, function(i,e){
                            nonmatchtpl += `<tr>
                                        <td><img src="${e.imageurl}" style="max-width: 40px;max-height: 40px;"></td>
                                        <td class="text-green">${e.Name}</td>
                                        <td>${e.Measurement}</td>
                                        <td class="text-red">P${e.Price}</td>
                                        <td>${e.supplierInfo['Company Name']}</td>
                                        <td class="text-right"><a href="javascript:;" class="btn btn-xs btn-info" onClick="Coa.setSupplier(${data.item},${data.rank},${e.id},${e.BusinessID})">Select</a></td>
                                    </tr>`;
                        });
                    } else {
                        nonmatchtpl += `<tr><td class="text-center text-bold padding-10 text-cyan" colspan="6">No item found.</td></tr>`;
                    }

                }
                $('#supplierItemFinderModal').find('.matchRows').html(tpl);
                $('#supplierItemFinderModal').find('.nonMatchRows').html(nonmatchtpl);
            },
            complete: function(r) {
                $('#supplierItemFinderModal').find('.matchedItems').LoadingOverlay("hide");
            }
        });

        $('#supplierItemFinderModal').modal({
            backdrop : 'static',
            keyboard : false
        });

        $('#supplierItemFinderModal').on('hide.bs.modal', function (e) {
            // refresh list
            if (self.supplierChanged) {
                location.reload();
            }
        });

    }

    this.setSupplier = function(item, rank, selectedid, supplier) {
        console.log(item, rank, selectedid, supplier);
        $.LoadingOverlay('show');
        $.ajax({
            url: window.base_url('coa/setitemsupplier/'),
            type: 'POST',
            data: {
                item: item,
                rank: rank,
                selecteditem: selectedid,
                supplierid: supplier,
                project: $('#selectedProject').val()
            },
            success: function (response) {
                if (response.status) {
                    location.reload();
                } else {
                    bootbox.alert(response.message);
                }
            },
            complete: function(r) {
                $.LoadingOverlay("hide");
            }
        });
    }

    this.removeAssignedSupplier = function(id)
    {
        bootbox.confirm('Are you sure you want to remove this supplier?', function(r) {
            if (r) {
                $.ajax({
                    url: window.base_url('coa/removeitemsupplier/'),
                    type: 'POST',
                    data: {
                        id: id,
                    },
                    success: function (response) {
                        location.reload();
                    }
                });
            }
        });
    }

}


var Coa = new Coa();
$(document).ready(function(){
    Coa._init();
});