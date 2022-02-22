  function chosenSelectionOnChange( field_name ) {
     var dest_field_name= field_name
     var src_value= $( '#chosen_'+field_name ).val()
     $('#'+dest_field_name).val( src_value )
     if ( jQuery.trim(src_value) != "" ) {
         $( '#'+field_name+'-error').html("")
     }
 }

 function showSearchDialog() {
     var href= '/get-most-used-search-results';
     var listing_input_search= jQuery.trim($('#listing_input_search').val())
     var hidden_vote_categories= $('#hidden_vote_categories').val()

     // alert( "showSearchDialog listing_input_search::"+(listing_input_search) + "  hidden_vote_categories::"+hidden_vote_categories)

     $.ajax(
         {
             type: "GET",
             dataType: "json",
             url: href,
             success: function( response )
             {
                 $("#div_most_used_search_results").html(response.html)
                 $("#div_frontend_search_modal").modal({
                     "backdrop": "static",
                     "keyboard": true,
                     "show": true
                 });
                 $('#input_search').val( listing_input_search )
                 $('#input_search').focus()

                 if ( typeof hidden_vote_categories != "undefined" ) {
                     $("#voteCategories").val(hidden_vote_categories.split(','))
                 }

             },
             error: function( error )
             {
                 popupErrorMessage(error)
             }
         }
     );
 }

 function runSearchDialog() {
     var input_search= jQuery.trim($('#input_search').val())
     if (input_search=="") {
         popupAlert("Enter search text !", 'danger')
         jQuery.trim($('#input_search').focus())
         return;
     }
     var search_in_blocks= '-'
     var vote_categories= jQuery.trim($('#voteCategories').val())
     if ( vote_categories=='' ) {
         vote_categories= '-'
     }
     document.location='/search-results/'+encodeURIComponent(input_search)+"/"+vote_categories+"/"+search_in_blocks
 }

 function runSearchListing() {
     var listing_input_search= jQuery.trim($('#listing_input_search').val())
     if (listing_input_search=="") {
         popupAlert("Enter search text !", 'danger')
         jQuery.trim($('#listing_input_search').focus())
         return;
     }
     var search_in_blocks= '-'
     var vote_categories= jQuery.trim($('#voteCategories').val())
     if ( vote_categories=='' ) {
         vote_categories= '-'
     }

     document.location='/search-results/'+encodeURIComponent(listing_input_search)+"/"+vote_categories+"/"+search_in_blocks
 }

 function selectSearchText(selected_text) {
     // alert( "selectSearchText selected_text::"+var_dump(selected_text) )
     $('#input_search').val(selected_text)
 }

 function initTinyMCEEditor(by_selector_container, by_selector, width, height) {
     var deviceParams= effectiveDeviceWidth()
     var calculated_width= getCalculatedWidth(deviceParams.width)
     if ( typeof calculated_width != "undefined" ) {
         width = getCalculatedWidth(deviceParams.width)
     }
     // alert( "initTinyMCEEditor width::"+(width) + "  by_selector_container::"+by_selector_container)

     var editor_config = {
         path_absolute : "/",
         selector: '#' + by_selector_container,

         setup: function (editor) {
             editor.on('change', function () {
                 var current_context= tinymce.get(by_selector_container).getContent()
                 // alert( "current_context::"+var_dump(current_context) )
                 $('#' + by_selector).html( current_context );
             });
         },


         theme: 'modern',
         width: width,
         height: height,
         plugins: [
             "advlist autolink lists link image charmap print preview hr anchor pagebreak",
             "searchreplace wordcount visualblocks visualchars code fullscreen",
             "insertdatetime media nonbreaking save table contextmenu directionality",
             "emoticons template paste textcolor colorpicker textpattern"
         ],
         toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
         relative_urls: false,
         file_browser_callback : function(field_name, url, type, win) {
             var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
             var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

             var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
             if (type == 'image') {
                 cmsURL = cmsURL + "&type=Images";
             } else {
                 cmsURL = cmsURL + "&type=Files";
             }

             tinyMCE.activeEditor.windowManager.open({
                 file : cmsURL,
                 title : 'Filemanager',
                 width : x * 0.8,
                 height : y * 0.8,
                 resizable : "yes",
                 close_previous : "no"
             });
         }
     };

     tinymce.init(editor_config);
     // alert( "AFTER initTinyMCEEditor::"+var_dump(-99) )
 }  // function initTinyMCEEditor(by_selector_container, by_selector, width, height) {


 function getCalculatedWidth(device_width) {
     if ( device_width <= 320  ) {
         // alert( -1 )
         return 300
     }
     if ( device_width > 320 && device_width <= 480  ) {
         // alert( -2 )
         return 320
     }

     if ( device_width > 480 && device_width <= 600  ) {
         // alert( -3 )
         return 310
     }

     if ( device_width > 600 && device_width <= 768  ) {
         // alert( -4 )
         return 470
     }
     if ( device_width > 768 && device_width <= 1024  ) {
         // alert( -5 )
         return 480
     }
     if ( device_width > 1024   ) {
         // alert( -6 )
         return 650
     }
 }


 function checkEmail(value) {
     regex=/^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/;
     return regex.test(value);
 }

 function checkInteger(value) {
     regex=/^[0-9]+$/;
     return regex.test(value);
 }

 function checkisNormalInteger(str, can_be_zero) {
     var n = Math.floor(Number(str));
     if ( can_be_zero ) {
         return String(n) === str && n >= 0;
     } else {
         return String(n) === str && n > 0;
     }
 }

 function checkFloat(value) {
     // value= "87.";  //"2034.33";
     // regex= /^\d+(\.\d\d)?$/;
     regex= /^\d+(\.\d{0,2})?$/;
     // regex= /^[1-9]\d{2,4}([\.,]\d\d)?$/;
     // alert( regex+"  value::"+value )
     return regex.test(value);
     //return isNaN(parseFloat(value))
 }


 function getSplitted(str, splitter, index) {
     var value_arr = str.split( splitter );
     if (typeof value_arr[index] != "undefined" ) {
         return value_arr[index];
     }
     return '';
 }

 function popupErrorMessage(error_message) {
     popupAlert("System error : "+error_message, 'danger')
 }

 function popupAlert(text, text_type, delay) {
     // alert( "popupAlert  text::"+var_dump(text) +" text_type::"+var_dump(text_type) )
     // return
     if ( typeof text_type == "undefined") {
         text_type= "info"
     }
     $.bootstrapGrowl(text, {
         type: text_type,
         align: 'center',
         width: 450,
         offset: {from: 'top', amount: 200},
         delay: (typeof delay != "undefined") ? 5000 : delay,
         stackup_spacing: 20
     });

 }

 function dbDateTimeToDateTimePicker(dat) {
     // debugger
     var d= moment(dat).format('YYYY-MM-DD hh:mm a')     // from db format to datetime
     var str= moment(dat).format('DD MMMM, YYYY hh:mm a')
     return str;
 }

 function dbDateToDatePicker(dat) {
     // debugger
     var d= moment(dat).format('YYYY-MM-DD')     // from db format to datetime
     var str= moment(dat).format('DD MMMM, YYYY')
     return str;
 }


function frontendInit() {
    if(jQuery().Chosen) {
        $(".chosen_select_box").chosen({
            disable_search_threshold: 10,
            allow_single_deselect: true,
            no_results_text: "Nothing found!",
        });
    }
    //
    // alert( "frontendInit::"+var_dump(11) )
    // return;
} // function frontendInit() {
  

 function close_div_action_text_alert() {
     $("#div_action_text_alert").css("display", "none")
 }

function preg_quote(str) {	// Quote regular expression characters
    return str.replace(/([\\\.\+\*\?\[\^\]\$\(\)\{\}\=\!\<\>\|\:])/g, "\\$1");
}


function addDaysToDate(add_days, current_date) {
    if (typeof current_date == "undefined") {
        current_date = new Date();
    }
    current_date.setDate(current_date.getDate() + add_days);
    return current_date;
}


function inArray(needle, haystack) {
    var length = haystack.length;
    for (var i = 0; i < length; i++) {
        if (haystack[i] == needle) return true;
    }
    return false;
}

function effectiveDeviceWidth(param) {
    var viewport = {
        width: $(window).width(),
        height: $(window).height()
    };
    // alert( "viewport::"+var_dump(viewport) )
    if (typeof param != "undefined") {
        if (param.toLowerCase() == 'width') {
            return viewport.width;
        }
        if (param.toLowerCase() == 'height') {
            return viewport.height;
        }
    }
    return viewport;
    //var deviceWidth = window.orientation == 0 ? window.screen.width : window.screen.height;
    //// iOS returns available pixels, Android returns pixels / pixel ratio
    //// http://www.quirksmode.org/blog/archives/2012/07/more_about_devi.html
    //if (navigator.userAgent.indexOf('Android') >= 0 && window.devicePixelRatio) {
    //    deviceWidth = deviceWidth / window.devicePixelRatio;
    //}
    //return deviceWidth;
}


function getBootstrapPlugins() {
    var ret_str = '';
    ret_str += "alert:" + ( (typeof($.fn.alert) != 'undefined') ? " <b>On</b>" : "Off" ) + ". ";
    ret_str += "button:" + ( (typeof($.fn.button) != 'undefined') ? " <b>On</b>" : "Off" ) + ". ";
    ret_str += "carousel:" + ( (typeof($.fn.carousel) != 'undefined') ? " <b>On</b>" : "Off" ) + ". ";
    ret_str += "dropdown:" + ( (typeof($.fn.dropdown) != 'undefined') ? " <b>On</b>" : "Off" ) + ". ";
    ret_str += "modal:" + ( (typeof($.fn.modal) != 'undefined') ? " <b>On</b>" : "Off" ) + ". ";
    ret_str += "tooltip:" + ( (typeof($.fn.tooltip) != 'undefined') ? " <b>On</b>" : "Off" ) + ". ";
    ret_str += "popover:" + ( (typeof($.fn.popover) != 'undefined') ? " <b>On</b>" : "Off" ) + ". ";
    ret_str += "tab:" + ( (typeof($.fn.tab) != 'undefined') ? " <b>On</b>" : "Off" ) + ". ";
    ret_str += "affix:" + ( (typeof($.fn.affix) != 'undefined') ? " <b>On</b>" : "Off" ) + ". ";
    ret_str += "collapse:" + ( (typeof($.fn.collapse) != 'undefined') ? " <b>On</b>" : "Off" ) + ". ";
    ret_str += "scrollspy:" + ( (typeof($.fn.scrollspy) != 'undefined') ? " <b>On</b>" : "Off" ) + ". ";
    ret_str += "transition:" + ( (typeof($.fn.transition) != 'undefined') ? " <b>On</b>" : "Off" ) + ". ";
    //ret_str+= "alert:" + ( (typeof($.fn.alert) != 'undefined') ? " <b>On</b>" :"Off" ) + ". ";
    return ret_str;
}


function formatNumberToHuman(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}

function convertMinsToHrsMins(minutes) {
    if (minutes < 60) return (minutes + ' minutes');
    var h = Math.floor(minutes / 60);
    var m = minutes % 60;
    h = h < 10 ? h : h;
    m = m < 10 ? m : m;
    return h + ':' + m + ' hour(s)';
}


function dateToMySqlFormat(dat) {
    if (typeof dat != 'object') return null;
    // alert( "dat::"+(dat) +  "   typeof  dat::"+(typeof dat) + "  dat::"+var_dump(dat) )

    var mm = dat.getMonth() + 1; // getMonth() is zero-based
    var dd = dat.getDate();

    return [dat.getFullYear(),
        (mm > 9 ? '' : '0') + mm,
        (dd > 9 ? '' : '0') + dd
    ].join('-');
}


function formatColor(rgb) {
    var isOk = /^#[0-9A-F]{6}$/i.test(rgb)
    // var isOk  = /^#[0-9A-F]{6}$/i.test('#aabbcc')
    // alert( "isOk::"+isOk )
    if (isOk) return rgb;

    // alert( typeof rgb )
    if (typeof rgb != "string" || this.trim(rgb) == "") return "";
    rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
    return (rgb && rgb.length === 4) ? "#" +
        ("0" + parseInt(rgb[1], 10).toString(16)).slice(-2) +
        ("0" + parseInt(rgb[2], 10).toString(16)).slice(-2) +
        ("0" + parseInt(rgb[3], 10).toString(16)).slice(-2) : '';
}

function trim(str) {
    if (typeof str != "string") return "";
    return str.replace(/^\s+|\s+$/gm, '');
}

  function concatStrings (dataArray, splitter) {
      var ret = '';
      // alert( "dataArray::"+(typeof dataArray)+"   "+var_dump(dataArray) )
      var L = dataArray.length;
      let resArray= []
      for ( let I = 0; I< L; I++ ) {
          var next_string = this.trim(dataArray[I]);
          // alert( "next_string::"+(next_string) + (typeof next_string) +"  splitter::"+splitter )
          // if ( typeof next_string != "undefined" && typeof next_string != "string" ) {
          if (typeof next_string == "string") {
              if ( next_string.length > 0) {
                  resArray[resArray.length] = next_string;
              }
          }
      }
      var L = resArray.length;
      resArray.map((next_string, index) => {
          // next_string = jQuery.trim(next_string);
          var next_string = this.trim(next_string);
          // alert( "next_string::"+(next_string) + (typeof next_string) +"  splitter::"+splitter )
          // if ( typeof next_string != "undefined" && typeof next_string != "string" ) {
          if (typeof next_string == "string") {
              if (next_string) {
                  if (L === index + 1) {
                      ret = ret + next_string;
                  } else {
                      ret = ret + next_string + splitter;
                  }
              } // if ( next_string ) {
          }
      });
      return ret;
  }

// function concatStrings(dataArray, splitter) {
//     var ret = '';
//     // alert( "dataArray::"+(typeof dataArray)+"   "+var_dump(dataArray) )
//     const l = dataArray.length;
//     dataArray.map((next_string, index) => {
//         // next_string = jQuery.trim(next_string);
//         next_string = this.trim(next_string);
//         // alert( "next_string::"+(next_string) + (typeof next_string) +"  splitter::"+splitter )
//         // if ( typeof next_string != "undefined" && typeof next_string != "string" ) {
//         if (typeof next_string == "string") {
//             if (next_string) {
//                 if (l === index + 1) {
//                     ret = ret + next_string;
//                 } else {
//                     ret = ret + next_string + splitter;
//                 }
//             } // if ( next_string ) {
//         }
//     });
//     return ret;
// }

/*
        showPopupMessage: function (message, type) {
            if (type == 'success') {         // https://www.npmjs.com/package/v-toaster
                this.$toaster.success(message)
            }
            if (type == 'info') {
                this.$toaster.info(message)
            }
            if (type == 'error') {
                this.$toaster.error(message)
            }
            if (type == 'warning') {
                this.$toaster.warning(message)
            }
        }, // showPopupMessage: function (message, type) {

*/

function concatStr(str, max_str_length_in_listing) {
    if (typeof settings_max_str_length_in_listing == "undefined" && typeof max_str_length_in_listing != "undefined") {
        var settings_max_str_length_in_listing = max_str_length_in_listing
    }

    if (typeof str == "undefined") str = '';
    if (str.length > settings_max_str_length_in_listing) {
        return str.slice(0, settings_max_str_length_in_listing) + '...';
    }
    return str;
}

function getNowDateTime() {
    return Date.now();
}

function getNowTimestamp() {
    return Date.now() / 1000 | 0;
}


function getFileSizeAsString(file_size) {
    if (parseInt(file_size) < 1024) {
        return file_size + 'b';
    }
    if (parseInt(file_size) < 1024 * 1024) {
        return Math.floor(file_size / 1024) + 'kb';
    }
    return Math.floor(file_size / (1024 * 1024)) + 'mb';
}


function Capitalize(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}


function confirmMsg(question, confirm_function_code, title, icon) {

    if ( confirm(question) ) {
        confirm_function_code();
    }
    return;
    if (typeof title == "undefined" || this.trim(title) == "") {
        title = 'Confirm!'
    }
    if (typeof icon == "undefined" || this.trim(icon) == "") {
        icon = 'fa fa-signal'
    }

    $.confirm({
        icon: icon,
        title: title,
        content: question,
        columnClass: 'col-md-8 col-md-offset-2  col-sm-8 col-sm-offset-2 ',
        buttons: {
            OK: {
                text: 'OK',
                btnClass: 'btn-blue',
                keys: ['enter', 'a'],
                isHidden: false,
                isDisabled: false,
                action: function (OKButton) {
                    confirm_function_code()
                }
            },
            cancel: {
                text: 'Cancel',
                btnClass: 'ml-4 btn-grey',
                keys: ['esc', 'e'],
                isHidden: false,
                isDisabled: false,
                action: function (cancelButton) {
                }
            },
        }
    });

}

function func_setting_focus(focus_field) {
    $('#' + focus_field).focus();
}

function alertMsg(content, title, confirm_button, icon, focus_field) {
    $.alert({
        title: title,
        content: content,
        icon: ( typeof icon != 'undefined' ? icon : 'fa fa-info-circle' ),
        confirmButton: ( typeof confirm_button != 'undefined' ? confirm_button : 'OK' ),
        keyboardEnabled: true,
        columnClass: 'col-md-8 col-md-offset-2  col-sm-8 col-sm-offset-2 ',
        confirm: function () {
            setTimeout("func_setting_focus('" + focus_field + "')", 500);
        }
    });
}

function br2nl(str) {
    return str.replace(/<br\s*\/?>/mg, "\n");
}

function nl2br(str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

function replaceAll(str, search, replacement) {
    return str.replace(new RegExp(search, 'g'), replacement);
}


function prettyJSON(json) {
    if (json) {
        json = JSON.stringify(json, undefined, 4);
        json = json.replace(/&/g, '&').replace(/</g, '<').replace(/>/g, '>');
        return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
            var cls = 'number';
            if (/^"/.test(match)) {
                if (/:$/.test(match)) {
                    cls = 'key';
                } else {
                    cls = 'string';
                }
            } else if (/true|false/.test(match)) {
                cls = 'boolean';
            } else if (/null/.test(match)) {
                cls = 'null';
            }
            return '<span class="' + cls + '">' + match + '</span>';
        });
    }
}


 function escapeHtml(text) {
     return text
         .replace(/&/g, "&amp;")
         .replace(/</g, "&lt;")
         .replace(/>/g, "&gt;")
         .replace(/"/g, "&quot;")
         .replace(/'/g, "&#039;");
 }

function var_dump(oElem, from_line, till_line) {
    if (typeof oElem == 'undefined') return 'undefined';
    var sStr = '';
    if (typeof(oElem) == 'string' || typeof(oElem) == 'number') {
        sStr = oElem;
    } else {
        var sValue = '';
        for (var oItem in oElem) {
            sValue = oElem[oItem];
            if (typeof(oElem) == 'innerHTML' || typeof(oElem) == 'outerHTML') {
                sValue = sValue.replace(/</g, '&lt;').replace(/>/g, '&gt;');
            }
            sStr += 'obj.' + oItem + ' = ' + sValue + '\n';
        }
    }
    //alert( "from_line::"+(typeof from_line) )
    if (typeof from_line == "number" && typeof till_line == "number") {
        return sStr.substr(from_line, till_line);
    }
    if (typeof from_line == "number") {
        return sStr.substr(from_line);
    }
    return sStr;
}
