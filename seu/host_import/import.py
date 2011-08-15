#!/usr/bin/python


import os
path="."  # insert the path to the directory of interest
dirList=os.listdir(path)

przelaczniki = {}
licznik_p = 0

for fname in dirList: 
	if fname[:7]!="backup_":
		continue
	switch = fname
	plik_s = open(switch, "rb")
	linie_s = plik_s.readlines()
	l_lini_s = len(linie_s)

	abonenci = {}
	przelacznik =""
	przelacznik_ip =""
#	for x in range(l_lini_s):
#		if linie_s[x][:8]=="hostname":
#			przelacznik = linie_s[x][12:][:-2]
	#		print(przelacznik)
#			break
	for x in range(l_lini_s):
		linie_s[x] = " ".join(linie_s[x].split())
		if linie_s[x][:11]=="description" and len(linie_s[x].split('/'))==2:
			port = linie_s[x-1][19:]
			if port not in  abonenci:
				abonenci[port] = {}
			pelna_nazwa = linie_s[x].split()
			adres = pelna_nazwa[1].split("/")
			if len(adres)<2:
				continue
			abonenci[port]['mieszkanie'] = adres[1]
			osiedle_end=0
			for z in range(len(adres[0])):
				if adres[0][z].isdigit():
					osiedle_end = z
					break;
			if osiedle_end < 0:
				print"Bledna nazwa hosta!"
				quit()
			abonenci[port]['osiedle'] = adres[0][:osiedle_end]
			abonenci[port]['blok'] = adres[0][osiedle_end:]
			#print "%5s%5s" % (osiedle_blok, mieszkanie)
			#print (abonenci)
		elif linie_s[x][:14]=="bridge address":
			port = linie_s[x][42:]
			if port not in  abonenci:
				abonenci[port] = {}
			abonenci[port]['mac'] = linie_s[x][15:32]
		elif linie_s[x][:17]=="ip address 172.20":
			przelacznik_ip = linie_s[x].split()[2]
			if len(przelacznik_ip.split('/')) > 1:
				przelacznik_ip = ''
		elif linie_s[x][:13]=="traffic-shape":
			y = x
			while y>0:
				y=y-1
				if linie_s[y].split()[0]=="interface":
					port = linie_s[y].split()[2]
					break
			if port not in  abonenci:
				abonenci[port] = {}
			if linie_s[x].split()[2]=="204800":
				abonenci[port]['pakiet'] = "2/1"
			elif linie_s[x].split()[2]=="409600":
				abonenci[port]['pakiet'] = "4/2"
			elif linie_s[x].split()[2]=="614400":
				abonenci[port]['pakiet'] = "6/3"
			elif linie_s[x].split()[2]=="819200":
				abonenci[port]['pakiet'] = "8/4"
			elif linie_s[x].split()[2]=="1024000":
				abonenci[port]['pakiet'] = "10/5"
		elif linie_s[x][:16]=="permit any 213.5" or linie_s[x][:17]=="permit any 46.175":
			ip = linie_s[x][11:-12]
			y = x
			while y>0:
				y=y-1
				if linie_s[y][:19]=="ip access-list user":
					port = linie_s[y][19:]
					port = ('g'+port)
					if port not in  abonenci:
						abonenci[port] = {}
					abonenci[port]['ip'] = ip
					break
	przelaczniki[licznik_p] = {}
	przelaczniki[licznik_p]['abonenci'] = abonenci
	przelaczniki[licznik_p]['ip_przelacznika']= przelacznik_ip
	licznik_p = licznik_p+1
print(przelaczniki)



#for key in przelaczniki:
#	if len(przelaczniki[key]['abonenci']) < 1:
#		continue
#	nazwa_pliku = przelaczniki[key]['ip_przelacznika']+".txt"
#	abonenci = przelaczniki[key]['abonenci']
#	tresc = ""
#	for port in abonenci:
#		if 'ip' and 'pakiet' in abonenci[port]:
#			if abonenci[port]['pakiet']=="10/5":
#				tresc = tresc + "interface ethernet "+port+"\n"
#				tresc = tresc + "traffic-shape 10240 1024000\n"
#				tresc = tresc + "rate-limit 7987\n"
#				tresc = tresc + "exit\n"
#			elif abonenci[port]['pakiet']=="6/3":
#				tresc = tresc + "interface ethernet "+port+"\n"
#				tresc = tresc + "traffic-shape 6144 614400\n"
#				tresc = tresc + "rate-limit 6792\n"
#				tresc = tresc + "exit\n"
#			elif abonenci[port]['pakiet']=="2/1":
#				tresc = tresc + "interface ethernet "+port+"\n"
#				tresc = tresc + "traffic-shape 2048 204800\n"
#				tresc = tresc + "rate-limit 5200\n"
#				tresc = tresc + "exit\n"
#	if(len(tresc)>0):
#		plik = open(nazwa_pliku, "wb")
#		print(tresc)
#		plik.write(tresc)
#		plik.flush()
#		plik.close()
	
#szukaj odpowiedniego tekstu
