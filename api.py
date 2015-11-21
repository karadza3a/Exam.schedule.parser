import sys, re, time, io, os
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

	for exam in exams:
		exam[5] = time.strptime(exam[5], "%d.%m.") 

	exams.sort(key=lambda x: x[5])

	output = ""

	for exam in exams:
		output += ((exam[4] + " ").ljust(12) + \
		(exam[3] + " ").ljust(7) + \
		exam[0] + '\n')

	output = output.encode('utf-8')

	# print >> environ['wsgi.errors'], output

	status = "200 OK"
	
	response_headers = [('Content-type', 'text/plain; charset=utf-8'),
						('Content-Length', str(len(output)))]
	start_response(status, response_headers)

	return [output]
