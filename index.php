<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <!-- Meta data for SEO and social media websites -->
    <title>Exam shedule parser</title>
    <!-- Viewport settings -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <!--Libraries-->

    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300italic' rel='stylesheet' type='text/css'>
    <!--Main stylesheet -->
    <link href="libs/css/main.css" rel="stylesheet">
    <link href="libs/css/animate.css" rel="stylesheet">
    <link href="libs/chosen.bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
    <!-- Modals -->
    <link rel="icon" type="image/png" href="img/favico.png">

</head>
<body>
    <div class="container">
        <div id="content">
            <h2>Exam schedćčule parser</h2>
            <h3>Add exams as events to your Google calendar.</h3>
            <p>Active schedule: <a href="https://www.raf.edu.rs/Rasporedi/Ispitni%20rokovi/2015-2016/Junski_rok.pdf">June finals</a>. Code available on <a href="https://github.com/karadza3a/Exam.schedule.parser">GitHub</a>.</p>

            <div id="authorize-div" class="collapse">
                <h3>Authorize access to Google Calendar API</h3>
                <!--Button for the user to click to initiate auth sequence -->
                <button id="authorize-button" onclick="handleAuthClick(event)">
                    Authorize
                </button>
            </div>

            <select id="calendars" class="chosen-select collapse"></select>
            <div style="margin: 10px"></div>
            <select data-placeholder="Choose your subjects..." id="subjects" multiple class="chosen-select collapse"></select>
            <div style="margin: 10px"></div>
            <div class="row">
                <span id="pleaseWait" class="collapse">Please wait...</span>
            </div>
            <div class="collapse">
                <div class="side-by-side clearfix">
                    <div class="col-sm-2">
                        <label>Prefix events with:</label>
                    </div>
                    <div>
                        <input type="text" id="name_prefix" value="[Ispit] " class="col-sm-4" />
                    </div>
                </div>
                <div style="margin: 10px"></div>
                <button id="addAllButton" onclick="addAll()" class="col-sm-6">
                    Add to calendar
                </button>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="libs/chosen.jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        var config = {
            '.chosen-select'           : {},
            '.chosen-select-deselect'  : {allow_single_deselect:true},
            '.chosen-select-no-single' : {disable_search_threshold:10},
            '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
            '.chosen-select-width'     : {width:"95%"}
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="exams.js"></script>
    <script src="https://apis.google.com/js/client.js?onload=checkAuth"></script>
</body>
</html>