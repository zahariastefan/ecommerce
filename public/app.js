// function addToCart(e, path) {
//     var fullUrl = window.location.origin + path;
//     var items_number = $('.items_number').text();
//     var second_div_cart0 = $(e).parent().attr('class');
//     var second_div_cart = (second_div_cart0.split(' '))[0];
//     var oneItemDiv0 = $(e).parent().parent().attr('class');
//     var oneItemDiv = (oneItemDiv0.split(' '))[0];
//     var oneItemName = $('.' + oneItemDiv + '  p.oneItemTitle').text();
//     var finalValue = $('.' + second_div_cart + '  input').val();
//     $.ajax({
//         type: "POST",
//         url: fullUrl,
//         data: {
//             itemId: oneItemName,
//             cartPage: 1,
//         },
//         complete: function (data) {
//             $('.' + second_div_cart + '  input').val(parseInt(finalValue) + 1);
//             $('.' + oneItemDiv + '  .value_to_fill').val('{"item":"' + oneItemName + '","quantity":' + (parseInt(finalValue) + 1) + '}');
//             $('.items_number').text(parseInt(items_number) + 1);
//             if(parseInt($('.items_number').text()) >= 100){
//                 $(".items_number").animate({
//                     width: '25px',
//                     height: '25px',
//                     'font-size': '18px',
//                     left: '56px'
//                 }, 100, function(){
//                     $(".items_number").animate({
//                         width: "18px",
//                         height: "20px",
//                         left: '52',
//                         'padding-top': "2px",
//                         'padding-right': "20px",
//                         'font-size': "10px",
//                     }, 100);
//                 });
//             }else{
//                 $(".items_number").animate({
//                     width: '25px',
//                     height: '25px',
//                     'font-size': '18px',
//                     left: '56px'
//                 }, 100, function(){
//                     $(".items_number").animate({
//                         width: "18px",
//                         height: "18px",
//                         left: '52',
//                         'padding-top': "0px",
//                         'padding-right': "0px",
//                         'font-size': "13px",
//                     }, 100);
//                 });
//             }
//             if(parseInt($('.items_number').text()) >= 10 && parseInt($('.items_number').text()) < 100){
//                 $('.items_number').css('text-align','left');
//             }
//             if(parseInt($('.items_number').text()) < 10){
//                 $('.items_number').css('text-align','center');
//             }
//             getTotal();
//         }
//     });
// }
//
// function removeToCart(e, path2) {
//     var fullUrl = window.location.origin + path2;
//     var items_number = $('.items_number').text();
//     var second_div_cart = $(e).parent().attr('class');
//     var oneItemDiv = $(e).parent().parent().attr('class');
//     var oneItemName = $('.' + oneItemDiv + '  p.oneItemTitle').text();
//     var finalValue = $('.' + second_div_cart + '  input').val();
//     $.ajax({
//         type: "POST",
//         url: fullUrl,
//         data: {
//             itemId: oneItemName,
//             cartPage: 1,
//             quantity: 1
//         },
//         complete: function (data) {
//             if (parseInt(finalValue) === 1) {
//                 location.reload();
//             } else {
//                 $('.items_number').text(parseInt(items_number) - 1);
//             }
//             $('.' + second_div_cart + ' input').val(parseInt(finalValue) - 1);
//             $('.' + oneItemDiv + ' .value_to_fill').val('{"item":"' + oneItemName + '","quantity":' + (parseInt(finalValue) - 1) + '}');
//             getTotal();
//             if(parseInt($('.items_number').text()) >= 100){
//                 $(".items_number").animate({
//                     width: '25px',
//                     height: '25px',
//                     'font-size': '18px',
//                     left: '56px'
//                 }, 100, function(){
//                     $(".items_number").animate({
//                         width: "18px",
//                         height: "20px",
//                         left: '52',
//                         'padding-top': "2px",
//                         'padding-right': "20px",
//                         'font-size': "10px",
//                     }, 100);
//                 });
//             }else{
//                 $(".items_number").animate({
//                     width: '25px',
//                     height: '25px',
//                     'font-size': '18px',
//                     left: '56px'
//                 }, 100, function(){
//                     $(".items_number").animate({
//                         width: "18px",
//                         height: "18px",
//                         left: '52',
//                         'padding-top': "0px",
//                         'padding-right': "0px",
//                         'font-size': "13px",
//                     }, 100);
//                 });
//             }
//             if(parseInt($('.items_number').text()) >= 10 && parseInt($('.items_number').text()) < 100){
//                 $('.items_number').css('text-align','left');
//             }
//             if(parseInt($('.items_number').text()) < 10){
//                 $('.items_number').css('text-align','center');
//             }
//         }
//     });
// }