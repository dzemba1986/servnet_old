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
		XMLHttpRequestObject.open("POST", 'ajax/getVlans.php');
		XMLHttpRequestObject.onreadystatechange = przetwarzajVlany;
		XMLHttpRequestObject.send(null);
	}
}
function wygenerujListe(lista)
{
	var vlany = lista.documentElement.childNodes;
//	alert(vlany.length);
	var glowny_obszar = document.getElementById("vlans");
	var obszar_pola = document.createElement("div");
	var id = glowny_obszar.childNodes.length;
	var lista = document.createElement("select");
	lista.name = "_vlan" + id;
	lista.onchange =  function(){pobierzPodsieci(this.value, this.nextSibling.name)}
	var element = document.createElement("option");
	var tekst = document.createTextNode("");
	element.appendChild(tekst);
	lista.appendChild(element);
	for(var i=0; i<vlany.length; i++)
	{
		var element = document.createElement("option");
		var tekst = vlany[i].firstChild.firstChild.nodeValue;
		if (vlany[i].lastChild.firstChild)
			tekst = tekst + "(" + vlany[i].lastChild.firstChild.nodeValue + ")";
		var tekst = document.createTextNode(tekst);
		element.appendChild(tekst);
		element.value = vlany[i].firstChild.firstChild.nodeValue;
		if(IPLISTA && VLAN)
			if(VLAN==vlany[i].firstChild.firstChild.nodeValue)
				element.selected=true;
		lista.appendChild(element);
	}
	obszar_pola.appendChild(lista);

	var podsiec = document.createElement("select");
	podsiec.name = "_podsiec" + id;
        if(!IPLISTA)
	  podsiec.onchange = function(){getIpAddressesForDevice(this.value, this.nextSibling.name)}
	obszar_pola.appendChild(podsiec);

	var pole = document.createElement("select");
	pole.name = "_ip"+id;
	pole.className = "ip_form";
	if(!IPLISTA)
		obszar_pola.appendChild(pole);
	if (id>1)
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
	if(IPLISTA && VLAN)
	{
		pobierzPodsieci(VLAN, '_podsiec0');
	}
}
function przetwarzajPodsieci(name)
{
//	alert(name);
	if(XMLHttpRequestObject2)
	{
//		alert("stan:"+XMLHttpRequestObject.readyState+"\nstatus: "+XMLHttpRequestObject.status);
		if(XMLHttpRequestObject2.readyState == 4 && XMLHttpRequestObject2.status == 200)
		{
			var podsieci = XMLHttpRequestObject2.responseXML;
			if(!podsieci || !podsieci.documentElement)
			{
				alert("nic nie doszlo"+XMLHttpRequestObject2.responseText);
			}
			else if(podsieci.documentElement.nodeName == "parsererror")
			{
	//			alert("blad parsera"+XMLHttpRequestObject.responseText);
			}
			else
			{	
		//		alert("wywoluje");
				wygenerujListePodsieci(podsieci, name);
			}
		}
	}
}
function pobierzPodsieci(vlan, v_counter)
{
	if(XMLHttpRequestObject)
	{
		XMLHttpRequestObject2.open("POST", 'ajax/getSubnets.php?vlan='+vlan);
		XMLHttpRequestObject2.onreadystatechange = function(){przetwarzajPodsieci(v_counter)}
		XMLHttpRequestObject2.send(null);
	}
}
function wygenerujListePodsieci(podsieci, name)
{
	var podsieci = podsieci.documentElement.childNodes;
        var wezel_nadrz = document.getElementsByName(name)[0];
	if (wezel_nadrz.length>0)
	{
		removeChildren(wezel_nadrz);
                if(!IPLISTA)
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
		if(IPLISTA && PODSIEC)
			if(PODSIEC==podsieci[i].firstChild.firstChild.nodeValue)
				element.selected=true;
		wezel_nadrz.appendChild(element);
	}
        if(!IPLISTA)
	  getIpAddressesForDevice(wezel_nadrz.value, wezel_nadrz.nextSibling.name);			
	//alert(dev_id);
}
function przetwarzajAdresy(name)
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
function getIpAddressesForDevice(podsiec, ip_counter)
{
	if(XMLHttpRequestObjectAdresy)
	{
		XMLHttpRequestObjectAdresy.open("POST", 'ajax/getIpAddressesForDevice.php?subnet='+podsiec);
		XMLHttpRequestObjectAdresy.onreadystatechange = function(){ przetwarzajAdresy(ip_counter)}
		XMLHttpRequestObjectAdresy.send(null);
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
	//	if(IPLISTA && PODSIEC)
	//		if(PODSIEC==adresy[i].firstChild.nodeValue)
	//			element.selected=true;
		wezel_nadrz.appendChild(element);
	}
	//alert(dev_id);
}
var XMLHttpRequestObject2 = getXMLHttpRequestObject();
var XMLHttpRequestObjectAdresy = getXMLHttpRequestObject();

