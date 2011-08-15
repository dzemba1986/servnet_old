function getXMLHttpRequestObject()
{
	try{
		return new XMLHttpRequest();
	}
	catch(e)
	{	
		try
		{
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch(e)
		{
			return false;
		}
	}
}
function przetwarzajVlany()
{
	if(XMLHttpRequestObject)
	{
		if(XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200)
		{
			var lista = XMLHttpRequestObject.responseXML;
			if(!lista || !lista.documentElement)
			{
				alert("nic nie doszlo"+XMLHttpRequestObject.responseText);
			}
			else if(lista.documentElement.nodeName == "parsererror")
			{
				alert("blad parsera"+XMLHttpRequestObject.responseText);
			}
			else
			{	
		//		alert("wywoluje");
				wyswietlVlany(lista);
			}
		}
	}
}
function pobierzVlany()
{
	//alert("wywoluje z adresem ");
	if(XMLHttpRequestObject)
	{
		XMLHttpRequestObject.open("POST", 'ajax/getVlans.php');
		XMLHttpRequestObject.onreadystatechange = przetwarzajVlany;
		XMLHttpRequestObject.send(null);
	}
}
function wynikDodawaniaVlanu()
{
	if(XMLHttpRequestObject)
	{
		if(XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200)
		{
			var wynik = XMLHttpRequestObject.responseText;
			alert(wynik);
			pobierzVlany();
		}
	}
}
function wyswietlVlany(lista)
{
	var vlany = lista.documentElement.childNodes;
	//alert(vlany.length);
	var wezel_nadrz = document.getElementById("lewa");
	if(!wezel_nadrz)
	{
		alert("nie odnaleziono wezla nadrzednego");
		exit(0);	
	}
	else if(vlany.length > 0)
	{	
		var stara_lista = document.getElementById("lista_vlanow");
		if (stara_lista)
		{
//			alert("byla stara");
			wezel_nadrz.removeChild(stara_lista);
		}
		var nowa_lista = document.createElement("ul");
		nowa_lista.id = "lista_vlanow";
		for (var i=0; i<vlany.length; i++)
		{
			var element = document.createElement("li");
			element.className = 'vlan_lewa';
			if(vlany[i].childNodes[1].firstChild)
				element.title = vlany[i].childNodes[1].firstChild.nodeValue;
			var vid = vlany[i].firstChild.firstChild.nodeValue;
			var text = document.createTextNode(vid);
			element.appendChild(text);
			element.onclick = function() {pobierzPodsieci(this.firstChild.nodeValue)}
	//		alert(vid);
	//		wezel_nadrz.appendChild(element);
			nowa_lista.appendChild(element);
		}
		
	//	alert(wezel_nadrz);
		wezel_nadrz.appendChild(nowa_lista);
	}
	else alert(vlany.length);
}
function dodajVlan()
{
	//alert("wywoluje z adresem ");
	if(XMLHttpRequestObject)
	{
		var vid = document.getElementById("vlan_form").value;
		var opis = document.getElementById("opis_vlanu_form").value;
		XMLHttpRequestObject.open("POST", 'ajax/addVlan.php?vid='+vid+'&opis='+opis);
		XMLHttpRequestObject.onreadystatechange = wynikDodawaniaVlanu;
		XMLHttpRequestObject.send(null);
	}
}
function usunVlan(vid)
{
	//alert("wywoluje z adresem ");
	if(XMLHttpRequestObject)
	{
		XMLHttpRequestObject.open("POST", 'ajax/removeVlan.php?vid='+vid);
		XMLHttpRequestObject.onreadystatechange = wynikUsuwaniaVlanu;
		XMLHttpRequestObject.send(null);
	}
}
function wynikUsuwaniaVlanu()
{
	if(XMLHttpRequestObject)
	{
		if(XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200)
		{
			var wynik = XMLHttpRequestObject.responseText;
			alert(wynik);
			pobierzVlany();
		}
	}
}

function przetwarzajPodsieci()
{
	if(XMLHttpRequestObject)
	{
		if(XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200)
		{
			var podsieci = XMLHttpRequestObject.responseXML;
			if(!podsieci || !podsieci.documentElement)
			{
				alert("nic nie doszlo"+XMLHttpRequestObject.responseText);
			}
			else if(podsieci.documentElement.nodeName == "parsererror")
			{
		//		alert("blad parsera"+XMLHttpRequestObject.responseText);
			}
			else
			{	
		//		alert("wywoluje");
				wyswietlPodsieci(podsieci);
			}
		}
	}
}
function pobierzPodsieci(vlan)
{
	//alert("wywoluje z adresem "+dev_id);
	if(XMLHttpRequestObject)
	{
		XMLHttpRequestObject.open("POST", 'ajax/getSubnets.php?vlan='+vlan);
		document.getElementById("usun_vlan").innerHTML = "<button class=\"submit\" style=\"width:100px;margin-top:10px\" onclick=\"usunVlan("+vlan+");\">Usuń Vlan</button>";
		var nazwa = document.getElementById("nazwa_vlanu");
		nazwa.innerHTML = "Podsieci vlanu "+vlan+":";
		var ukryte_vid = document.getElementById("vlan_hidden_form");
		ukryte_vid.value = vlan;
		XMLHttpRequestObject.onreadystatechange = przetwarzajPodsieci;
		XMLHttpRequestObject.send(null);
	}
}
function wyswietlPodsieci(podsieci)
{
	//alert(podsieci);
	var podsieci = podsieci.documentElement.childNodes;
	var wezel_nadrz = document.getElementById("podsieci");
	//alert(dev_id);
		var tabela = "<table class=\"opis\"><tbody><tr class=\"opis_naglowek\"><td class=\"opis_adres\">Adres</td><td class=\"opis_maska\">Maska</td><td class=\"opis_opis\">Opis</td><td class=\"opis_opis\">DHCP</td><td class=\"opis_przycisk\" bgcolor=\"black\">&nbsp</td><td class=\"opis_przycisk\" bgcolor=\"black\">&nbsp</td>";
		for (var i=0; i<podsieci.length; i++)
		{
			tabela+="<tr><td>";
			tabela+=podsieci[i].childNodes[1].firstChild.nodeValue;
			tabela+="</td><td>";
			tabela+=podsieci[i].childNodes[2].firstChild.nodeValue;
			tabela+="</td><td>";
			if(podsieci[i].childNodes[4].firstChild)
				tabela+=podsieci[i].childNodes[4].firstChild.nodeValue;
			tabela+="</td><td>";
                        tabela+="<div><input type=\"checkbox\" ";
			if(podsieci[i].childNodes[5].firstChild)
				if(podsieci[i].childNodes[5].firstChild.nodeValue==1)
                                  tabela+="checked ";
                        tabela+=" onclick=\"changeDhcp(this, " + podsieci[i].childNodes[0].firstChild.nodeValue + ")\">"
			tabela+="</td><td> <a link=\"";
			tabela+=podsieci[i].childNodes[0].firstChild.nodeValue;
			tabela+="\">Urządzenia</a></td><td><input type=\"button\" class=\"submit\" onclick=\"usunPodsiec(";
			tabela+=podsieci[i].childNodes[0].firstChild.nodeValue;
			tabela+=");\" value=\"Usuń\"></td></tr>";
			//alert("bu");
			//var wiersz = tabela.insertRow();
			//var nazwa = wiersz.insertCell(0);
			//nazwa.innerHTML="nazwa";
			//var wartosc = document.createElement("td");
			//nazwa.innerHtml = "nazwa";//podsieci[i].firstChild.firstChild.nodeName;
			//wartosc.innerHtml = podsieci[i].firstChild.firstChild.nodeValue;
			//wiersz.appendChild(nazwa);
			//wiersz.appendChild(wartosc);
			//tabela.appendChild(wiersz);
		}
		tabela+="</tbody></table>";
		//alert(tabela);
		wezel_nadrz.innerHTML = tabela;

}
function dodajPodsiec()
{
	var ip = document.getElementById('podsiec_form').value;
	var maska = document.getElementById('maska_form').value;
	var vlan = document.getElementById('vlan_hidden_form').value;
	var opis = document.getElementById('opis_form').value;
        var dhcp = document.getElementById('dhcp_form').checked;
	if(XMLHttpRequestObject)
	{
		XMLHttpRequestObject.open("POST", 'ajax/addSubnet.php?nowa_podsiec='+ip+'&nowa_maska='+maska+'&vlan='+vlan + '&nowy_opis=' + opis + '&nowa_dhcp=' + dhcp);
		XMLHttpRequestObject.onreadystatechange = wynikDodawaniaPodsieci;
		XMLHttpRequestObject.send(null);
	}
}
function wynikDodawaniaPodsieci()
{
	if(XMLHttpRequestObject)
	{
		if(XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200)
		{
			var wynik = XMLHttpRequestObject.responseText;
			alert(wynik);
			pobierzPodsieci(document.getElementById("vlan_hidden_form").value);
		}
	}
}
function usunPodsiec(id)
{
	//alert("wywoluje z adresem ");
	if(XMLHttpRequestObject)
	{
		XMLHttpRequestObject.open("POST", 'ajax/removeSubnet.php?id='+id);
		XMLHttpRequestObject.onreadystatechange = wynikUsuwaniaPodsieci;
		XMLHttpRequestObject.send(null);
	}
}
function wynikUsuwaniaPodsieci()
{
	if(XMLHttpRequestObject)
	{
		if(XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200)
		{
			var wynik = XMLHttpRequestObject.responseText;
			alert(wynik);
			pobierzPodsieci(document.getElementById("vlan_hidden_form").value);
		}
	}
}
var XMLHttpRequestObject = getXMLHttpRequestObject();
function changeDhcp(obj, id)
{
  var parameters = new Object();
  parameters['id'] = id;
  parameters['dhcp'] = obj.checked;
//  alert(id + '-' +obj.checked);
  var response = getAjaxData(parameters, 'ajax/changeDhcp', 'POST');
  alert(response);
}

