// if all goes well, only the origin needs to be changed
var origin = "https://ifvent.com/sip/public";

var url = origin + "/api/user_journey/log_action";

var session_id = /SESS\w*ID=([^;]+)/i.test(document.cookie) ? RegExp.$1 : false;
var pathname = window.location.pathname;

// on script load, logs action to servers
function LogVisit(prefix) {
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            session_id: session_id,
            page: prefix + pathname
        },
        dataType: 'json',
        cache: false,
        success: function (json) {

        }
    });
}

// allows logging specific actions
function LogAction(prefix, object, action) {
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            session_id: session_id,
            page: prefix + pathname,
            object: object,
            action: action
        },
        dataType: 'json',
        cache: false,
        success: function (json) {

        }
    });
}