var this_backend_home_url
var this_id
var this_csrf_token


function backendVoteItem(page, paramsArray) {  // constructor of backend Vote item's editor - set all params from server
// alert( "page::"+page+"  backendVoteItem paramsArray::"+var_dump(paramsArray) )
    this_backend_home_url = paramsArray.backend_home_url;
    this_backend_per_page = paramsArray.backend_per_page;
    this_csrf_token = paramsArray.csrf_token;
    if (page == "edit") {
        this_id = paramsArray.id;
    }
} // function backendVoteItem(Params) {  constructor of backend Vote's editor - set all params from server


backendVoteItem.prototype.onBackendPageInit = function (page) {  // all vars/objects init
    // alert( "backendVoteItem  onBackendPageInit this_id::"+this_id )
    backendInit()
    if (page == "edit") {
        $('#pills-tab a').on('click', function (e) {
            e.preventDefault()
            $(this).tab('show')
        })
    }
} // backendVoteItem.prototype.onBackendPageInit= function(page) {  // all vars/objects init and load sublistings


backendVoteItem.prototype.onSubmit = function () {
    var theForm = $("#form_vote_item_edit");
    theForm.submit();
}


