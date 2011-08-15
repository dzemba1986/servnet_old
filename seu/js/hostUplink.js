var XMLHttpRequestObjectUplink = getXMLHttpRequestObject();

function getDeviceUplink(par1)
{

	if(XMLHttpRequestObjectUplink)
	{
			XMLHttpRequestObjectUplink.open("POST", 'ajax/getDeviceUplink.php?host_parent=' + par1, false);
			XMLHttpRequestObjectUplink.send(null);
			przetwarzajUplink();
	}
}
function przetwarzajUplink()
{

	if(XMLHttpRequestObjectUplink)
	{
		if(XMLHttpRequestObjectUplink.readyState == 4 && XMLHttpRequestObjectUplink.status == 200)
		{
			var lista = XMLHttpRequestObjectUplink.responseXML;
			if(!lista || !lista.documentElement)
			{
				alert("nic nie doszlo"+XMLHttpRequestObjectUplink.responseText);
			}
			else if(lista.documentElement.nodeName == "parsererror")
			{
				alert("blad parsera"+XMLHttpRequestObjectUplink.responseText);
			}
			else
			{	
				wygenerujUplink(lista);
			}
		}
	}
}
function wygenerujUplink(lista)
{
	//div = uplinks
	var porty = lista.documentElement.childNodes;
	LOCAL_PORTS = porty[0];
	PARENT_PORTS = porty[1];
	UPLINK_CONNECTIONS = porty[2];
	var uplink_div = document.getElementById('uplinks');
	while(uplink_div.childNodes.length>0)
		uplink_div.removeChild(uplink_div.lastChild);
	if(uplink_div.nextSibling && uplink_div.nextSibling.value=="dodaj")
		uplink_div.parentNode.removeChild(uplink_div.nextSibling);
	if(UPLINK_CONNECTIONS && UPLINK_CONNECTIONS.childNodes.length > 0)
		for(var i=0; i<UPLINK_CONNECTIONS.childNodes.length; i++)
	{
		var index="0";
		if(i > 0)
			index = i;
		var local_div = document.createElement('div');
		local_div.id = "uplink_div_"+i;
		var parent_select = generatePortForm('uplink_parent_select['+index+']', PARENT_PORTS.childNodes, UPLINK_CONNECTIONS.childNodes[i].childNodes[3].firstChild.nodeValue);
		var local_select = generatePortForm('uplink_local_select['+index+']', LOCAL_PORTS.childNodes, UPLINK_CONNECTIONS.childNodes[i].childNodes[1].firstChild.nodeValue);
		local_div.appendChild(document.createTextNode('parent_port '));
		local_div.appendChild(parent_select);
		local_div.appendChild(document.createTextNode('local_port '));
		local_div.appendChild(local_select);
		var usun = document.createElement('input');
		usun.type = "button";
		usun.value = "usuń";
		if(i>0)
		{
			usun.onclick = function(){
					var p_div = document.getElementById("uplinks");
					var child_id = this.parentNode.id.substr(11);
					p_div.removeChild(p_div.childNodes[child_id]);
			}
			local_div.appendChild(usun);
		}
		uplink_div.appendChild(local_div);
	}
	else
	{
		var index="0";
		if(i > 0)
			index = i;
		var local_div = document.createElement('div');
		local_div.id = "uplink_div_0";
		var parent_select = generatePortForm('uplink_parent_select['+index+']', PARENT_PORTS.childNodes, null);
		var local_select = generatePortForm('uplink_local_select['+index+']', LOCAL_PORTS.childNodes, null);
		local_div.appendChild(document.createTextNode('parent_port '));
		local_div.appendChild(parent_select);
		local_div.appendChild(document.createTextNode('local_port '));
		local_div.appendChild(local_select);
		uplink_div.appendChild(local_div);
		//dodaj.appendChild(document.createTextNode('<input type="button" value="dodaj" onclick="addEmptyUplink();">'));
		
	}
	var dodaj = document.createElement('input');
	dodaj.type = "button";
	dodaj.value = "dodaj";
	dodaj.onclick = addEmptyUplink;
	//dodaj.appendChild(document.createTextNode('<input type="button" value="dodaj" onclick="addEmptyUplink();">'));
	uplink_div.parentNode.appendChild(dodaj);
}
function addEmptyUplink()
{
	var uplink_div = document.getElementById('uplinks');
	var i="0";
	if(uplink_div.childNodes.length>0)
		i = uplink_div.childNodes.length;
	var local_div = document.createElement('div');
	local_div.id = "uplink_div_"+i;
	var parent_select = generatePortForm('uplink_parent_select['+i+']', PARENT_PORTS.childNodes);
	var local_select = generatePortForm('uplink_local_select['+i+']', LOCAL_PORTS.childNodes);
	local_div.appendChild(document.createTextNode('parent_port '));
	local_div.appendChild(parent_select);
	local_div.appendChild(document.createTextNode('local_port '));
	local_div.appendChild(local_select);
	var usun = document.createElement('input');
	usun.type = "button";
	usun.value = "usuń";
	usun.onclick = function(){
			var p_div = document.getElementById("uplinks");
			var child_id = this.parentNode.id.substr(11);
			p_div.removeChild(p_div.childNodes[child_id]);
	}
	local_div.appendChild(usun);
	uplink_div.appendChild(local_div);
}


function generatePortForm(name, ports, active)
{
	var select = document.createElement('select');
	select.name = name;
	for(var i=0; i<ports.length; i++)
	{
		var port = ports[i].firstChild.nodeValue;
		var option = document.createElement('option');
		option.value = port;
		var tekst = document.createTextNode(port);
		option.appendChild(tekst);
		if(port==active)
			option.selected=true;
		select.appendChild(option);
	}
	return select;
}

