var this_frontend_home_url
var this_csrf_token


function frontendRegister(page, paramsArray) {  // constructor of frontend
    this_frontend_home_url = paramsArray.frontend_home_url;
    this_csrf_token = paramsArray.csrf_token;
} // function frontendRegister(Params) {


frontendRegister.prototype.onFrontendPageInit = function (page) {  // all vars/objects init
    frontendInit()
    if (page == "edit") {
    }

}