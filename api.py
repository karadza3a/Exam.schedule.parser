import re, time, os, json
from datetime import datetime, date

def application(environ, start_response):

	exams = []
	# gets this file's location and appends 'files/sched.txt'
	schedule = os.path.join(os.path.dirname(__file__), 'files/sched.txt')

	with open (schedule, "r") as myfile:
		data=myfile.read().decode('utf-8')
		data=re.split('\n',data)

	regexes = [
		"Geom",
		"Intel",
		"Kodov",
		"Kripto",
	]

	combined = "(^" + ")|(^".join(regexes) + ")"

	for idx,row in enumerate(data):
		if re.match(combined, row):
			# correct indentation after "Ponedeljak"
			row = row.replace("Ponedeljak", "Ponedeljak  ")
			# split on 2 or more spaces
			exams.append( re.split(' {2,}', row))

	result = []

	for exam in exams:
		hour_start = exam[3].split("-")[0]
		hour_end = exam[3].split("-")[1]

		t = datetime.strptime(exam[5], "%d.%m.") 
		t = t.replace(year=date.today().year)
		
		dateTime = '{year}-{month}-{day}T'.format(year=t.year, month=t.month, day=t.day)

		result.append({
			"summary": exam[0],
			'location': exam[2] + ', School of Computing, Kneza Mihaila 6, Beograd 11000',
			'description': exam[1],
			'start': {
				'dateTime': dateTime+'{hour}:00:00'.format(hour=hour_start),
				'timeZone': 'Europe/Belgrade'
			},
			'end': {
				'dateTime': dateTime+'{hour}:00:00'.format(hour=hour_end),
				'timeZone': 'Europe/Belgrade'
			}
		}) 

	output = json.dumps(result).encode('utf-8')

	# print >> environ['wsgi.errors'], output

	status = "200 OK"
	
	response_headers = [('Content-type', 'application/json; charset=utf-8'),
						('Content-Length', str(len(output)))]
	start_response(status, response_headers)

	return [output]
