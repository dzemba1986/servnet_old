var XMLHttpRequestObjectModel = getXMLHttpRequestObject();
function pobierzModel(device_type, producent)
{

	if(XMLHttpRequestObjectModel)
	{
		XMLHttpRequestObjectModel.open("POST", 'ajax/getModel.php?device_type='+device_type+'&producent='+producent);
		XMLHttpRequestObjectModel.onreadystatechange = przetwarzajModel;
		XMLHttpRequestObjectModel.send(null);
	}
}
function przetwarzajModel()
{

	if(XMLHttpRequestObjectModel)
	{
		if(XMLHttpRequestObjectModel.readyState == 4 && XMLHttpRequestObjectModel.status == 200)
		{
			var lista = XMLHttpRequestObjectModel.responseXML;
			if(!lista || !lista.documentElement)
			{
				alert("nic nie doszlo"+XMLHttpRequestObjectModel.responseText);
			}
			else if(lista.documentElement.nodeName == "parsererror")
			{
				//alert("blad parsera"+XMLHttpRequestObjectModel.responseText);
				var select = document.getElementById('model');
				while(select.lastChild)
					select.removeChild(select.lastChild);
			}
			else
			{	
				wygenerujModel(lista);
			}
		}
	}
}
function wygenerujModel(lista)
{
	var modele = lista.documentElement.childNodes;
	var select = document.getElementById('model');
	while(select.lastChild)
		select.removeChild(select.lastChild);
	var option = document.createElement("option");
	select.appendChild(option);
	for(var i=0; i<modele.length; i++)
	{
		var nazwa_tekst = document.createTextNode(modele[i].childNodes[1].firstChild.nodeValue);
		var option = document.createElement("option");
		option.value = modele[i].firstChild.firstChild.nodeValue;
		option.appendChild(nazwa_tekst);
		//jeszcze ustawimy liczbę portów
		option.onclick = function() {
			//var modele = XMLHttpRequestObjectModel.responseXML.documentElement.childNodes;
			var id_modelu = this.value;//parseInt(this.value);
			var port_number = null;	
			for(var j=0; j<modele.length; j++)
			{
				if(modele[j].childNodes[0].firstChild.nodeValue == id_modelu)
					port_number = modele[j].childNodes[4].firstChild.nodeValue;
			}
			//ponieważ model można tylko zmienić przy dodawaniu więc aktywować listę uplinku będziemy za pomocą dwuparametrowego schematu
			if(this.value && PARENT_DEVICE)
			{
				LOCAL_DEV_MODEL = this.value;
				getDeviceUplink(PARENT_DEVICE, this.value);
			}
			else
				alert("brak niezbędnych danych o parent_device ("+PARENT_DEVICE+") lub device_model_id ("+this.value+")"); 
		}
		select.appendChild(option);
	}
	aktywujModel(select.options[0].value);
}
function aktywujModel(id)
{
	if(!id)
		return;
	var select = document.getElementById('model');
//	alert(select.length);
	for(var i=0; i<select.length; i++)
	{
		if(select.options[i].value == id)
			select.options[i].selected=true;
	}
}
