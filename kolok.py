import sys, re, time

with open (sys.argv[1], "r") as myfile:
    data=myfile.read().decode('utf-8')
    data=re.split('\n',data)

exams = []

for idx,row in enumerate(data):
	if len(row)>20:
		exams.append( re.split(' {2,}',row.replace("Ponedeljak", "Ponedeljak  ")))

for exam in exams:
	exam[5] = time.strptime(exam[5], "%d.%m.") 

exams.sort(key=lambda x: x[5])

for exam in exams:
	print (exam[4] + " ").ljust(12) + \
	(exam[3] + " ").ljust(7) + \
	exam[0]
