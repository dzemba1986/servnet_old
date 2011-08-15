var XMLHttpRequestObjectProducent = getXMLHttpRequestObject();
function pobierzProducent()
{

	if(XMLHttpRequestObjectProducent)
	{
		XMLHttpRequestObjectProducent.open("POST", 'ajax/getProducent.php');
		XMLHttpRequestObjectProducent.onreadystatechange = przetwarzajProducent;
		XMLHttpRequestObjectProducent.send(null);
	}
}
function przetwarzajProducent()
{

	if(XMLHttpRequestObjectProducent)
	{
		if(XMLHttpRequestObjectProducent.readyState == 4 && XMLHttpRequestObjectProducent.status == 200)
		{
			var lista = XMLHttpRequestObjectProducent.responseXML;
			if(!lista || !lista.documentElement)
			{
				alert("nic nie doszlo"+XMLHttpRequestObjectProducent.responseText);
			}
			else if(lista.documentElement.nodeName == "parsererror")
			{
				alert("blad parsera"+XMLHttpRequestObjectProducent.responseText);
			}
			else
			{	
				wygenerujProducent(lista);
			}
		}
	}
}
function wygenerujProducent(lista)
{
	var producenci = lista.documentElement.childNodes;
	var select = document.getElementById('producent');
	var option = document.createElement("option");
	select.appendChild(option);
	for(var i=0; i<producenci.length; i++)
	{
		var nazwa_tekst = document.createTextNode(producenci[i].childNodes[1].firstChild.nodeValue);
		var option = document.createElement("option");
		option.value = producenci[i].firstChild.firstChild.nodeValue;
		option.appendChild(nazwa_tekst);
		option.onclick = function() {
			var device_type = document.getElementsByName('device_type');
			if(!device_type[0].value)
			{
				alert("Najpierw musisz wybrać typ urządzenia!");
				exit();
			}
			device_type = device_type[0].value;
			var producent = this.value;
			pobierzModel(device_type, producent);
		}

		select.appendChild(option);
	}
}
function aktywujProducent(id)
{
	var select = document.getElementById('producent');
	for(var i=0; i<select.length; i++)
	{
		if(select.options[i].value == id)
			select.options[i].selected=true;
	}
}
