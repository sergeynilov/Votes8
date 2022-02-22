var this_frontend_home_url
var this_csrf_token


function showPageContent(page, paramsArray) {  // constructor of frontend showPageContent's editor - set all params from server
    // alert( "public/js/cardsBS41Frontend/show_page_content.jsparamsArray::"+var_dump(paramsArray) )
    this_frontend_home_url = paramsArray.frontend_home_url;
    this_csrf_token = paramsArray.csrf_token;
} // function showPageContent(Params) {  constructor of frontend showPageContent's editor - set all params from server

showPageContent.prototype.onFrontendPageInit = function (page) {  // all vars/objects init
    frontendInit()
    var deviceParams= effectiveDeviceWidth()
    // alert( "deviceParams::"+var_dump(deviceParams) )

    /* showPageContent */
    if ( typeof deviceParams.width!= "undefined" && typeof deviceParams.height!= "undefined" ) {

        $('.video_page_content').each(function () {
            var next_video_id = $(this).attr('id');
            var next_id = getSplitted(next_video_id, 'video_page_content_', 1)
            var next_width= $("#video_page_content_width_"+next_id).val()
            var next_height= $("#video_page_content_height_"+next_id).val()

            var ratio= next_width / next_height

            if ( deviceParams.width <= 320  ) {
                var max_width= 310
                // alert( "::"+(-1) + "#video_page_content_" +  next_video_id)
                $("#"+next_video_id).width(max_width)
                $("#"+next_video_id).height( parseInt(max_width / ratio) )
                // $(".video_page_content").height("auto")
                return
            }
            if ( deviceParams.width > 320 && deviceParams.width <= 480  ) {
                var max_width= 420
                // alert( "::"+(-2) + "#video_page_content_" +  next_video_id)
                $("#"+next_video_id).width(max_width)
                $("#"+next_video_id).height( parseInt(max_width / ratio) )
                return
            }

            if ( deviceParams.width > 480 && deviceParams.width <= 600  ) {
                var max_width= 410
                // alert( "::"+(-3) + "#video_page_content_" +  next_video_id)
                $("#"+next_video_id).width(max_width)
                $("#"+next_video_id).height( parseInt(max_width / ratio) )
                return
            }

            if ( deviceParams.width > 600 && deviceParams.width <= 768  ) {
                var max_width= 500
                // alert( "::"+(-4) + "#video_page_content_" +  next_video_id)
                $("#"+next_video_id).width(max_width)
                $("#"+next_video_id).height( parseInt(max_width / ratio) )
                return
            }
            if ( deviceParams.width > 768 && deviceParams.width <= 1024  ) {
                var max_width= 660
                // alert( "::"+(-5) + "#video_page_content_" +  next_video_id)
                $("#"+next_video_id).width(max_width)
                $("#"+next_video_id).height( parseInt(max_width / ratio) )
                return
            }
            if ( deviceParams.width > 1024   ) {
                var max_width= 800
                // alert( "::"+(-6) + "#video_page_content_" +  next_video_id)
                $("#"+next_video_id).width(max_width)
                $("#"+next_video_id).height( parseInt(max_width / ratio) )
                return
            }

        });
    }

} // showPageContent.prototype.onFrontendPageInit= function(page) {

////////// showPageContent Block End ///////////



////////// Payment Block Start ///////////

showPageContent.prototype.downloadOnChange = function ( el, download_id ) {
    // alert( "downloadOnChange download_id::"+download_id+"  el::"+var_dump(el) )

    var download_selected_count = 0
    var download_selected_price_sum = 0
    var selected_download_ids = []
    $('input.cbx_download:checked').each(function () {
        // alert(  "CHECKED $(this).val()::"+($(this).val() ) +" $(this).id::"+this.id  )
        var el_id = getSplitted(this.id, 'cbx_download_', 1)

        selected_download_ids[selected_download_ids.length]= el_id
        var el_price= $("#hidden_download_" + el_id + "_price").val()
        // alert( "el_id::"+el_id +"  el_price::"+el_price )
        download_selected_count++
        download_selected_price_sum= download_selected_price_sum + parseFloat(el_price)
    });
    // 'hidden_download_{{ $nextDownload['id'] }}_price



    // alert( "download_selected_count::"+(download_selected_count) +"  download_selected_price_sum::"+download_selected_price_sum )
    if ( download_selected_count > 0 ) {
        var href = this_frontend_home_url + "/format_currency/" + download_selected_price_sum;
        $.ajax({
            type: "GET",
            dataType: "json",
            url: href,
            success: function (response) {
                $("#div_downloads_select").html( download_selected_count + " selected service(s) at common sum <b>" + response.formatted_currency + "</b>" )
                $("#div_downloads_payment_description").css('display', 'block')
                $("#div_downloads_select").css('display', 'block')
                $("#div_payment_select").css('display', 'flex')
                $("#hidden_selected_downloads").val(selected_download_ids )
            },
            error: function (error) {
                popupErrorMessage(error.responseJSON.message)
            }
        });

    } else {
        $("#div_payment_select").css('display', 'none')
        $("#div_downloads_select").css('display', 'none')
        $("#div_downloads_payment_description").css('display', 'none')
        $("#div_downloads_select").html(  )
    }

} // backendPageContent.prototype.is_videoOnChange = function () {


showPageContent.prototype.onPaymentExecute = function () {
    let payment_type = $('input[name=payment_type]:checked').val();
    // alert( "payment_type::"+var_dump(payment_type) )
    if ( typeof payment_type == "undefined" ) {
        popupAlert("Select payment method !", 'danger')
        $("#payment_type").focus();
        return;
    }

    var theForm = $("#form_payment_execute");
    theForm.submit();
}

////////// Payment Block End ///////////
