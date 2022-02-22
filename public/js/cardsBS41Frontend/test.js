
// alert( "public/js/cardsBS41Frontend/test.js::"+var_dump(-65) )


var this_frontend_home_url
var this_csrf_token


function test(page, paramsArray) {  // constructor of frontend test's editor - set all params from server
    this_frontend_home_url = paramsArray.frontend_home_url;
    this_csrf_token = paramsArray.csrf_token;
} // function test(Params) {  constructor of frontend test's editor - set all params from server


test.prototype.onFrontendPageInit = function (page) {  // all vars/objects init
    frontendInit()

    if (page == "view") {

    }

} // test.prototype.onFrontendPageInit= function(page) {


// 					<a href="#" class="active" id="aside_menu_item_general" onclick="javascript:asideMenuItemClicked('aside_menu_item_general')">
test.prototype.asideMenuItemClicked = function (clicked_menu_item) {
    // alert( "asideMenuItemClicked clicked_menu_tem::"+var_dump(clicked_menu_item) )

    // $("#td_id").attr('class', 'newClass');

    $("#aside_menu_item_general").removeAttr('class');
    $("#section_menu_item_general").css('display','none');

    $("#aside_menu_item_contacts").removeAttr('class');
    $("#section_menu_item_contacts").css('display','none');

    $("#aside_menu_item_sitemapping").removeAttr('class');
    $("#section_menu_item_sitemapping").css('display','none');

    if ( clicked_menu_item == 'aside_menu_item_general' ) {
        $("#aside_menu_item_general").attr('class', 'active');
        $("#section_menu_item_general").css('display','block');
    }

    if ( clicked_menu_item == 'aside_menu_item_contacts' ) {
        $("#aside_menu_item_contacts").attr('class', 'active');
        $("#section_menu_item_contacts").css('display','block');
    }

    if ( clicked_menu_item == 'aside_menu_item_sitemapping' ) {
        $("#aside_menu_item_sitemapping").attr('class', 'active');
        $("#section_menu_item_sitemapping").css('display','block');
    }
    // aside_menu_item_sitemapping

}


test.prototype.onSubmit = function () {
    var theForm = $("#form_test");
    alert( "theForm::"+var_dump(theForm) )
    theForm.submit();
}

////////// test Block End ///////////

//
// ////////// Payment Block Start ///////////
//
// test.prototype.downloadOnChange = function ( el, download_id ) {
//     // alert( "downloadOnChange download_id::"+download_id+"  el::"+var_dump(el) )
//
//     var download_selected_count = 0
//     var download_selected_price_sum = 0
//     var selected_download_ids = []
//     $('input.cbx_download:checked').each(function () {
//         // alert(  "CHECKED $(this).val()::"+($(this).val() ) +" $(this).id::"+this.id  )
//         var el_id = getSplitted(this.id, 'cbx_download_', 1)
//
//         selected_download_ids[selected_download_ids.length]= el_id
//         var el_price= $("#hidden_download_" + el_id + "_price").val()
//         // alert( "el_id::"+el_id +"  el_price::"+el_price )
//         download_selected_count++
//         download_selected_price_sum= download_selected_price_sum + parseFloat(el_price)
//     });
//     // 'hidden_download_{{ $nextDownload['id'] }}_price
//
//
//
//     // alert( "download_selected_count::"+(download_selected_count) +"  download_selected_price_sum::"+download_selected_price_sum )
//     if ( download_selected_count > 0 ) {
//         var href = this_frontend_home_url + "/format_currency/" + download_selected_price_sum;
//         $.ajax({
//             type: "GET",
//             dataType: "json",
//             url: href,
//             success: function (response) {
//                 $("#div_downloads_select").html( download_selected_count + " selected services at common sum <b>" + response.formatted_currency + "</b>" )
//                 $("#div_downloads_select").css('display', 'block')
//                 $("#div_payment_select").css('display', 'block')
//                 $("#hidden_selected_downloads").val(selected_download_ids )
//             },
//             error: function (error) {
//                 popupErrorMessage(error.responseJSON.message)
//             }
//         });
//
//     } else {
//         $("#div_payment_select").css('display', 'none')
//         $("#div_downloads_select").css('display', 'none')
//         $("#div_downloads_select").html(  )
//     }
//
// } // backendPageContent.prototype.is_videoOnChange = function () {
//
//
// test.prototype.onPaymentExecute = function () {
//     var theForm = $("#form_payment_execute");
//     // alert( "\"#tab_name_to_submit_\"+tab_name::"+("#tab_name_to_submit_"+tab_name) +"  tab_name::"+tab_name )
//     // $("#tab_name_to_submit_"+tab_name).val(tab_name)
//     theForm.submit();
//     // alert( "theForm::"+var_dump(theForm) )
// }
//
// ////////// Payment Block End ///////////
