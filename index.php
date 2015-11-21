<html>
	<head>
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
				var request = gapi.client.calendar.calendarList.list({
				});

				request.execute(function(resp) {
					var calendars = resp.items;
					appendPre('Upcoming calendars:');

					if (calendars.length > 0) {
						for (i = 0; i < calendars.length; i++) {
							var calendar = calendars[i];
							console.log(calendar);
							appendPre(calendar.summary + ' (' + calendar.id + ')')
						}
					} else {
						appendPre('No calendars found.');
					}

				});
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
				  console.log(resp);
				});
			}

			function addAll () {
				var resource = {
							  "summary": "Thingy ",
							  "start": {
							    "dateTime": "2015-11-21T10:00:00.000-07:00"
							  },
							  "end": {
							    "dateTime": "2015-11-21T10:25:00.000-07:00"
							  }
							};
			}
			/**
			* Append a pre element to the body containing the given message
			* as its text node.
			*
			* @param {string} message Text to be placed in pre element.
			*/
			function appendPre(message) {
				var pre = document.getElementById('output');
				var textContent = document.createTextNode(message + '\n');
				pre.appendChild(textContent);
			}

		</script>
		<script src="https://apis.google.com/js/client.js?onload=checkAuth"></script>
	</head>
	<body>
		<div id="authorize-div" style="display: none">
			<span>Authorize access to Google Calendar API</span>
			<!--Button for the user to click to initiate auth sequence -->
			<button id="authorize-button" onclick="handleAuthClick(event)">
				Authorize
			</button>
		</div>
		<pre id="output"></pre>
	</body>
</html>