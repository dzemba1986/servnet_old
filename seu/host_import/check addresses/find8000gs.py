#!/usr/bin/python


a8000 = "8000GS"
x900 = "x900"
pingrange = "adresy.txt"

a8000_f = open(a8000, "rb")
a8000_l = a8000_f.readlines()

x900_f = open(x900, "rb")
x900_l = x900_f.readlines()

pingrange_f = open(pingrange, "rb")
pingrange_l = pingrange_f.readlines()

for x in range(len(pingrange_l)):
	occurs = False
	adres = " ".join(pingrange_l[x].split())
	for y in range(len(a8000_l)):
		adres_y = " ".join(a8000_l[y].split())
#		print "%20s%20s"% (adres, adres_y)
		if adres == adres_y:
			occurs = True
			break
	for z in range(len(x900_l)):
		adres_z = " ".join(x900_l[z].split())
		if adres == adres_z:
			occurs = True
			break
	if not occurs:
		print(adres)

