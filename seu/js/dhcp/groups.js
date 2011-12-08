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
function wynikDodawaniaGrupy()
{
	if(XMLHttpRequestObject)
	{
		if(XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200)
		{
			var wynik = XMLHttpRequestObject.responseText;
			alert(wynik);
			pobierzGrupy();
		}
	}
}
function pobierzGrupy()
{
	//alert("wywoluje z adresem ");
	if(XMLHttpRequestObject)
	{
		XMLHttpRequestObject.open("POST", 'ajax/dhcp/groupsGet.php');
		XMLHttpRequestObject.onreadystatechange = przetwarzajGrupy;
		XMLHttpRequestObject.send(null);
	}
}
function przetwarzajGrupy()
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
				wyswietlGrupy(lista, 'grupy_lista');
			}
		}
	}
}
function wyswietlGrupy(lista, root)
{
	var grupy = lista.documentElement.childNodes;
	//alert(grupy.length);
	var wezel_nadrz = document.getElementById("lewa");
	if(!wezel_nadrz)
	{
		alert("nie odnaleziono wezla nadrzednego");
		exit(0);	
	}
	else if(grupy.length > 0)
	{	
		var stara_lista = document.getElementById(root);
		if (stara_lista)
		{
//			alert("byla stara");
			wezel_nadrz.removeChild(stara_lista);
		}
		var nowa_lista = document.createElement("ul");
		nowa_lista.id = root;
		for (var i=0; i<grupy.length; i++)
		{
			var element = document.createElement("li");
			element.className = 'vlan_lewa';
			if(grupy[i].childNodes[1].firstChild)
				element.title = grupy[i].childNodes[1].firstChild.nodeValue;
			var g_id = grupy[i].firstChild.firstChild.nodeValue;
			var g_name = 'Grupa: '+grupy[i].childNodes[1].firstChild.nodeValue;
			var text = document.createTextNode(g_name);
			element.appendChild(text);
                        var createClickHandler = function(s_id, g_id, title){return function(){ {pobierzOpcjeDhcp(s_id, g_id, title);};}                         }
			element.onclick = createClickHandler('1', g_id, g_name);
	//		alert(g_id);
			nowa_lista.appendChild(element);
		}
		
	//	alert(wezel_nadrz);
		wezel_nadrz.appendChild(nowa_lista);
	}
	else alert(grupy.length);
}



function dodajGrupe()
{
	//alert("wywoluje z adresem ");
	if(XMLHttpRequestObject)
	{
	        var group = document.getElementById("group_form").value;
		XMLHttpRequestObject.open("POST", 'ajax/dhcp/groupAdd.php?g_name='+group+'&g_desc='+group);
		XMLHttpRequestObject.onreadystatechange = wynikDodawaniaGrupy;
		XMLHttpRequestObject.send(null);
	}
}
function usunGrupe(g_id)
{
	//alert("wywoluje z adresem ");
	if(XMLHttpRequestObject)
	{
		XMLHttpRequestObject.open("POST", 'ajax/groupDel.php?g_id='+g_id);
		XMLHttpRequestObject.onreadystatechange = wynikUsuwaniaGrupy;
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
var XMLHttpRequestObject = getXMLHttpRequestObject();
