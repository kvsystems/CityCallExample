$(document).ready(function() { calculateSlaveCost() });
$( '#field_start, #field_expire, input[name=customer]' ).on('change',function () { calculateSlaveCost() });

function calculateSlaveCost()   {

    var startRent  = $('#field_start').val();
    var expireRent = $('#field_expire').val();
    var slave      = $('input[name=slave_data_id]').val();
    var customer      = $('input[name=customer]:checked').val();

    $.post( "/api/rent/check/", {
        start_rent: startRent,
        expire_rent: expireRent,
        slave_id: slave,
        customer_id: customer  },
    function(data) {
        $('#confirm_rent').show();
        $('#calculated').show();
        $('#response').text(data.message);
        $('input[name=slave_cost]').val(data.cost);
        $('#response').css('color', '#0313ff');
    }).fail(function( data ) {
        $('#confirm_rent').hide();
        $('#calculated').hide();
        $('#response').text(data.responseJSON.message);
        $('#response').css('color', '#F00');
    });

}