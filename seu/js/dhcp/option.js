function pobierzDostepneOpcjeDhcp(s_id, g_id)
{
	//alert("wywoluje z adresem ");
	if(XMLHttpRequestObjectOptionsAvaible)
	{
		XMLHttpRequestObjectOptionsAvaible.open("POST", 'ajax/dhcp/optionsGet.php?s_id='+s_id+'g_id='+g_id);
		XMLHttpRequestObjectOptionsAvaible.onreadystatechange = przetwarzajDostepneOpcjeDhcp;
		XMLHttpRequestObjectOptionsAvaible.send(null);
	}
}
function przetwarzajDostepneOpcjeDhcp()
{
	if(XMLHttpRequestObjectOptionsAvaible)
	{
		if(XMLHttpRequestObjectOptionsAvaible.readyState == 4 && XMLHttpRequestObjectOptions.status == 200)
		{
			var lista = XMLHttpRequestObjectOptionsAvaible.responseXML;
			if(!lista || !lista.documentElement)
			{
				alert("nic nie doszlo"+XMLHttpRequestObjectOptionsAvaible.responseText);
			}
			else if(lista.documentElement.nodeName == "parsererror")
			{
				alert("blad parsera"+XMLHttpRequestObjectOptionsAvaible.responseText);
			}
			else
			{	
				wyswietlDostepneOpcjeDhcp(lista, selektor);
			}
		}
	}
}
function wyswietlDostepneOpcjeDhcp(lista, root)
{
	var grupy = lista.documentElement.childNodes;
	//alert(grupy.length);
	var wezel_nadrz = document.getElementById("prawa");
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
			var g_name = grupy[i].childNodes[1].firstChild.nodeValue;
			var text = document.createTextNode(g_name);
			element.appendChild(text);
	//		element.onclick = function() {pobierz(this.firstChild.nodeValue)}
	//		alert(g_id);
			nowa_lista.appendChild(element);
		}
		
	//	alert(wezel_nadrz);
		wezel_nadrz.appendChild(nowa_lista);
	}
	else alert(grupy.length);
}
var XMLHttpRequestObjectOptionsAvaible = getXMLHttpRequestObject();
