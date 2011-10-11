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
function pobierz(dev_id)
{
	//alert("wywoluje z adresem "+dev_id);
	if(XMLHttpRequestObject)
	{
		XMLHttpRequestObject.open("POST", 'ajax/getChildren.php?dev_id='+dev_id, false);
		XMLHttpRequestObject.send(null);
		var lista = XMLHttpRequestObject.responseXML;
		if(! lista)
			alert(XMLHttpRequestObject.responseText);
		else
			dodajWezel(lista);
	}
}
function dodajWezel(lista)
{
	var dzieci = lista.documentElement.childNodes;
	var parentMac = dzieci[0].firstChild.firstChild.nodeValue;
	var parentLoc = dzieci[0].lastChild.firstChild.nodeValue;
	//alert(parentMac);
	var wezel_nadrz = document.getElementById(parentMac);
	if(!wezel_nadrz)
	{
		alert("nie odnaleziono wezla o id = \""+parentMac+"\"");
		exit(0);	
	}
	else if(wezel_nadrz.childNodes.length > 1)
	{
	//	alert("usuwam");
		wezel_nadrz.removeChild(wezel_nadrz.lastChild);
	}
	else if(dzieci.length > 1)
	{	
	
		var obecne = lista.documentElement.firstChild.nextSibling;
		var nowaLista = document.createElement("ul");
		for (var i=1; i<dzieci.length; i++)
		{
			var element = document.createElement("li");
			element.id = dzieci[i].firstChild.firstChild.nodeValue;
			if( i==dzieci.length -1){
				//alert("ostatni");
				element.setAttribute("class", "punkt_koniec");
			}
			//element.className = "koniec";
			//jezeli element ma zmienna exists=0 wtedyjest bw
			var bw='';
                        if(dzieci[i].childNodes[9] && dzieci[i].childNodes[9].firstChild)
                                if(dzieci[i].childNodes[9].firstChild.nodeValue=='0')
					bw="_bw";
			var ikona = "<img src=\"images/" + dzieci[i].childNodes[3].firstChild.nodeValue + bw + ".png\" height=\"18\"> ";
			var tmp = dzieci[i].childNodes[2].firstChild.nodeValue;
			tmp = tmp +": " + ikona;
			var klatka; 
			var ip;
			if(dzieci[i].childNodes[10] && dzieci[i].childNodes[10].firstChild)
				ip = dzieci[i].childNodes[10].firstChild.nodeValue;
			else
				ip = '';
			if(dzieci[i].childNodes[3].firstChild.nodeValue=='Host')
				klatka="/"+dzieci[i].childNodes[8].firstChild.nodeValue;
			else if(dzieci[i].childNodes[6].firstChild)
				klatka = dzieci[i].childNodes[6].firstChild.nodeValue;
			else
				klatka=" ";
			if(dzieci[i].childNodes[7].firstChild && dzieci[i].childNodes[7].firstChild.nodeValue)
				tmp = tmp + "<strong>"+ dzieci[i].childNodes[7].firstChild.nodeValue + "</strong>";
			else
				tmp = tmp + "<strong>"+ dzieci[i].childNodes[4].firstChild.nodeValue +" "+ dzieci[i].childNodes[5].firstChild.nodeValue + klatka + "</strong>";
			tmp = tmp + "<font class=\"ip\"> [" + ip + "]</font>";
			//alert (tmp);
			var tekst = document.createElement("div");
			tekst.innerHTML = tmp;
			tekst.onclick = function() {pobierz(this.parentNode.id);}
			element.appendChild(tekst);
			nowaLista.appendChild(element);
		}
		wezel_nadrz.appendChild(nowaLista);
	}
	pobierzOpcje(parentMac);
	pobierzHistoria(parentMac, parentLoc);
}
function przetwarzajOpcje()
{
	if(XMLHttpRequestObjectOpcje)
	{
		if(XMLHttpRequestObjectOpcje.readyState == 4 && XMLHttpRequestObjectOpcje.status == 200)
		{
			var opcje = XMLHttpRequestObjectOpcje.responseXML;
			if(!opcje || !opcje.documentElement)
			{
				alert("nic nie doszlo"+XMLHttpRequestObjectOpcje.responseText);
			}
			else if(opcje.documentElement.nodeName == "parsererror")
			{
				alert("blad parsera"+XMLHttpRequestObjectOpcje.responseText);
			}
			else
			{	
		//		alert("wywoluje");
				wyswietlOpcje(opcje);
			}
		}
	//	alert("state: "+XMLHttpRequestObjectOpcje.readyState+"\nstatus: "+XMLHttpRequestObjectOpcje.status);
	}
}
function pobierzOpcje(dev_id)
{
	//alert("wywoluje z adresem "+dev_id);
	if(XMLHttpRequestObjectOpcje)
	{
		XMLHttpRequestObjectOpcje.open("POST", 'ajax/getParameters.php?dev_id='+dev_id);
		XMLHttpRequestObjectOpcje.onreadystatechange = przetwarzajOpcje;
		XMLHttpRequestObjectOpcje.send(null);
	}
}
function wyswietlOpcje(opcje)
{
	//alert(opcje);
	var opcje = opcje.documentElement.childNodes;
	var dev_id = opcje[3].firstChild.nodeValue;
	var wezel_nadrz = document.getElementById("opis");
        var vscroll = (document.scrollTop ? document.scrollTop : window.pageYOffset);
        wezel_nadrz.style.marginTop = vscroll +'px';
	var con_id = null;
       // alert(vscroll);
	//alert(dev_id);
		var tabela = "<table class=\"opis\"><tbody>";
		for (var i=0; i<opcje.length; i++)
		{
			tabela+="<tr><td style=\"background:#888; width:150px;\">";
			tabela+=opcje[i].nodeName;
			if(opcje[i].nodeName=="Zamontowane" && opcje[i].firstChild.nodeValue=="NIE")
				tabela+="</td><td bgcolor=\"red\">";
			else if(opcje[i].nodeName=="con_id" && opcje[i].firstChild)
			{
				con_id =  opcje[i].firstChild.nodeValue;
				tabela+="</td><td>";
			}
			else
				tabela+="</td><td>";
                        var nodeValue;
                        if(opcje[2].firstChild.nodeValue=="Host" && opcje[i].nodeName=="Adres_MAC")
                        {
                          var mac_dec = parseInt(opcje[i].firstChild.nodeValue.replace(/:/g, ""),16);
                          nodeValue = "<a class=\"mac\" target=\"_blank\" href=\"http://172.20.4.19/src/index.php?sourceid=3&amp;filter=clientmac%3A%3D"+mac_dec+"&amp;search=Search\">"+opcje[i].firstChild.nodeValue+"</a>";
                        }
			else if(opcje[i].nodeName=="Lokalizacja" && opcje[2].firstChild.nodeValue=="Host" && opcje[i].firstChild && opcje[6].nodeName=="Numer_mieszkania")
                          nodeValue = '<b>' + opcje[i].firstChild.nodeValue + '/' + opcje[6].firstChild.nodeValue + '</b>';
                        else
                          nodeValue = opcje[i].firstChild.nodeValue;
			tabela+=nodeValue;
			tabela+="</td></tr>";
			//alert("bu");
			//var wiersz = tabela.insertRow();
			//var nazwa = wiersz.insertCell(0);
			//nazwa.innerHTML="nazwa";
			//var wartosc = document.createElement("td");
			//nazwa.innerHtml = "nazwa";//opcje[i].firstChild.firstChild.nodeName;
			//wartosc.innerHtml = opcje[i].firstChild.firstChild.nodeValue;
			//wiersz.appendChild(nazwa);
			//wiersz.appendChild(wartosc);
			//tabela.appendChild(wiersz);
		}
		tabela+="</tbody></table>";
		tabela+="<table border=\"0\"><tr><td><form method=\"get\" action=\"modyfikuj.php\"><input type=\"hidden\" name=\"device\" value=\""+dev_id+"\"><input type=\"submit\" value=\"Modyfikuj\"></form></td>";
		if(opcje[2].firstChild.nodeValue=="Virtual")
		{
			tabela+="<td><form action=\"usun.php\" method=\"get\"><input type=\"hidden\" name=\"dev_id\" value=\""+dev_id+"\"><input type=\"submit\" value=\"Usuń\"></form></td>";
		}
		else if(opcje[2].firstChild.nodeValue!="Host")
		{
			tabela+="<td><form action=\"wymien.php\" method=\"get\"><input type=\"hidden\" name=\"dev_id\" value=\""+dev_id+"\"><input type=\"submit\" value=\"Wymień\"></form></td>";
			tabela+="<td><form action=\"przeniesDoMagazynu.php\" method=\"get\"><input type=\"hidden\" name=\"dev_id\" value=\""+dev_id+"\"><input type=\"submit\" value=\"Przenieś Do Magazynu\"></form></td>";
		}
		else
		                        tabela+="<td><form target=\"_blank\" action=\"https://lista-serwis.wtvk.pl/edit.php\" method=\"get\"><input type=\"hidden\" name=\"main_id\" value=\""+con_id+"\"><input type=\"hidden\" name=\"tryb\" value=\"edit\"><input type=\"submit\" value=\"Lista podłączeń\"></form></td>";
		//alert(tabela);
		wezel_nadrz.innerHTML = tabela;

}
function przetwarzajHistoria()
{
	if(XMLHttpRequestObjectHistoria)
	{
		if(XMLHttpRequestObjectHistoria.readyState == 4 && XMLHttpRequestObjectHistoria.status == 200)
		{
			var rekordy = XMLHttpRequestObjectHistoria.responseXML;
			if(!rekordy || !rekordy.documentElement)
			{
				alert("nic nie doszlo"+XMLHttpRequestObjectHistoria.responseText);
			}
			else if(rekordy.documentElement.nodeName == "parsererror")
			{
				var wezel_nadrz = document.getElementById("historia");
				wezel_nadrz.innerHTML = '';
//				alert("blad parsera"+XMLHttpRequestObjectHistoria.responseText);
			}
			else
			{	
				wyswietlHistoria(rekordy);
			}
		}
	}
}
function pobierzHistoria(dev_id, lokalizacja)
{
	if(XMLHttpRequestObjectHistoria)
	{
		XMLHttpRequestObjectHistoria.open("POST", 'ajax/getHistory.php?dev_id=' + dev_id + '&lokalizacja=' + lokalizacja);
		XMLHttpRequestObjectHistoria.onreadystatechange = przetwarzajHistoria;
		XMLHttpRequestObjectHistoria.send(null);
	}
}
function wyswietlHistoria(rekordy)
{
	//alert(rekordy);
	var rekordy = rekordy.documentElement.childNodes;
	var wezel_nadrz = document.getElementById("historia");
	//alert(dev_id);
	var tabela = "<div style=\"margin-top:40px; text-align:center; font-style:italic; font-weight:bolder;\">Historia</div><table class=\"historia_table\"><tbody>";
	tabela += "<tr style=\"font-weight: bold; background:#888;\">";
	tabela +=	"<td style=\"width:125px;\">Czas</td>";
	tabela += 	"<td style=\"width:295px;\">Opis</td>";
	tabela +=	"<td style=\"width:70px\">User</td>";
	tabela += 	"<td style=\"width:50px;\">Operacja</td>";
	var i;
	for (i=0; i<rekordy.length; i++)
	{
		tabela+="<tr>";
		for(var j=0; j<rekordy[i].childNodes.length; j++)
		{
			tabela+="<td>";
			if(rekordy[i].childNodes[j].firstChild)
				tabela+=rekordy[i].childNodes[j].firstChild.nodeValue;
			tabela+="</td>";
			//alert("bu");
			//var wiersz = tabela.insertRow();
			//var nazwa = wiersz.insertCell(0);
			//nazwa.innerHTML="nazwa";
			//var wartosc = document.createElement("td");
			//nazwa.innerHtml = "nazwa";//rekordy[i].firstChild.firstChild.nodeName;
			//wartosc.innerHtml = rekordy[i].firstChild.firstChild.nodeValue;
			//wiersz.appendChild(nazwa);
			//wiersz.appendChild(wartosc);
			//tabela.appendChild(wiersz);
		}
		tabela+="</tr>";
	}
	tabela+="</tbody></table>";
	//alert(tabela);
	if(i>0)
		wezel_nadrz.innerHTML = tabela;
	else
		wezel_nadrz.innerHTML = '';


}
function getParentDeviceId(dev_id)
{
	//alert("wywoluje z adresem "+dev_id);
	if(XMLHttpRequestObjectDevId)
	{
		XMLHttpRequestObjectDevId.open("POST", 'ajax/getParentDeviceId.php?dev_id='+dev_id, false);
		XMLHttpRequestObjectDevId.send(null);
		var dev_id = XMLHttpRequestObjectDevId.responseText;
		return dev_id;
	}
}
function rozwinDrzewo(dev_id)
{
	var parent = getParentDeviceId(dev_id);
	if(parent)
		rozwinDrzewo(parent);
	pobierz(dev_id);
}
var XMLHttpRequestObjectDevId = getXMLHttpRequestObject();
var XMLHttpRequestObject = getXMLHttpRequestObject();
var XMLHttpRequestObjectOpcje = getXMLHttpRequestObject();
var XMLHttpRequestObjectHistoria = getXMLHttpRequestObject();
