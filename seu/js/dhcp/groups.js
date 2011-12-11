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
	if(XMLHttpRequestObjectG4)
	{
		if(XMLHttpRequestObjectG4.readyState == 4 && XMLHttpRequestObjectG4.status == 200)
		{
			var wynik = XMLHttpRequestObjectG4.responseText;
			alert(wynik);
			pobierzGrupy();
		}
	}
}
function pobierzListeGrup()
{
	//alert("wywoluje z adresem ");
	if(XMLHttpRequestObjectG1)
	{
		XMLHttpRequestObjectG1.open("POST", 'ajax/dhcp/groupsGet.php', false);
	        XMLHttpRequestObjectG1.send(null);
	        var wynik = XMLHttpRequestObjectG1.responseXML;
	        var grupy = wynik.documentElement.childNodes;
                return grupy;
	}
}
function pobierzGrupy()
{
	//alert("wywoluje z adresem ");
	if(XMLHttpRequestObjectG2)
	{
		XMLHttpRequestObjectG2.open("POST", 'ajax/dhcp/groupsGet.php', false);
		XMLHttpRequestObjectG2.send(null);
		var lista = XMLHttpRequestObjectG2.responseXML;
		wyswietlGrupy(lista, 'grupy_lista');
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
			var g_name = grupy[i].childNodes[1].firstChild.nodeValue;
			var text = document.createTextNode(g_name);
			element.appendChild(text);
                        g_name = 'Grupa '+g_name;
                        var createClickHandler = function(s_id, g_id, title){return function(){ {pobierzOpcjeDhcp(s_id, g_id, title); dodajPrzyciskUsuwania(g_id) };}                         }
			element.onclick = createClickHandler('1', g_id, g_name);
	//		alert(g_id);
			nowa_lista.appendChild(element);
		}
		
	//	alert(wezel_nadrz);
		wezel_nadrz.appendChild(nowa_lista);
	}
	else alert(grupy.length);
}
function dodajPrzyciskUsuwania(g_id)
{
  var p = document.getElementById('aktywne_opcje_lista');
  var przycisk = document.createElement('button');
  przycisk.appendChild(document.createTextNode('Usuń grupę'));
  var createClickHandler = function(g_id){return function(){ {usunGrupe(g_id); };}                         }
  przycisk.onclick = createClickHandler(g_id);
  p.appendChild(przycisk);
}

function dodajGrupe()
{
	//alert("wywoluje z adresem ");
	if(XMLHttpRequestObjectG4)
	{
	        var group = document.getElementById("group_form").value;
		XMLHttpRequestObjectG4.open("POST", 'ajax/dhcp/groupAdd.php?g_name='+group+'&g_desc='+group);
		XMLHttpRequestObjectG4.onreadystatechange = wynikDodawaniaGrupy;
		XMLHttpRequestObjectG4.send(null);
	}
}
function usunGrupe(g_id)
{
	//alert("wywoluje z adresem ");
	if(XMLHttpRequestObjectG3)
	{
		XMLHttpRequestObjectG3.open("POST", 'ajax/dhcp/groupDel.php?g_id='+g_id, false);
		XMLHttpRequestObjectG3.send(null);
	        var wynik = XMLHttpRequestObjectG3.responseText;
                alert(wynik);
                removeChildren('aktywne_opcje_lista');
                pobierzGrupy();
	}
}
function removeChildren(nodeId)
{
  var p = document.getElementById(nodeId);
  if(!p)
    return false;
  var length =p.childNodes.length; 
  for(i=0; i<length; i++)
    p.removeChild(p.lastChild);
}
var XMLHttpRequestObjectG4 = getXMLHttpRequestObject();
var XMLHttpRequestObjectG3 = getXMLHttpRequestObject();
var XMLHttpRequestObjectG2 = getXMLHttpRequestObject();
var XMLHttpRequestObjectG1 = getXMLHttpRequestObject();
