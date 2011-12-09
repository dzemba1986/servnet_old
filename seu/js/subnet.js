function przetwarzajListePodsieci()
{
	if(XMLHttpRequestObjectSubnet)
	{
		if(XMLHttpRequestObjectSubnet.readyState == 4 && XMLHttpRequestObjectSubnet.status == 200)
		{
			var podsieci = XMLHttpRequestObjectSubnet.responseXML;
			if(!podsieci || !podsieci.documentElement)
			{
				alert("nic nie doszlo"+XMLHttpRequestObjectSubnet.responseText);
			}
			else if(podsieci.documentElement.nodeName == "parsererror")
			{
		//		alert("blad parsera"+XMLHttpRequestObjectSubnet.responseText);
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
	if(XMLHttpRequestObjectSubnet)
	{
		XMLHttpRequestObjectSubnet.open("POST", 'ajax/getSubnets.php?vlan='+vlan);
		var nazwa = document.getElementById("nazwa_vlanu");
		nazwa.innerHTML = "Podsieci vlanu "+vlan+":";
		var ukryte_vid = document.getElementById("vlan_hidden_form");
		ukryte_vid.value = vlan;
		XMLHttpRequestObjectSubnet.onreadystatechange = przetwarzajListePodsieci;
		XMLHttpRequestObjectSubnet.send(null);
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
			var opis = 'Podsiec: '+podsieci[i].childNodes[1].firstChild.nodeValue;
			opis += '/' + podsieci[i].childNodes[2].firstChild.nodeValue;
			opis += ' (' + podsieci[i].childNodes[4].firstChild.nodeValue + ')';
			var text = document.createTextNode(opis);
			element.appendChild(text);
                        var createClickHandler = function(s_id, g_id, title){return function(){ {pobierzOpcjeDhcp(s_id, g_id, title);};}                         }

			element.onclick = createClickHandler(subnet_id, '1', opis);
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
	if(XMLHttpRequestObjectSubnet)
	{
		XMLHttpRequestObjectSubnet.open("POST", 'ajax/getSubnets.php?s_id='+s_id);
		var nazwa = document.getElementById("nazwa_vlanu");
		nazwa.innerHTML = "Podsieci vlanu "+vlan+":";
		var ukryte_vid = document.getElementById("vlan_hidden_form");
		ukryte_vid.value = vlan;
		XMLHttpRequestObjectSubnet.onreadystatechange = przetwarzajListePodsieci;
		XMLHttpRequestObjectSubnet.send(null);
	}
}
function zmienOpcjeDhcp(parentDiv)
{
	//alert("wywoluje z adresem ");
	if(XMLHttpRequestObjectOptionAdd)
	{
          var o_subnet = parentDiv.childNodes[0].value; 
          var o_group = parentDiv.childNodes[1].value; 
          var o_id = parentDiv.childNodes[2].value; 
          var o_value = parentDiv.childNodes[3].value; 
          var o_weight = parentDiv.childNodes[4].value; 
          XMLHttpRequestObjectOptionAdd.open("POST", 'ajax/dhcp/optionSet.php?g_id='+o_group+'&s_id='+o_subnet+'&o_id='+o_id+'&o_value='+o_value+'&o_weight='+o_weight, false);
	  XMLHttpRequestObjectOptionAdd.send(null);
	  var wynik = XMLHttpRequestObjectOptionAdd.responseText;
          alert(wynik);
          var title = document.getElementById('nazwa_vlanu').firstChild.textContent;
	  pobierzOpcjeDhcp(o_subnet, o_group, title);
        }
}
function ustawGrupePodsieci(grupa, podsiec)
{
	//alert("wywoluje z adresem ");
	if(XMLHttpRequestObjectSubnetGrAdd)
	{
          XMLHttpRequestObjectSubnetGrAdd.open("POST", 'ajax/dhcp/subnetSetGroup.php?g_id='+grupa+'&s_id='+podsiec, false);
	  XMLHttpRequestObjectSubnetGrAdd.send(null);
	  var wynik = XMLHttpRequestObjectSubnetGrAdd.responseText;
          alert(wynik);
          pobierzPodsieci(document.getElementById('vlan_hidden_form').value);
        }
}
var XMLHttpRequestObjectSubnet = getXMLHttpRequestObject();
var XMLHttpRequestObjectSubnetGrAdd = getXMLHttpRequestObject();
