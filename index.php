<html>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script type="text/javascript">
			// Your Client ID can be retrieved from your project in the Google
			// Developer Console, https://console.developers.google.com
			var CLIENT_ID = '964072569290-gdpoo81np0q3rddbaujdh8jdd6vravu4.apps.googleusercontent.com';

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
					$("#calendars").chosen({width: "450px"});
					checkSchedulePdf();
				});
			}

			function checkSchedulePdf () {
				$.ajax({
				  url: "fetch-schedule.php",
				  success: function(result){
					toastr.info('Pdf last updated: '+result);
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
					$("#subjects").chosen({width: "450px"});
				  }
				});
				$("#pleaseWait").hide();
				$("#addAllButton").show();
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
				  		addEvent( result[i], calendarId);
				  	};
				  }
				});
			}

		</script>

		<script src="libs/chosen.jquery.min.js"></script>
		<link rel="stylesheet" href="libs/chosen.min.css" />
		
		<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

		<script src="https://apis.google.com/js/client.js?onload=checkAuth"></script>
		<script type="text/javascript">
			// $(document).ready(function () {
			// });
		</script>
	</head>
	<body>
		<div id="authorize-div" style="display: none">
			<span>Authorize access to Google Calendar API</span>
			<!--Button for the user to click to initiate auth sequence -->
			<button id="authorize-button" onclick="handleAuthClick(event)">
				Authorize
			</button>
		</div>
		<div id="content">
			<select id="calendars" class="chosen-select" style="display:none; width: 200px">
			</select><br>
			<select data-placeholder="Choose your subjects..." id="subjects" multiple class="chosen-select" style="display:none; width: 200px">
			</select><br>
			<span id="pleaseWait" style="display: none">Please wait...</span>
			<button id="addAllButton" onclick="addAll()" style="display: none">
				Add to calendar
			</button>
		</div>
	</body>
</html>