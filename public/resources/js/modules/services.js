function Services() {

    // because this is overwritten on jquery events
    var self = this;

    this.servicesData = {};

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

    }

    /**
    * usage of different libraries and plugins
    */
    this.set_configs = function()
    {
        
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

}


var Services = new Services();
$(document).ready(function(){
    Services._init();
});