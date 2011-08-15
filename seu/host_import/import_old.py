#!/usr/bin/python
import datetime

import os
path="backup_11_05_07"  # insert the path to the directory of interest
dirList=os.listdir(path)

przelaczniki = {}
licznik_p = 0

for fname in dirList: 
	if fname[:7]!="backup_":
		continue
	switch = path+'/'+fname
	plik_s = open(switch, "rb")
	linie_s = plik_s.readlines()
	l_lini_s = len(linie_s)

	abonenci = {}
	przelacznik =""
	przelacznik_ip =""
	for x in range(l_lini_s):
		linie_s[x] = " ".join(linie_s[x].split())
		if linie_s[x][:8]=="hostname":
			przelacznik = linie_s[x].split()[1]
	#		print(przelacznik)
			break
	for x in range(l_lini_s):
		linie_s[x] = " ".join(linie_s[x].split())
		if linie_s[x][:11]=="description":
			if len(linie_s[x].split('/'))==2:		
				pelna_nazwa = linie_s[x].split()
				if pelna_nazwa[1][:2]=='sd' or pelna_nazwa[1][:2]=='hd':
					continue
				port = linie_s[x-1][19:]
				if port not in  abonenci:
					abonenci[port] = {}
				adres = pelna_nazwa[1].split("/")
				if len(adres) < 2:
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
			elif linie_s[x].split()[1][:2]=='vo':
				port = linie_s[x-1][19:]
				if port not in  abonenci:
					abonenci[port] = {}
				abonenci[port]['opis']="VoIP"
				abonenci[port]['mieszkanie'] = linie_s[x].split()[1] 
		elif linie_s[x][:14]=="bridge address":
			port = linie_s[x][42:]
			if port not in  abonenci:
				abonenci[port] = {}
			y = x
			vlan = False
			while y>0:
				y=y-1
				if linie_s[y][:14]=="interface vlan":
					vlan = linie_s[y].split()[2]
					break
			if vlan == '3':
				abonenci[port]['opis']="VoIP"
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
			if linie_s[x].split()[2]=="409600":
				abonenci[port]['pakiet'] = "4/2"
			elif linie_s[x].split()[2]=="307200":
				abonenci[port]['pakiet'] = "30/15"
			elif linie_s[x].split()[2]=="512000":
				abonenci[port]['pakiet'] = "50/25"
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
		elif linie_s[x][-20:]=="213.5.208.0 0.0.0.63":
			ip = linie_s[x][11:-29]
			y = x
			while y>0:
				y=y-1
				if linie_s[y][:19]=="ip access-list voip":
					port = linie_s[y][19:]
					port = ('g'+port)
					if port not in  abonenci:
						abonenci[port] = {}
					abonenci[port]['ip'] = ip
					break
	if przelacznik == 'CENTRALNY':
		continue
	przelaczniki[licznik_p] = {}




	przelaczniki[licznik_p]['abonenci'] = abonenci
	przelaczniki[licznik_p]['ip_przelacznika']= przelacznik_ip
	przelaczniki[licznik_p]['hostname']= przelacznik
	licznik_p = licznik_p+1

	# tutaj trzeba bedzie poszukac dat i telefonow

import MySQLdb
#conn2 = MySQLdb.connect("localhost", "internet", "szczurek", "internet")
#c2 = conn2.cursor()
#c2.execute("SELECT * FROM Connections WHERE service_activation is not null AND service='net'")
#wiersz = c2.fetchone()
#while wiersz:
#	for key in przelaczniki:
#		abonenci = przelaczniki[key]['abonenci']
#		for port in abonenci:
#			if 'opis' in abonenci[port] and abonenci[port]['opis']=="VoIP":
#				continue
#			if not 'osiedle' in abonenci[port] or not 'blok' in abonenci[port] or not 'mieszkanie' in abonenci[port]:
#				continue
#			adres1 = abonenci[port]['osiedle']+abonenci[port]['blok']+'/'+abonenci[port]['mieszkanie']
#			if adres1==wiersz[2]:
#				if wiersz[9]:
#					abonenci[port]['net_date'] = wiersz[9].isoformat()
#				if wiersz[11]:
#					abonenci[port]['resignation_date'] = wiersz[11].isoformat()
#				telefony = wiersz[12].split('<br>')
#				abonenci[port]['tel1'] = telefony[0]
#				if len(telefony)==2:
#					abonenci[port]['tel2'] = telefony[1]
#
#	wiersz = c2.fetchone()

#dodawanie do bazy
conn = MySQLdb.connect("localhost", "host_import", "tralalala", "siec")
c = conn.cursor()
for key in przelaczniki:
	print ""
	print(przelaczniki[key]['ip_przelacznika'])
	abonenci = przelaczniki[key]['abonenci']
	for port in abonenci:
		if not 'ip' in abonenci[port]:
			abonenci[port]['ip']=""
		if not 'pakiet' in abonenci[port]:
			abonenci[port]['pakiet']=""
		if not 'blok' in abonenci[port]:
			abonenci[port]['blok']=""
		if not 'mac' in abonenci[port]:
			abonenci[port]['mac']=""
		if not 'osiedle' in abonenci[port]:
			abonenci[port]['osiedle']=""
		if not 'opis' in abonenci[port]:
			abonenci[port]['opis']=""
		if not 'mieszkanie' in abonenci[port]:
			abonenci[port]['mieszkanie']=""
		if not 'tel1' in abonenci[port]:
			abonenci[port]['tel1']=""
		if not 'tel2' in abonenci[port]:
			abonenci[port]['tel2']=""
		if not 'net_date' in abonenci[port]:
			abonenci[port]['net_date']=""
		if not 'resignation_date' in abonenci[port]:
			abonenci[port]['resignation_date']=""
		print "%4s:: %4s %10s %10s %5s %17s%20s%10s%10s"% (port, abonenci[port]['osiedle'], abonenci[port]['blok'], abonenci[port]['mieszkanie'], abonenci[port]['pakiet'], abonenci[port]['ip'], abonenci[port]['mac'], abonenci[port]['opis'], przelaczniki[key]['hostname'])
		zapytanie = "INSERT INTO Host_import SET switch_hostname='"+przelaczniki[key]['hostname']+"',"
		zapytanie = zapytanie+" switch_ip='"+przelaczniki[key]['ip_przelacznika']+"', port='"+port+"'"
		zapytanie = zapytanie+", osiedle='"+abonenci[port]['osiedle']+"', blok='"+abonenci[port]['blok']+"'"
		zapytanie = zapytanie+", mieszkanie='"+abonenci[port]['mieszkanie']+"', ip_hosta='"+abonenci[port]['ip']+"'"
		zapytanie = zapytanie+", pakiet='"+abonenci[port]['pakiet']+"', mac='"+abonenci[port]['mac']+"'"
		zapytanie = zapytanie+", opis='"+abonenci[port]['opis']+"', tel1='"+abonenci[port]['tel1']+"'"
		zapytanie = zapytanie+", tel2='"+abonenci[port]['tel2']+"', net_date='"+abonenci[port]['net_date']+"'"
		zapytanie = zapytanie+", resignation_date='"+abonenci[port]['resignation_date']+"'"
		c.execute(zapytanie)
		conn.commit()
#szukaj odpowiedniego tekstu

