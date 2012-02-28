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
function getAjaxData(parameters, target, method)
{
  var response_object = getXMLHttpRequestObject();
  if(response_object)
  {
    var url = target+ '?';
    for(key in parameters)
      url= url + key + '=' +parameters[key]+'&';
    response_object.open(method, url, false);
    response_object.send(null);
    return getSyncResponse(response_object);
  }
  return false;
}
function getSyncResponse(response_object)
{

	if(response_object)
	{
		if(response_object.readyState == 4 && response_object.status == 200)
		{
			var result = response_object.responseXML;
			if(!result || !result.documentElement)
			{
				if(response_object.responseText)
                                  return response_object.responseText;
                                else
                                  alert("nic nie doszlo ");
			}
			else if(result.documentElement.nodeName == "parsererror")
			{
				alert("blad parsera "+response_object.responseText);
			}
			else
			{	
	                  return result.documentElement.childNodes;
			}
		}
	}
        return null;
}
function getDocHeight() {
  var D = document;
  return Math.max(
      Math.max(D.body.scrollHeight, D.documentElement.scrollHeight),
      Math.max(D.body.offsetHeight, D.documentElement.offsetHeight),
      Math.max(D.body.clientHeight, D.documentElement.clientHeight)
      );
}
function getWindowHeight()
{
  var myHeight = 0;
  if( typeof( window.innerWidth ) == 'number' ) {
    //Non-IE
    myHeight = window.innerHeight;
  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
    //IE 6+ in 'standards compliant mode'
    myHeight = document.documentElement.clientHeight;
  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
    //IE 4 compatible
    myHeight = document.body.clientHeight;
  }
  return myHeight;
}
function appendForm2(title, fields, submit_text, target, top_position, left, width, height, reload)
{
  var bgdiv = document.createElement('div');
  var divIdName = 'bgdiv';
  bgdiv.setAttribute('id',divIdName);
  bgdiv.style.width = "100%";
  //bgdiv.style.height = document.body.offsetHeight +'px';
  bgdiv.style.height = getDocHeight() + 'px';
  bgdiv.style.left = "0";
  bgdiv.style.top = "0";
  bgdiv.style.opacity = "0.8";
  bgdiv.style.filter = 'alpha(opacity=80)';//IE
  bgdiv.style.background = "#000000";
  bgdiv.style.zIndex= '999';
  bgdiv.style.position = "absolute";
  document.body.appendChild(bgdiv); 	
  document.body.scroll ="no";
  document.body.style.overflow = 'hidden';
  
  var newdiv = document.createElement('div');
  var divIdName = 'newdiv';
  newdiv.setAttribute('id',divIdName);
  newdiv.style.width = width+'px';
  newdiv.style.height = height+'px';
  newdiv.style.left = left + "px";
  var vscroll = (document.all ? document.scrollTop : window.pageYOffset);
  var client_height = getWindowHeight();
  if((top_position - vscroll > height) && ((top_position - vscroll) < (client_height - height-50)))
  {
    newdiv.style.top = top_position+ 'px';
  }
  else
    if(top_position - vscroll <= height)
      newdiv.style.top = (vscroll + height) + "px";
    else
      newdiv.style.top = (vscroll + client_height - height -30) + "px";
  //alert('top: '+ top_position +'\nvscroll ' + vscroll + '\nclient_height: '+client_height+ '\nheight: ' +height);
  newdiv.style.padding = "10px";
  newdiv.style.opacity = "1";
  newdiv.style.zIndex= '1000';
  newdiv.style.position = "absolute";
  newdiv.style.background = "#FFF";
  newdiv.style.border = "4px solid #000";
  newdiv.style.fontFamily = "Verdana";
  newdiv.style.fontSize = "14px";
  newdiv.innerHTML = title;
  var table = document.createElement("table");
  table.style.border = '0px none black';
  newdiv.appendChild(document.createElement("br"));
  for(key in fields)
  {
    var row = document.createElement("tr");
    var col_name = document.createElement("td");
    col_name.style.border = '0px none black';
    var col_val = document.createElement("td")
    col_val.style.border = '0px none black';  
    col_val.appendChild(fields[key]['obj']);
    col_name.appendChild(document.createTextNode(fields[key]['label']));
    row.appendChild(col_name);
    row.appendChild(col_val);
    table.appendChild(row);
  }
  newdiv.appendChild(table);
  b_close = document.createElement('button');
  b_close.innerHTML = 'zamknij';
  b_close.setAttribute('id','newdiv_b_close');
  addEvent(b_close, "click", function (){removeForm(false);});
  newdiv.appendChild(document.createElement("br"));
  newdiv.appendChild(b_close);
  append = document.createElement('button');
  append.innerHTML = submit_text;
  append.setAttribute('id','newdiv_b_append');
  append.onclick = function (){sendForm(newdiv, target, reload);};
  newdiv.appendChild(append);
  var result = document.createElement('div');
  newdiv.appendChild(result);
  document.body.appendChild(newdiv); 	
}
//fields = array(name=>'', value=>'', type=>'', label=>'')
//dla select type='select' a value to tablica value=>label
function appendForm(title, fields, submit_text, target, top_position, left, width, height, reload)
{
  var bgdiv = document.createElement('div');
  var divIdName = 'bgdiv';
  bgdiv.setAttribute('id',divIdName);
  bgdiv.style.width = "100%";
  //bgdiv.style.height = document.body.offsetHeight +'px';
  bgdiv.style.height = getDocHeight() + 'px';
  bgdiv.style.left = "0";
  bgdiv.style.top = "0";
  bgdiv.style.opacity = "0.8";
  bgdiv.style.filter = 'alpha(opacity=80)';//IE
  bgdiv.style.background = "#000000";
  bgdiv.style.zIndex= '999';
  bgdiv.style.position = "absolute";
  document.body.appendChild(bgdiv); 	
  document.body.scroll ="no";
  document.body.style.overflow = 'hidden';
  
  var newdiv = document.createElement('div');
  var divIdName = 'newdiv';
  newdiv.setAttribute('id',divIdName);
  newdiv.style.width = width+'px';
  newdiv.style.height = height+'px';
  newdiv.style.left = left + "px";
  var vscroll = (document.all ? document.scrollTop : window.pageYOffset);
  var client_height = getWindowHeight();
  if((top_position - vscroll > height) && ((top_position - vscroll) < (client_height - height-50)))
  {
    newdiv.style.top = top_position+ 'px';
  }
  else
    if(top_position - vscroll <= height)
      newdiv.style.top = (vscroll + height) + "px";
    else
      newdiv.style.top = (vscroll + client_height - height -30) + "px";
  //alert('top: '+ top_position +'\nvscroll ' + vscroll + '\nclient_height: '+client_height+ '\nheight: ' +height);
  newdiv.style.padding = "10px";
  newdiv.style.opacity = "1";
  newdiv.style.zIndex= '1000';
  newdiv.style.position = "absolute";
  newdiv.style.background = "#FFF";
  newdiv.style.border = "4px solid #000";
  newdiv.style.fontFamily = "Verdana";
  newdiv.style.fontSize = "14px";
  newdiv.innerHTML = title;
  var table = document.createElement("table");
  table.style.border = '0px none black';
  newdiv.appendChild(document.createElement("br"));
  for(key in fields)
  {
    var row = document.createElement("tr");
    var col_name = document.createElement("td");
    col_name.style.border = '0px none black';
    var col_val = document.createElement("td")
    col_val.style.border = '0px none black';  
    if(fields[key]['type']!='select')
    {
      var field = document.createElement('input');
      field.setAttribute('type',fields[key]['type']);
      field.setAttribute('value',fields[key]['value']);
    }
    else
    {
      var field = document.createElement('select');
      for(key2 in fields[key]['value'])
      {
        var option = document.createElement('option');
        option.setAttribute('value', key2);
        option.appendChild(document.createTextNode(fields[key]['value'][key2]));
        field.appendChild(option);
      }
    }
    field.setAttribute('name',fields[key]['name']);
    field.setAttribute('id',fields[key]['name']);
    col_val.appendChild(field);
    if(fields[key]['type']!='hidden')
    {
      col_name.appendChild(document.createTextNode(fields[key]['label']));
    }
    row.appendChild(col_name);
    row.appendChild(col_val);
    table.appendChild(row);
  }
  newdiv.appendChild(table);
  b_close = document.createElement('button');
  b_close.innerHTML = 'zamknij';
  b_close.setAttribute('id','newdiv_b_close');
  b_close.onclick = function (){removeForm(false);};
  newdiv.appendChild(document.createElement("br"));
  newdiv.appendChild(b_close);
  append = document.createElement('button');
  append.innerHTML = submit_text;
  append.onclick = function (){sendForm(newdiv, target, reload);};
  newdiv.appendChild(append);
  var result = document.createElement('div');
  newdiv.appendChild(result);
  document.body.appendChild(newdiv); 	
}
function removeForm(reload)
{
  var newdiv = document.getElementById('newdiv');
  var bgdiv = document.getElementById('bgdiv');
  document.body.removeChild(bgdiv);
  document.body.removeChild(newdiv);
  document.body.scroll ="yes";
  document.body.style.overflow = 'scroll';
  if(reload)
    window.location.reload();
}
function sendForm(obj, target, reload)
{
  var parent_node = obj;
  var parameters = new Array();
  inputs = document.getElementsByTagName('input');
  var counter = 0;
  for(key in inputs)
  {
    if(inputs[key].parentNode && inputs[key].parentNode.parentNode && inputs[key].parentNode.parentNode.parentNode && inputs[key].parentNode.parentNode.parentNode.parentNode == parent_node && (inputs[key].type!='radio' || inputs[key].checked==true))
    {
      parameters[inputs[key].name] = new Object();
      parameters[inputs[key].name] = inputs[key].value;
    //  alert(inputs[key].name + ' ' +  inputs[key].value);
      counter++;
    }
  }
  inputs = document.getElementsByTagName('select');
  for(key in inputs)
  {
    if(inputs[key].parentNode && inputs[key].parentNode.parentNode && inputs[key].parentNode.parentNode.parentNode && inputs[key].parentNode.parentNode.parentNode.parentNode == parent_node)
    {
      parameters[inputs[key].name] = new Object();
      parameters[inputs[key].name] = inputs[key].value;
    //  alert(inputs[key].name + ' ' +  inputs[key].value);
      counter++;
    }
  }
  inputs = document.getElementsByTagName('textarea');
  for(key in inputs)
  {
    if(inputs[key].firstChild && inputs[key].parentNode && inputs[key].parentNode.parentNode && inputs[key].parentNode.parentNode.parentNode && inputs[key].parentNode.parentNode.parentNode.parentNode == parent_node)
    {
      parameters[inputs[key].name] = new Object();
      parameters[inputs[key].name] = inputs[key].value;
    //  alert(inputs[key].name + ' ' +  inputs[key].value);
      counter++;
    }
  }
  var response = getAjaxData(parameters, target, 'POST');
  printResponse(parent_node.lastChild, response);
  var b_close = document.getElementById('newdiv_b_close');
  b_close.onclick = false;
  b_close.onclick = function (){removeForm(reload);};
  //removeForm();
}
function printResponse(obj, response)
{
  while(obj.firstChild)
    obj.removeChild(obj.firstChild);
  obj.appendChild(document.createTextNode(response));
}
function getElementTopPosition(e)
{
  var y=0;
  while(e)
  {
    y+=e.offsetTop+e.clientTop;
    e=e.offsetParent;
  }
  return y;
}
function addEvent(obj, evType, fn) {
  if (obj.addEventListener) {
    obj.addEventListener(evType, fn, false);
    return true;
  } else if (obj.attachEvent) {
    var r = obj.attachEvent("on" + evType, fn);
    return r;
  } else {
    alert("Handler could not be attached");
  }
}

