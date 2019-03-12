function Marketplace() {

    // because this is overwritten on jquery events
    var self = this;

    this.itemData = {};

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

    }

    /**
    * usage of different libraries and plugins
    */
    this.set_configs = function()
    {

    },


    this.getData = function(id)
    {   
        var match = false;
        $.each(self.itemData, function(i,e){
            if (e.id == id) {
                match = e;
                return false;
            }
        });

        return match;
    }

    this.viewItem = function(elem, event)
    {   

        var data = self.getData($(elem).data('id'));
        if (data && !$(event.target).is('i') && !$(event.target).is('a')) {

            $('#viewItemModal').find('span.name').text(data.Name);
            $('#viewItemModal').find('span.description').text(data.Description);
            $('#viewItemModal').find('span.price').text('₱' + data.Price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#viewItemModal').find('span.uom').text('/ ' + data.Measurement);

            $('#viewItemModal').find('span.sellerName').text(data.seller['Company Name']);
            $('#viewItemModal').find('span.accreditation').text(data.seller['Accredication No.']);
            if (data.seller.sellerData.contact) {
                $('#viewItemModal').find('span.contact').text(data.seller.sellerData.contact)
                $('#viewItemModal').find('a.call-bot').prop('href', 'tel:' + data.seller.sellerData.contact).show();
            } else {
                $('#viewItemModal').find('a.call-bot').hide();
            }
            $('#viewItemModal').find('a.chat-bot').off('click');
            $('#viewItemModal').find('a.chat-bot').off('click').on('click', function(){
                $('#viewItemModal').modal('hide');
                Chatbox.openChatbox(data.seller.sellerData.mabuhayID);
            });

            if (data.Warranty) {
                $('#viewItemModal').find('span.warranty').text(data.Warranty);
                $('#viewItemModal').find('span.warranty').parent().show();
            } else {
                $('#viewItemModal').find('span.warranty').parent().hide();
            }
            if (data.PaymentTerm) {
                $('#viewItemModal').find('span.payment-term').text(data.PaymentTerm);
                $('#viewItemModal').find('span.payment-term').parent().show();
            } else {
                $('#viewItemModal').find('span.payment-term').parent().hide();
            }
            if (data.LeadTime) {
                $('#viewItemModal').find('span.lead-time').text(data.LeadTime);
                $('#viewItemModal').find('span.lead-time').parent().show();
            } else {
                $('#viewItemModal').find('span.lead-time').parent().hide();
            }

            $('#viewItemModal .modal-title').html('<b>Product</b> | Details');
            $('#viewItemModal').modal();
        }
    }

}


var Marketplace = new Marketplace();
$(document).ready(function(){
    Marketplace._init();
});