// alert( "print_to_pdf.js::"+var_dump(-8) )
var this_frontend_home_url
var this_csrf_token


var this_selected_option_background_color= ''
var this_selected_option_title_font_color= ''
var this_selected_option_subtitle_font_color= ''
var this_selected_option_content_font_color= ''
var this_selected_option_notes_font_color= ''


function frontendPrintToPdf(page, paramsArray) {  // constructor of frontend PrintToPdfOptions's editor - set all params from server
    this_frontend_home_url = paramsArray.frontend_home_url;
    this_csrf_token = paramsArray.csrf_token;
    // alert( "page::"+page+"  this_csrf_token "+this_csrf_token+"  frontendPrintToPdf paramsArray::"+var_dump(paramsArray) )
} // function frontendPrintToPdf(Params) {  constructor of frontend PrintToPdfOptions's editor - set all params from server


frontendPrintToPdf.prototype.onFrontendPageInit = function (page) {  // all vars/objects init
    // alert( "frontendPrintToPdf  onFrontendPageInit ::" )
    frontendInit()

    if (page == "view") {
    }

} // frontendPrintToPdf.prototype.onFrontendPageInit= function(page) { 



frontendPrintToPdf.prototype.generatePrintToPdfOptions = function () {
    var option_output_file_format  = $("#option_output_file_format").val();
    var option_output_filename  = $("#option_output_filename").val();
    if ( option_output_file_format == '' ) {
        popupAlert("Select output format !", 'danger')
        $("#option_output_file_format").focus()
        return;
    }

    var pdf_content  = $("#div_profile_content").html();
    $("#pdf_content").val( escapeHtml(pdf_content) );

    $("#option_output_filename").val( option_output_filename );
    var theForm = $("#form_print_to_pdf_content");
    theForm.submit();
}

frontendPrintToPdf.prototype.openPrintToPdfOptions = function () {

    // alert( "openPrintToPdfOptions AFTER::"+var_dump(-5) )

    var href = this_frontend_home_url + "/profile/print-to-pdf-options";

    $.ajax(
        {
            type: "GET",
            dataType: "json",
            url: href,
            success: function( response )
            {
                $("#div_print_to_pdf_options_results").html(response.html)
                $("#div_print_to_pdf_options_modal").modal({
                    "backdrop": "static",
                    "keyboard": true,
                    "show": true
                });


                $('#option_background_color').colorpicker();

                $('#option_background_color').on('change', function(event) {
                    this_selected_option_background_color= event.color.toHexString()
                    $('#option_background_color_mark > i').css('background-color',this_selected_option_background_color);
                    $('#option_background_color').colorpicker('hide');
                });

                $('#option_title_font_color').colorpicker();
                $('#option_title_font_color').on('change', function(event) {
                    this_selected_option_title_font_color= event.color.toHexString()
                    $('#option_title_font_color_mark > i').css('background-color',this_selected_option_title_font_color);
                    $('#option_title_font_color').colorpicker('hide');
                });

                $('#option_subtitle_font_color').colorpicker();
                $('#option_subtitle_font_color').on('change', function(event) {
                    this_selected_option_subtitle_font_color= event.color.toHexString()
                    $('#option_subtitle_font_color_mark > i').css('background-color',this_selected_option_subtitle_font_color);
                    $('#option_subtitle_font_color').colorpicker('hide');
                });

                $('#option_content_font_color').colorpicker();
                $('#option_content_font_color').on('change', function(event) {
                    this_selected_option_content_font_color= event.color.toHexString()
                    $('#option_content_font_color_mark > i').css('background-color',this_selected_option_content_font_color);
                    $('#option_content_font_color').colorpicker('hide');
                });

                $('#option_notes_font_color').colorpicker();
                $('#option_notes_font_color').on('change', function(event) {
                    this_selected_option_notes_font_color= event.color.toHexString()
                    $('#option_notes_font_color_mark > i').css('background-color',this_selected_option_notes_font_color);
                    $('#option_notes_font_color').colorpicker('hide');
                });


                // alert( "::"+var_dump(-77) )
            },
            error: function( error )
            {
                popupErrorMessage(error.responseJSON.message)
            }
        }


    );



}

//
//
frontendPrintToPdf.prototype.selectedPrintToPdfOptions = function () {

    // alert( "selectedPrintToPdfOptions this_selected_option_background_color::"+(this_selected_option_background_color) )
    $("#background_color").val( this_selected_option_background_color )
    $("#title_font_color").val( this_selected_option_title_font_color )
    $("#subtitle_font_color").val( this_selected_option_subtitle_font_color )
    $("#content_font_color").val( this_selected_option_content_font_color )
    $("#notes_font_color").val( this_selected_option_notes_font_color )


    $("#title_font_name").val( $("#title_font_name").val() )
    $("#title_font_size").val( $("#option_title_font_size").val() )
    $("#title_font_color").val( this_selected_option_title_font_color )

    $("#subtitle_font_name").val( $("#option_subtitle_font_name").val() )
    $("#subtitle_font_size").val( $("#option_subtitle_font_size").val() )
    $("#subtitle_font_color").val( this_selected_option_subtitle_font_color )

    $("#content_font_name").val( $("#option_content_font_name").val() )
    $("#content_font_size").val( $("#option_content_font_size").val() )
    $("#content_font_color").val( this_selected_option_content_font_color )

    $("#notes_font_name").val( $("#option_notes_font_name").val() )
    $("#notes_font_size").val( $("#option_notes_font_size").val() )
    $("#notes_font_color").val( this_selected_option_notes_font_color )

    var theForm = $("#form_print_to_pdf_options_dialog");
    theForm.submit();

} // selectedPrintToPdfOptions


frontendPrintToPdf.prototype.printToPdf = function () {  // all vars/objects init

    document.location= this_frontend_home_url + "/printToPdfOptions/print-to-pdf";
    return;
    var href = this_frontend_home_url + "/printToPdfOptions/print-to-pdf";
    $.ajax({
        type: "GET",
        // type: "POST",
        dataType: "json",
        url: href,
        // data: {"_token": this_csrf_token},
        success: function (response) {
            popupAlert("Your printToPdfOptions was printed to pdf file successfully !", 'success')
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }
    });

    //     }
    // );


} // printToPdf.prototype.printToPdf

////////// PrintToPdfOptions Block End ///////////
