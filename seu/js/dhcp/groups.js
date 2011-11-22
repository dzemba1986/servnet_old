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
		XMLHttpRequestObject.open("POST", 'ajax/dhcp/getGroups.php');
		XMLHttpRequestObject.onreadystatechange = przetwarzajGrupy;
		XMLHttpRequestObject.send(null);
	}
}
function dodajGrupe()
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
function usunGrupe(vid)
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
var XMLHttpRequestObject = getXMLHttpRequestObject();
