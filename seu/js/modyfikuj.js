var XMLHttpRequestObjectAdresy = getXMLHttpRequestObject();
var XMLHttpRequestObjectAdres = getXMLHttpRequestObject();


function pobierzAdresy(dev_id)
{

	getDeviceAddresses(dev_id);
/*	foreach(adres in adresy)
	{
		podsiec = getSubnet(adres[1]); //aadres[1] to id podsieci
		podsieci = getSubnets(podsiec[3]); //podsiec[3] to vlan
		var glowny_obszar = document.getElementById("vlans");
		var vlan_select = glowny_obszar.lastChild.childNodes[0];
		var podsiec_select = glowny_obszar.lastChild.childNodes[1];
		pobierzVlany();
		aktywujVlan(vlan_select, podsieci[3]);
		pobierzPodsieci(podsiec_select);
		aktywujPodsiec(podsiec_select, podsiec);
		nadajIp(adres[2]); //adres[2] to adres ip
	}
*/}
function getDeviceAddresses(dev_id)
{
	if(XMLHttpRequestObjectAdres)
	{
                XMLHttpRequestObjectAdres = getXMLHttpRequestObject();
		XMLHttpRequestObjectAdres.open("POST", 'ajax/getDeviceAddresses.php?dev_id=' + dev_id, false);
		XMLHttpRequestObjectAdres.send(null);
		przetwarzajAdresy();
	}
}
function przetwarzajAdresy()
{
	if(XMLHttpRequestObjectAdres)
	{
		if(XMLHttpRequestObjectAdres.readyState == 4 && XMLHttpRequestObjectAdres.status == 200)
		{
			var lista = XMLHttpRequestObjectAdres.responseXML;
			if(!lista || !lista.documentElement)
			{
				alert("nic nie doszlo"+XMLHttpRequestObjectAdres.responseText);
			}
			else if(lista.documentElement.nodeName == "parsererror")
			{
				alert("blad parsera"+XMLHttpRequestObjectAdres.responseText);
			}
			else
			{	
				wygenerujAdresy(lista);
			}
		}
	}
}
function wygenerujAdresy(lista)
{
	var adresy = lista.documentElement.childNodes;
	for(var i=0; i<adresy.length; i++)
	{
		pobierzVlany();
		var device = adresy[i].childNodes[0].firstChild.nodeValue;
		var ip = adresy[i].childNodes[2].firstChild.nodeValue;
		var podsiec = adresy[i].childNodes[1].firstChild.nodeValue;
		var vlan = getVlan(podsiec);
		var vlan_form_id = "_vlan"+(i+1);
		wezel_vlanu = document.getElementsByName(vlan_form_id)[0];
		var aktywny_vlan = aktywujVlan(wezel_vlanu, vlan);
		pobierzPodsieci(aktywny_vlan, podsiec, ip);
		aktywujIp(wezel_vlanu.nextSibling.nextSibling, ip);
	}
}
function getVlan(id)
{
	if(XMLHttpRequestObject)
	{
                XMLHttpRequestObject = getXMLHttpRequestObject();
		XMLHttpRequestObject.open("POST", 'ajax/getVlan.php?id='+id, false);
		XMLHttpRequestObject.send(null);
		if(XMLHttpRequestObject)
		{
			if(XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200)
			{
				var vlan = XMLHttpRequestObject.responseText;
				return vlan;
			}
		}
	}
	return false;
}
function aktywujVlan(wezel_vlanu, vlan)
{
	for(var j=0; j<wezel_vlanu.length; j++)
		if(wezel_vlanu.childNodes[j].value==vlan)
		{
			wezel_vlanu.childNodes[j].selected = true;
			return wezel_vlanu.childNodes[j];
		}
}
function aktywujIp(wezel_ip, ip)
{
	for(var j=0; j<wezel_ip.length; j++)
	{
		if(wezel_ip.childNodes[j].value==ip)
		{
			wezel_ip.childNodes[j].selected = true;
			return wezel_ip.childNodes[j];
		}
	}
}
function dodajPole()
{
	pobierzVlany();

	//obszar.innerHtml = obszar.innerHtml + "<input type=\"text\">";
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
	//			alert("wywoluje");
				wygenerujListe(lista);
			}
		}
	}
}
function pobierzVlany()
{
	//alert("wywoluje z adresem ");
	if(XMLHttpRequestObject)
	{
                XMLHttpRequestObject = getXMLHttpRequestObject();
		XMLHttpRequestObject.open("POST", 'ajax/getVlans.php', false);
		XMLHttpRequestObject.send(null);
		przetwarzajVlany();
	}
}
function wygenerujListe(lista)
{
	var vlany = lista.documentElement.childNodes;
//	alert(vlany.length);
	var glowny_obszar = document.getElementById("vlans");
	var obszar_pola = document.createElement("div");
	obszar_pola.className = 'ip_form';
	var id = glowny_obszar.childNodes.length;
	var lista = document.createElement("select");
	lista.onchange = function(){pobierzPodsieci(this.childNodes[this.selectedIndex])}
	var element = document.createElement("option");
	var tekst = document.createTextNode("");
	element.appendChild(tekst);
	lista.appendChild(element);
	lista.name = "_vlan" + id;
	for(var i=0; i<vlany.length; i++)
	{
		var element = document.createElement("option");
		var tekst = vlany[i].firstChild.firstChild.nodeValue;
		if (vlany[i].lastChild.firstChild)
			tekst = tekst + "(" + vlany[i].lastChild.firstChild.nodeValue + ")";
		var tekst = document.createTextNode(tekst);
		element.appendChild(tekst);
		element.value = vlany[i].firstChild.firstChild.nodeValue;
		lista.appendChild(element);
	}
	obszar_pola.appendChild(lista);

	var podsiec = document.createElement("select");
	podsiec.name = "_podsiec" + id;
	podsiec.onchange = function(){getIpAddressesForDevice(this.value, this.nextSibling.name)}
	obszar_pola.appendChild(podsiec);

	var pole = document.createElement("select");
	pole.name = "_ip"+id;
	pole.className = "ip_form";
	obszar_pola.appendChild(pole);

	if (id>1 || DEVICE_TYPE=="Virtual")
	{
		var usun = document.createElement("button");
		usun.appendChild(document.createTextNode("usuń"));
		usun.onclick = function()
		{
			glowny_obszar.removeChild(obszar_pola);
		}
		obszar_pola.appendChild(usun);
	}
	glowny_obszar.appendChild(obszar_pola);
}
function przetwarzajPodsieci(name, active, ip)
{
//	alert(name);
	if(XMLHttpRequestObject)
	{
//		alert("stan:"+XMLHttpRequestObject.readyState+"\nstatus: "+XMLHttpRequestObject.status);
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
				wygenerujListePodsieci(podsieci, name, active, ip);
			}
		}
	}
}
function pobierzPodsieci(vlan, active)
{
	if(XMLHttpRequestObject)
	{
                XMLHttpRequestObject = getXMLHttpRequestObject();
		XMLHttpRequestObject.open("POST", 'ajax/getSubnets.php?vlan='+vlan.value, false);
		XMLHttpRequestObject.send(null);
		przetwarzajPodsieci(vlan.parentNode.parentNode.childNodes[1].name, active, null);
	}
}
function pobierzPodsieci(vlan, active, ip)
{
	if(XMLHttpRequestObject)
	{
                XMLHttpRequestObject = getXMLHttpRequestObject();
		XMLHttpRequestObject.open("POST", 'ajax/getSubnets.php?vlan='+vlan.value, false);
		XMLHttpRequestObject.send(null);
		przetwarzajPodsieci(vlan.parentNode.parentNode.childNodes[1].name, active, ip);
	}
}
function wygenerujListePodsieci(podsieci, name, active, ip)
{
//	alert(podsieci);
	var podsieci = podsieci.documentElement.childNodes;
        var wezel_nadrz = document.getElementsByName(name)[0];
	if (wezel_nadrz.length>0)
	{
		removeChildren(wezel_nadrz);
		removeChildren(wezel_nadrz.nextSibling);
//		alert(wezel_nadrz);
	}
	for(var i=0; i<podsieci.length; i++)
	{
		var element = document.createElement("option");
                var osiedle;
                if(podsieci[i].childNodes[4].firstChild)
                  osiedle = " ("+podsieci[i].childNodes[4].firstChild.nodeValue+")";
		var tekst = document.createTextNode(podsieci[i].childNodes[1].firstChild.nodeValue+"/"+podsieci[i].childNodes[2].firstChild.nodeValue + osiedle);
		element.appendChild(tekst);
		element.value = podsieci[i].firstChild.firstChild.nodeValue;
		if(element.value==active)
			element.selected = true;
		wezel_nadrz.appendChild(element);
	}
	getIpAddressesForDevice(wezel_nadrz.value, wezel_nadrz.nextSibling.name, ip);			
	//alert(dev_id);
}
function przetwarzajDostepneAdresy(name)
{
	if(XMLHttpRequestObjectAdresy)
	{
//		alert("stan:"+XMLHttpRequestObject.readyState+"\nstatus: "+XMLHttpRequestObject.status);
		if(XMLHttpRequestObjectAdresy.readyState == 4 && XMLHttpRequestObjectAdresy.status == 200)
		{
			var adresy = XMLHttpRequestObjectAdresy.responseXML;
			if(!adresy || !adresy.documentElement)
			{
				alert("nic nie doszlo"+XMLHttpRequestObjectAdresy.responseText);
			}
			else if(adresy.documentElement.nodeName == "parsererror")
			{
		//		alert("blad parsera"+XMLHttpRequestObject.responseText);
			}
			else
			{	
	//			alert("wywoluje "+ name);
				wygenerujListeAdresow(adresy, name);
			}
		}
	}
}
function getIpAddressesForDevice(podsiec, ip_counter, ip)
{
	if(XMLHttpRequestObjectAdresy)
	{
                XMLHttpRequestObjectAdresy = getXMLHttpRequestObject();
		if(ip)
			XMLHttpRequestObjectAdresy.open("POST", 'ajax/getIpAddressesForDevice.php?subnet='+podsiec+'&dev_ip='+ip, false);
		else
			XMLHttpRequestObjectAdresy.open("POST", 'ajax/getIpAddressesForDevice.php?subnet='+podsiec, false);
		XMLHttpRequestObjectAdresy.send(null);
		przetwarzajDostepneAdresy(ip_counter);
	}
}
function removeChildren(wezel_nadrz)
{
	for(var j = wezel_nadrz.length-1; j>=0; j--)
		wezel_nadrz.removeChild(wezel_nadrz.lastChild); 
}
function wygenerujListeAdresow(adresy, name)
{
//	alert(name);
	var wezel_nadrz;
	wezel_nadrz = document.getElementsByName(name)[0];
	if (wezel_nadrz.length>0)
	{
		removeChildren(wezel_nadrz);
//		alert(wezel_nadrz);
	}
	var adresy = adresy.documentElement.childNodes;
	for(var i=0; i<adresy.length; i++)
	{
		var element = document.createElement("option");
		var tekst = document.createTextNode(adresy[i].firstChild.nodeValue);
		element.appendChild(tekst);
		element.value = adresy[i].firstChild.nodeValue;
		wezel_nadrz.appendChild(element);
	}
	//alert(dev_id);
}
