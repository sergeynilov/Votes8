var this_frontend_home_url
var this_csrf_token


function publicProfile(page, paramsArray) {  // constructor of frontend publicProfile's editor - set all params from server
    this_frontend_home_url = paramsArray.frontend_home_url;
    this_csrf_token = paramsArray.csrf_token;
} // function publicProfile(Params) {  constructor of frontend publicProfile's editor - set all params from server


publicProfile.prototype.onFrontendPageInit = function (page) {  // all vars/objects init
    frontendInit()

    if (page == "view") {

    }

} // publicProfile.prototype.onFrontendPageInit= function(page) { 

////////// publicProfile Block End ///////////