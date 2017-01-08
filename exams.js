// Your Client ID can be retrieved from your project in the Google
// Developer Console, https://console.developers.google.com
var CLIENT_ID = '997372761535-10k4lovop0tc9iuo1099khm7354undpk.apps.googleusercontent.com';

var SCOPES = ["https://www.googleapis.com/auth/calendar"];

/**
* Check if current user has authorized this application.
*/
function checkAuth() {
    gapi.auth.authorize(
    {
        'client_id': CLIENT_ID,
        'scope': SCOPES.join(' '),
        'immediate': true
    }, handleAuthResult);
}

/**
* Handle response from authorization server.
*
* @param {Object} authResult Authorization result.
*/
function handleAuthResult(authResult) {
    var authorizeDiv = document.getElementById('authorize-div');
    if (authResult && !authResult.error) {
        // Hide auth UI, then load client library.
        authorizeDiv.style.display = 'none';
        $("#pleaseWait").show();
        loadCalendarApi();
    } else {
        // Show auth UI, allowing the user to initiate authorization by
        // clicking authorize button.
        authorizeDiv.style.display = 'inline';
    }
}

/**
* Initiate auth flow in response to user clicking authorize button.
*
* @param {Event} event Button click event.
*/
function handleAuthClick(event) {
    gapi.auth.authorize(
        {client_id: CLIENT_ID, scope: SCOPES, immediate: false},
        handleAuthResult);
    return false;
}

/**
* Load Google Calendar client library. List calendars
* once client library is loaded.
*/
function loadCalendarApi() {
    gapi.client.load('calendar', 'v3', listCalendars);
}

/**
* TODO
*/
function listCalendars() {
    var request = gapi.client.calendar.calendarList.list({});

    request.execute(function(resp) {
        var calendars = resp.items;

        if (calendars.length > 0) {
            for (i = 0; i < calendars.length; i++) {
                var calendar = calendars[i];
                if(calendar.accessRole == 'writer'
                    || calendar.accessRole == 'owner' )
                    $("#calendars").append(
                        $('<option />')
                        .attr('value', calendar.id)
                        .html( calendar.summary )
                        );
            }
        }
        if (chosen_browser_is_supported()) {
            $("#calendars").chosen({width: "450px"});
        }else{
            $("#calendars").show();
        }
        checkSchedulePdf();
    });
}

function checkSchedulePdf () {
    $.ajax({
        url: "fetch-schedule.php",
        success: function(result){
            toastr.info('Pdf last updated: ' + (new Date(result)).toUTCString());
            listSubjects();
        }
    });

}

function listSubjects () {
    $.ajax({
        url: "api.py",
        success: function(result){
            for (var i = 0; i < result.length; i++) {
                $("#subjects").append(
                    $('<option />')
                    .attr('value', result[i] )
                    .html( result[i] )
                    );
            };
            if (chosen_browser_is_supported()) {
                $("#subjects").chosen({width: "450px"});
            }else{
                $("#subjects").show();
            }
        }
    });
    $("#pleaseWait").hide();
    $("#addAllButton").parent().show();
}

function addCalendar () {
    var resource = {
        "summary": "Exams schedule"
    };
    var request = gapi.client.calendar.calendars.insert({
        'resource': resource
    });
    request.execute(function(resp) {
        console.log(resp);
    });
}

function addEvent (resource, calendarId) {
    var request = gapi.client.calendar.events.insert({
        'calendarId': calendarId,
        'resource': resource
    });
    request.execute(function(resp) {
        if(resp.status == "confirmed"){
            toastr.success('Success', resp.summary);
        }else{
            toastr.warning('Something may have gone wrong.');
        }
    });
}

function addAll () {
    if($("#calendars").val() == null){
        alert("Please select a writeable calendar.")
        return;
    }
    if($("#subjects").val() == null){
        alert("Please select your subjects.")
        return;
    }
    calendarId = $("#calendars").val();
    $.ajax({
        url: "api.py",
        data: {
            subjects: $("#subjects").val()
        },
        success: function(result){
            for (var i = 0; i < result.length; i++) {
                result[i].summary = $("#name_prefix").val() + result[i].summary;
                addEvent(result[i], calendarId);
            };
            logDone(calendarId);
        }
    });
}

function logDone(calId) {
                console.log("done");
}

chosen_browser_is_supported = function() {
    if (window.navigator.appName === "Microsoft Internet Explorer") {
        return document.documentMode >= 8;
    }
    if (/iP(od|hone)/i.test(window.navigator.userAgent)) {
        return false;
    }
    if (/Android/i.test(window.navigator.userAgent)) {
        if (/Mobile/i.test(window.navigator.userAgent)) {
            return false;
        }
    }
    return true;
};