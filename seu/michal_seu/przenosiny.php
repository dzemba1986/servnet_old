<!--Wszystkie najdziwniejsze przypadki adresów występują na OZ8 w mieszkaniach między 50 a 120
-->

<a href="index_przenosiny.php"><button>Powrót</button></a>
 
 <?php
 header('Content-Type: text/html; charset=utf-8');
   try
   {
      $pdo = new PDO('mysql:host=localhost;dbname=internet;encoding=utf8_polish_ci', 'susek','wach0wiak1985');
      //echo 'Połączenie nawiązane!';
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      
      $pdo->query('set names utf8');    
      
      $stmt = $pdo->prepare('select c.address, ifnull(date_format(c.resignation_date,\'%Y-%m-%d\'),\'empty\') as resignation,
						         c.ara_id, h.pakiet as speed, c.port, a.parent_port as port_seu, trim(leading \'g\' from a.parent_port) as port_seu,
							      d.mac, i.ip, p.vlan,
							      (select count(*) from internet.Connections where address=c.address and service=:usluga) as umowa_z_rzedu
									from internet.Lokalizacja l join internet.Teryt t on t.ULIC=l.ulic
									right join internet.Connections c on c.localization=l.id
									left join siec.Host h on h.con_id=c.id
									left join siec.Device d on d.dev_id=h.device
									left join siec.Agregacja a on a.device=h.device
									left join siec.Adres_ip i on i.device=h.device
									left join siec.Podsiec p on p.id=i.podsiec
									where l.ulic=(select ULIC from internet.Teryt where short_name=:osiedle)
									and l.blok=:numer_bloku
									and l.mieszkanie>=:pierwsze_mieszkanie and l.mieszkanie<=:ostatnie_mieszkanie
									and c.service=:usluga
									order by cast(l.mieszkanie as UNSIGNED), c.resignation_date asc;');
									
		$stmt->bindValue(':osiedle',$_POST['osiedle'],PDO::PARAM_STR);
		$stmt->bindValue(':usluga',$_POST['usl'],PDO::PARAM_STR);
		$stmt->bindValue(':numer_bloku',$_POST['blok'],PDO::PARAM_STR);
		$stmt->bindValue(':pierwsze_mieszkanie',(int)$_POST['mieszkanie_start'],PDO::PARAM_INT);
		$stmt->bindValue(':ostatnie_mieszkanie',(int)$_POST['mieszkanie_end'],PDO::PARAM_INT);
		$stmt->execute();
      
      $port_number=(int)$_POST['startowy_port'];
      $konfiguracja=null;
      $konf_drop=null;
      
       echo '<table border="1">';
       echo '<th>Port</th><th>Adres</th><th>Rezygnacja</th><th>ara_id</th><th>Prędkość</th><th>Port-lista</th><th>Port-SEU</th><th>Komentarz</th>';
      
        while($row = $stmt->fetch()) {
        	
        	$komentarz='';
        	
        	
        	

//budowanie add        	
        	if($row['resignation'] === 'empty'){
        		//znany ip i mac
        		if(($row['ip']!=null) && ($row['mac']!=null)) {
	        		$konfiguracja .=  '! Dodanie abonenta <b>'.$row['address'].'</b><br><br>
	        							interface vlan '.$row['vlan'].'<br>
										bridge address '.$row['mac'].' permanent ethernet g'.$port_number.'<br>
										exit<br>
										ip access-list user'.$port_number.'<br>
										deny-udp any any any 68<br>
										deny-tcp any any any 25<br>
										permit any '.$row['ip'].' 0.0.0.0 any<br>
										permit-udp 0.0.0.0 0.0.0.0 68 any 67<br>
										exit<br>
										interface ethernet g'.$port_number.'<br>
										shutdown<br>
										switchport access vlan '.$row['vlan'].'<br>
										description '.$row['address'].'<br>
										service-acl input user'.$port_number.'<br>';
										
				   if($row['speed']==300){
				   	$konfiguracja .= 'traffic-shape 307200 3072000<br>
										      rate-limit 600000<br>';
				   }
				   else{
				   	$konfiguracja .= 'traffic-shape 30720 307200<br>
	                                 rate-limit 81500<br>';
				   }
									
										
					$konfiguracja .=  'port security mode lock<br>
										port security discard<br>
										no shutdown<br>
										exit<br><br>';
				}
				// przypadek bez umowy (wariant z podłączaniem kabli bez umowy)
				// przypadek, gdzy umowa została podpisana, a kabel jeszcze nie jest dociągnięty
				else{
					$konfiguracja .= '! Danie abonenta bez umowy <b>'.$row['address'].'</b><br><br>
					                  interface ethernet g'.$port_number.'<br>
										   shutdown<br>
										   switchport access vlan 555<br>
										   description '.$row['address'].'<br>
										   no shutdown<br>
										   exit<br><br>';
				}
        	}
        	elseif($row['port']==0 && $row['port_seu']==null && $row['resignation']!='empty'){
					$konfiguracja .= '<h2><font color="green">! '.$row['address'].' rozwiązał umowę przed doprowadzeniem kabla.</font></h2><br><br>';
					$komentarz = 'zrezygnował przed doprowadzeniem kabla';
			}
        	else{
        		$konfiguracja .= '! Dodanie opisu abonenta <b>'.$row['address'].'</b><br><br>
        		               interface ethernet g'.$port_number.'<br>
									shutdown<br>
									switchport access vlan 555<br>
									description '.$row['address'].'<br>
									no shutdown<br>
									exit<br><br>';
        	}
        	//$port_number++;
        	
        	
        	
//budowanie dropa
         
         $flaga_bledu=false;
         //standardowy przypadek, gdy abonent miał obecnie aktywną umowę
        	if($row['port'] === $row['port_seu']){
	        	$konf_drop .= '! Usunięcie konfiguracji abonenta <b>'.$row['address'].'</b><br><br>
	        	               interface vlan '.$row['vlan'].'<br>
									no bridge address '.$row['mac'].'<br>
									exit<br>
									interface ethernet g'.$row['port'].'<br>
									shutdown<br>
									no description<br>
									no service-acl input<br>
									no traffic-shape<br>
									no rate-limit<br>
									no port security<br>
									sw a v 555<br>
									no shutdown<br>
									exit<br>
									no ip access-list user'.$row['port'].'<br>
									no ip access-list user'.$row['port'].'<br><br>';
        	}
        	else{
        		$flaga_bledu = true;
        		//przypadek, gdy jest znany mac i port z seu - czyli umowa aktualna, ale isnieje niespójność między SEU a listą
        		//prawdopodobnie dobra konfiguracja
        		if(($row['mac'] != null) && ($row['port_seu'] != null)) {
	        		$konf_drop .= '<font color="blue">! Usunięcie konfiguracji abonenta <b>'.$row['address'].'</b><br>
	        							! Lista pokazuje port: '.$row['port'].'<br>
	        							! SEU pokazuje port: '.$row['port_seu'].'<br><br>
		        	               interface vlan '.$row['vlan'].'<br>
										no bridge address '.$row['mac'].'<br>
										exit<br>
										interface ethernet g'.$row['port_seu'].'<br>
										shutdown<br>
										no description<br>
										no service-acl input<br>
										no traffic-shape<br>
										no rate-limit<br>
										no port security<br>
										sw a v 555<br>
										no shutdown<br>
										exit<br>
										no ip access-list user'.$row['port_seu'].'<br>
										no ip access-list user'.$row['port_seu'].'</font><br><br>';
				}
				//przypadek bez umowy
				elseif(preg_match('/^a[0-9]*$/D',$row['ara_id'])) {
					$konf_drop .= '<h2><font color="blue"> ! '.$row['address'].' BEZ UMOWY !!!!!!!!</font></h2><br><br>';
					$komentarz = 'adres bez umowy, ale podłączyć pod wskazany port';
				}
				//przypadek z podpisaną umową, ale kabel jeszcze nie był doprowadzony
				//podczas przenosin muszą go dporowadzić i podpiąć pod wskazany port
				elseif(!preg_match('/^a[0-9]*$/D',$row['ara_id']) && $row['port']==0 && $row['resignation']=='empty') {
					$konf_drop .= '<h2><font color="green">! dporowadzić kabel do'.$row['address'].'</font></h2><br><br>';
					$komentarz = 'dociągnąć kabel pod mieszkanie i podpiąć pod wskazany port';
				}
				//przypadek, gdy była podpisana umowa, ale abonent zrezygnował przed doprowadzeniem kabla
				elseif($row['port']==0 && $row['port_seu']==null && $row['resignation']!='empty'){
					$konf_drop .= '<h2><font color="green">! '.$row['address'].' rozwiązał umowę przed doprowadzeniem kabla.</font></h2><br><br>';
					$komentarz = 'zrezygnował przed doprowadzeniem kabla';
				}
				//przypadek, gdy obecnie aktywna jest rezygnacja, ale kabel był już dociąnięty
				elseif($row['port']!=0 && $row['port_seu']==null && $row['resignation']!='empty'){
					$konf_drop .= '<h2><font color="red">! '.$row['address'].' rozwiązał umowę tradycyjnie</h2><br><br>
					                                       interface ethernet g'.$row['port'].'<br>
																		shutdown<br>
																		no description<br>
																		no port security<br>
																		sw a v 555<br>
																		no shutdown<br>
																		exit<br></font><br><br>';
					$komentarz='aktywna rezygnacja';
				}
				else {
					$konf_drop .= '<h2><font color="red">! Dla '.$row['address'].' nie ma co usuwać!!!</font></h2><br><br>';
				}
        	}
        	
        	if($komentarz != 'zrezygnował przed doprowadzeniem kabla'){
        	echo '<tr><td>g'.$port_number.'</td><td>&nbsp&nbsp&nbsp'.$row['address'].'</td><td>'.$row['resignation'].'</td><td>&nbsp'.$row['ara_id'].'&nbsp</td><td>'.$row['speed'].'</td>
        	          <td>'.$row['port'].'</td><td>'.$row['port_seu'].'</td><td>&nbsp'.$komentarz.'&nbsp</td></tr>';
        	$port_number++;
        	}
        	
        	$i=1;
        	for($i;$i<$row['umowa_z_rzedu'];$i++) {
        		$row = $stmt->fetch();
        	}
       }
       echo '</table><br><br>';
       $stmt->closeCursor();
       
       echo '<h2>ADD</h2>';
       echo $konfiguracja,'<br><br>';
       
       echo '<h2>DROP</h2>';
       echo $konf_drop;
      
   }
   catch(PDOException $e)
   {
      echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
   }
?>
