var XMLHttpRequestObjectPort = getXMLHttpRequestObject();
function pobierzPort(dev_id,child)
{

	if(XMLHttpRequestObjectPort)
	{
		XMLHttpRequestObjectPort.open("POST", 'ajax/getPorts.php?dev_id='+dev_id+'&child='+child);
		XMLHttpRequestObjectPort.onreadystatechange = przetwarzajPort;
		XMLHttpRequestObjectPort.send(null);
	}
}
function przetwarzajPort()
{

	if(XMLHttpRequestObjectPort)
	{
		if(XMLHttpRequestObjectPort.readyState == 4 && XMLHttpRequestObjectPort.status == 200)
		{
			var lista = XMLHttpRequestObjectPort.responseXML;
			if(!lista || !lista.documentElement)
			{
				alert("nic nie doszlo"+XMLHttpRequestObjectPort.responseText);
			}
			else if(lista.documentElement.nodeName == "parsererror")
			{
				alert("blad parsera"+XMLHttpRequestObjectPort.responseText);
			}
			else
			{	
				wygenerujPort(lista);
			}
		}
	}
}
function wygenerujPort(lista)
{
	var porty = lista.documentElement.childNodes;
	var select = document.getElementById('parent_port');
	while(select.length > 0)
		select.removeChild(select.firstChild);
	for(var i=0; i<porty.length; i++)
	{
		var nazwa_tekst = document.createTextNode(porty[i].firstChild.nodeValue);
		var option = document.createElement("option");
		option.value = porty[i].firstChild.nodeValue;
		option.appendChild(nazwa_tekst);
		select.appendChild(option);
	}
}
function aktywujPort(id)
{
	var select = document.getElementById('parent_port');
	for(var i=0; i<select.length; i++)
	{
		if(select.options[i].value == id)
			select.options[i].selected=true;
	}
}
