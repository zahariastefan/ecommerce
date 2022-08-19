
function addToCart(e, path) {
    var fullUrl = window.location.origin + path;
    var items_number = $('.items_number').text();
    var second_div_cart0 = $(e).parent().attr('class');
    var second_div_cart = (second_div_cart0.split(' '))[0];
    var oneItemDiv0 = $(e).parent().parent().attr('class');
    var oneItemDiv = (oneItemDiv0.split(' '))[0];
    var oneItemName = $('.' + oneItemDiv + '  p.oneItemTitle').text();
    var finalValue = $('.' + second_div_cart + '  input').val();
    $.ajax({
        type: "POST",
        url: fullUrl,
        data: {
            itemId: oneItemName,
            cartPage: 1,
        },
        complete: function (data) {
            $('.' + second_div_cart + '  input').val(parseInt(finalValue) + 1);
            $('.' + oneItemDiv + '  .value_to_fill').val('{"item":"' + oneItemName + '","quantity":' + (parseInt(finalValue) + 1) + '}');
            $('.items_number').text(parseInt(items_number) + 1);
            getTotal();
        }
    });
}

function removeToCart(e, path2) {
    var fullUrl = window.location.origin + path2;
    var items_number = $('.items_number').text();
    var second_div_cart = $(e).parent().attr('class');
    var oneItemDiv = $(e).parent().parent().attr('class');
    var oneItemName = $('.' + oneItemDiv + '  p.oneItemTitle').text();
    var finalValue = $('.' + second_div_cart + '  input').val();
    $.ajax({
        type: "POST",
        url: fullUrl,
        data: {
            itemId: oneItemName,
            cartPage: 1,
            quantity: 1
        },
        complete: function (data) {
            if (parseInt(finalValue) === 1) {
                location.reload();
            } else {
                $('.items_number').text(parseInt(items_number) - 1);
            }
            $('.' + second_div_cart + ' input').val(parseInt(finalValue) - 1);
            $('.' + oneItemDiv + ' .value_to_fill').val('{"item":"' + oneItemName + '","quantity":' + (parseInt(finalValue) - 1) + '}');
            getTotal();
        }
    });
}
