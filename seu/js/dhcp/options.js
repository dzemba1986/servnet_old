function getXMLHttpRequestObjectOptions()
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
function wynikDodawaniaOpcjeDhcp()
{
	if(XMLHttpRequestObjectOptions)
	{
		if(XMLHttpRequestObjectOptions.readyState == 4 && XMLHttpRequestObjectOptions.status == 200)
		{
			var wynik = XMLHttpRequestObjectOptions.responseText;
			alert(wynik);
//			pobierzOpcjeDhcp();
		}
	}
}
function pobierzOpcjeDhcp(s_id, g_id, title)
{
	//alert("wywoluje z adresem ");
	if(XMLHttpRequestObjectOptions)
	{
		XMLHttpRequestObjectOptions.open("POST", 'ajax/dhcp/optionsGet.php?s_id='+s_id+'&g_id='+g_id+'&title='+title);
		XMLHttpRequestObjectOptions.onreadystatechange = przetwarzajOpcjeDhcp;
		XMLHttpRequestObjectOptions.send(null);
	}
}
function przetwarzajOpcjeDhcp()
{
	if(XMLHttpRequestObjectOptions)
	{
		if(XMLHttpRequestObjectOptions.readyState == 4 && XMLHttpRequestObjectOptions.status == 200)
		{
			var lista = XMLHttpRequestObjectOptions.responseXML;
			if(!lista || !lista.documentElement)
			{
				alert("nic nie doszlo"+XMLHttpRequestObjectOptions.responseText);
			}
			else if(lista.documentElement.nodeName == "parsererror")
			{
				alert("blad parsera"+XMLHttpRequestObjectOptions.responseText);
			}
			else
			{	
				wyswietlOpcjeDhcp(lista, 'aktywne_opcje_lista');
			}
		}
	}
}
function wyswietlOpcjeDhcp(lista, root)
{
	var opcje = lista.documentElement.firstChild.childNodes;
	var opcje_grupy = lista.documentElement.childNodes[1].childNodes;
        var parents = lista.documentElement.childNodes[2].childNodes;
        var g_id = parents[0].firstChild.nodeValue;
        var s_id = parents[1].firstChild.nodeValue;
        var title = parents[2].firstChild.nodeValue;
        var titlebox = document.getElementById('nazwa_vlanu');
        titlebox.removeChild(titlebox.firstChild);
        if(g_id==1)
          titlebox.appendChild(document.createTextNode("Podsieć: "+title));
        else if(s_id==1)
          titlebox.appendChild(document.createTextNode("Grupa: "+title));
	//alert(grupy.length);
	var wezel_nadrz = document.getElementById("prawa");
	if(!wezel_nadrz)
	{
		alert("nie odnaleziono wezla nadrzednego");
		exit(0);	
	}
        var stara_lista = document.getElementById(root);
        if (stara_lista)
        {
//			alert("byla stara");
                wezel_nadrz.removeChild(stara_lista);
        }
        var nowa_lista = document.createElement("div");
        nowa_lista.id = root;
        nowa_lista.appendChild(generateOptionRow('', s_id, g_id, '', opcje, '', '', true));
        for (var i=0; i<opcje_grupy.length; i++)
        {
          var o_group = opcje_grupy[i].firstChild.firstChild.nodeValue;
          var o_subnet = opcje_grupy[i].childNodes[1].firstChild.nodeValue;
          var o_weight = opcje_grupy[i].childNodes[2].firstChild.nodeValue;
          var o_option = opcje_grupy[i].childNodes[3].firstChild.nodeValue;
          var o_value = opcje_grupy[i].childNodes[4].firstChild.nodeValue;
          var element = generateOptionRow(i, o_subnet, o_group, o_option, opcje, o_weight, o_value, false);
          nowa_lista.appendChild(element);
        }
         
//	alert(wezel_nadrz);
        wezel_nadrz.appendChild(nowa_lista);
}
function generateOptionRow(i, subnet, group, option, options, weight, value, add)
{
    var element = document.createElement("div");
    element.className = 'dhcp_option';
    element.id = 'dhcp_option_'+i;
    var o_group = document.createElement('input');
    o_group.setAttribute('type', 'hidden');
    o_group.setAttribute('name', 'o_group');
    o_group.setAttribute('id', 'o_group'+i);
    o_group.setAttribute('value', group);

    var o_subnet = document.createElement('input');
    o_subnet.setAttribute('type', 'hidden');
    o_subnet.setAttribute('name', 'o_subnet');
    o_subnet.setAttribute('id', 'o_subnet'+i);
    o_subnet.setAttribute('value', subnet);

    var o_weight = document.createElement('input');
    o_weight.setAttribute('type', 'text');
    o_weight.setAttribute('name', 'o_weight');
    o_weight.setAttribute('id', 'o_weight'+i);
    o_weight.setAttribute('value', weight);

    var o_option = document.createElement('select');
    o_option.setAttribute('name', 'o_option');
    o_option.setAttribute('id', 'o_option'+i);
    for(var j=0; j<options.length; j++)
    {
      var opt = document.createElement('option');
      opt.setAttribute('value', options[j].childNodes[0].firstChild.nodeValue);
      opt.appendChild(document.createTextNode(options[j].childNodes[3].firstChild.nodeValue));
      if(option==options[j].childNodes[0].firstChild.nodeValue)
        opt.setAttribute('selected', true);
      o_option.appendChild(opt);
    }

    var o_value = document.createElement('input');
    o_value.setAttribute('type', 'text');
    o_value.setAttribute('name', 'o_value');
    o_value.setAttribute('id', 'o_value'+i);
    o_value.setAttribute('value', value);

    element.appendChild(o_subnet);
    element.appendChild(o_group);
    element.appendChild(o_option);
    element.appendChild(o_value);
    element.appendChild(o_weight);

    if(!add)
    {
      var o_submit = document.createElement('input');
      o_submit.setAttribute('type', 'submit');
      o_submit.setAttribute('name', 'o_submit');
      o_submit.setAttribute('id', 'o_submit'+i);
      o_submit.setAttribute('value', 'o_submit'+i);

      var o_rm = document.createElement('input');
      o_rm.setAttribute('type', 'submit');
      o_rm.setAttribute('name', 'o_rm');
      o_rm.setAttribute('id', 'o_rm'+i);
      o_rm.setAttribute('value', 'o_rm');

      element.appendChild(o_rm);
      element.appendChild(o_submit);
    }
    else
    {
      var o_submit = document.createElement('input');
      o_submit.setAttribute('type', 'submit');
      o_submit.setAttribute('name', 'o_submit');
      o_submit.setAttribute('id', 'o_submit'+i);
      o_submit.setAttribute('value', 'add');
      o_submit.onclick = function() {dodajOpcjeDhcp(this.parentNode);};
      element.appendChild(o_submit);
    }

    return element;
}
function dodajOpcjeDhcp(parentDiv)
{
	//alert("wywoluje z adresem ");
	if(XMLHttpRequestObjectOptions)
	{
          var o_subnet = parentDiv.childNodes[0].value; 
          var o_group = parentDiv.childNodes[1].value; 
          var o_id = parentDiv.childNodes[2].value; 
          var o_value = parentDiv.childNodes[3].value; 
          var o_weight = parentDiv.childNodes[4].value; 
          var group = document.getElementById("group_form").value;
          XMLHttpRequestObjectOptions.open("POST", 'ajax/dhcp/groupOptionAdd.php?g_id='+o_group+'&s_id='+o_subnet+'&o_id='+o_id+'&o_value='+o_value+'&o_weight='+o_weight);
          XMLHttpRequestObjectOptions.onreadystatechange = wynikDodawaniaOpcjeDhcp;
          XMLHttpRequestObjectOptions.send(null);
	}
}
function usunOpcjeDhcp(g_id)
{
	//alert("wywoluje z adresem ");
	if(XMLHttpRequestObjectOptions)
	{
		XMLHttpRequestObjectOptions.open("POST", 'ajax/groupDel.php?g_id='+g_id);
		XMLHttpRequestObjectOptions.onreadystatechange = wynikUsuwaniaOpcjeDhcp;
		XMLHttpRequestObjectOptions.send(null);
	}
}
function wynikUsuwaniaVlanu()
{
	if(XMLHttpRequestObjectOptions)
	{
		if(XMLHttpRequestObjectOptions.readyState == 4 && XMLHttpRequestObjectOptions.status == 200)
		{
			var wynik = XMLHttpRequestObjectOptions.responseText;
			alert(wynik);
			pobierzVlany();
		}
	}
}
var XMLHttpRequestObjectOptions = getXMLHttpRequestObjectOptions();
