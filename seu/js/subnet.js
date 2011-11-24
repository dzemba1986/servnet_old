function przetwarzajListePodsieci()
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
				wyswietlListePodsieci(podsieci, 'subnets_list');
			}
		}
	}
}
function pobierzListePodsieci(vlan)
{
	//alert("wywoluje z adresem "+dev_id);
	if(XMLHttpRequestObject)
	{
		XMLHttpRequestObject.open("POST", 'ajax/getSubnets.php?vlan='+vlan);
		var nazwa = document.getElementById("nazwa_vlanu");
		nazwa.innerHTML = "Podsieci vlanu "+vlan+":";
		var ukryte_vid = document.getElementById("vlan_hidden_form");
		ukryte_vid.value = vlan;
		XMLHttpRequestObject.onreadystatechange = przetwarzajListePodsieci;
		XMLHttpRequestObject.send(null);
	}
}
function wyswietlListePodsieci(lista, root)
{
	var podsieci = lista.documentElement.childNodes;
	//alert(podsieci.length);
	var wezel_nadrz = document.getElementById("lewa");
	if(!wezel_nadrz)
	{
		alert("nie odnaleziono wezla nadrzednego");
		exit(0);	
	}
	else if(podsieci.length > 0)
	{	
		var stara_lista = document.getElementById(root);
		if (stara_lista)
		{
//			alert("byla stara");
			wezel_nadrz.removeChild(stara_lista);
		}
		var nowa_lista = document.createElement("ul");
		nowa_lista.id = root;
		for (var i=0; i<podsieci.length; i++)
		{
			var element = document.createElement("li");
			element.className = 'vlan_lewa';
			if(podsieci[i].childNodes[1].firstChild)
				element.title = podsieci[i].childNodes[1].firstChild.nodeValue;
			var subnet_id = podsieci[i].firstChild.firstChild.nodeValue;
			var subnet_addr = podsieci[i].childNodes[1].firstChild.nodeValue;
			var subnet_netmask = podsieci[i].childNodes[2].firstChild.nodeValue;
			var subnet_desc = podsieci[i].childNodes[4].firstChild.nodeValue;
			var opis = podsieci[i].childNodes[1].firstChild.nodeValue;
			opis += '/' + podsieci[i].childNodes[2].firstChild.nodeValue;
			opis += ' (' + podsieci[i].childNodes[4].firstChild.nodeValue + ')';
			var text = document.createTextNode(opis);
			element.appendChild(text);
                        var createClickHandler = function(s_id, s_addr, s_mask, s_desc){return function(){ {pobierzDhcpPodsiec(s_id, s_addr, s_mask, s_desc);};}                         }

			element.onclick = createClickHandler(subnet_id, subnet_addr, subnet_mask, subnet_desc);
	//		alert(vid);
	//		wezel_nadrz.appendChild(element);
			nowa_lista.appendChild(element);
		}
		
	//	alert(wezel_nadrz);
		wezel_nadrz.appendChild(nowa_lista);
	}
	else alert(podsieci.length);
}
function pobierzDhcpPodsiec(s_id)
{
	//alert("wywoluje z adresem "+dev_id);
	if(XMLHttpRequestObject)
	{
		XMLHttpRequestObject.open("POST", 'ajax/getSubnets.php?s_id='+s_id);
		var nazwa = document.getElementById("nazwa_vlanu");
		nazwa.innerHTML = "Podsieci vlanu "+vlan+":";
		var ukryte_vid = document.getElementById("vlan_hidden_form");
		ukryte_vid.value = vlan;
		XMLHttpRequestObject.onreadystatechange = przetwarzajListePodsieci;
		XMLHttpRequestObject.send(null);
	}
}
var XMLHttpRequestObject = getXMLHttpRequestObject();
