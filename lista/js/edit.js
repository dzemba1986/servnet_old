function testStartDate(field, sub_button)
{
  var value = field.value;
  var result = value.match(/^(((0[1-9])|([1-2][0-9])|(3[01]))\.((0[1-9])|(1[0-2]))\.([0-9][0-9]))$/);
  if(result)
  {
    field.style.backgroundColor="white";
  }
  else
  {
    field.style.backgroundColor="red";
  }
    setSubmit(field, result, sub_button);
    return result;
}
function testSwitch(field, sub_button)
{
  var value = field.value;
  var result = value.match(/^[0-9a-ząćęłńóśźżĄĆĘŁŃÓŚŹŻ_]*$/i);
  if(!value || result)
  {
    field.style.backgroundColor="white";
    setSubmit(field, true, sub_button);
    return true;
  }
  else
  {
    field.style.backgroundColor="red";
    setSubmit(field, false, sub_button);
    return false;
  }
}
function testPhone(field)
{
  var value = field.value;
  var result = value.match(/^[0-9\s]{3,14}$/);
  if(!value || result)
  {
    field.style.backgroundColor="white";
    setSubmit(field, true, '');
  }
  else
  {
    field.style.backgroundColor="red";
    setSubmit(field, false, '');
  }
}
function testSpeed(field)
{
  var value = field.value;
  var result = value.match(/^\b[0-9]*\b$/i);
  if(result)
  {
    field.style.backgroundColor="white";
    setSubmit(field, true, '');
  }
  else
  {
    field.style.backgroundColor="red";
    setSubmit(field, false, '');
  }
}
function setSubmit(field, active, sub_button)
{
  if(!sub_button)
  {
    for (var i=0; i<field.parentNode.parentNode.childNodes.length; i++)
    {
      for(var j=0; j<field.parentNode.parentNode.childNodes[i].childNodes.length; j++)
      {
        var tmp = field.parentNode.parentNode.childNodes[i].childNodes[j];
        if(tmp && tmp.type=="submit")
        {
          sub_button = tmp;
          break;
        }
      }
    }
  }
  sub_button.style.backgroundColor="red";
  if(active)
  {
    if(field)
      field.style.backgroundColor="white";
    sub_button.disabled=false;

  }
  else
  {
    if(field)
      field.style.backgroundColor="red";
    sub_button.disabled=true;
  }
}
function testDate(field, sub_button)
{
  var value = field.value;
  var result = value.match(/^(((0[1-9])|([1-2][0-9])|(3[01]))\.((0[1-9])|(1[0-2]))\.([0-9][0-9]))$/);
  if(!value || result)
  {
    field.style.backgroundColor="white";
    setSubmit(field, true, sub_button);
    return true;
  }
  else
  {
    field.style.backgroundColor="red";
    setSubmit(field, false, sub_button);
    return false;
  }
}
function testTime(field)
{
  var value = field.value;
  var result = value.match(/^((([0-1][0-9])|(2[1-3])):([0-5][0-9]))$/);
  if(!value || result)
  {
    field.style.backgroundColor="white";
    setSubmit(field, true, '');
    return true;
  }
  else
  {
    field.style.backgroundColor="red";
    setSubmit(field, false, '');
    return false;
  }
}
function testInstallDateTime(sector)
{
  var date_field = document.getElementById('installation_date_'+sector);
  var time_field = document.getElementById('installation_time_'+sector);
  if(testTime(time_field) && testDate(date_field))
    setSubmit(date_field, true, '');
  else
    setSubmit(date_field, false, '');
}
function testAddress(field)
{
  var value = field.value;
  var result = value.match(/^((O((P(L|[a-z1-9]))|(Z)|(K)|(WW))|WILCZAK|NARAMOWICKA)[^_\s]{1}.*)$/);
  if(result)
  {
    field.style.backgroundColor="white";
    setSubmit(field, true, '');
  }
  else
  {
    field.style.backgroundColor="red";
    setSubmit(field, false, '');
  }
}
function testMac(field, sub_button)
{
  var value = field.value;
  var result = value.match(/^((([0-9a-fA-F]{2}):){5}([0-9a-fA-F]{2}))$/);
  if(!value || result)
  {
    field.style.backgroundColor="white";
    setSubmit(field, true, sub_button);
    return true;
  }
  else
  {
    field.style.backgroundColor="red";
    setSubmit(field, false, sub_button);
    return false;
  }
}
function testInstaller(field, sub_button)
{
  var value = field.value;
  var result = value.match(/^[a-zA-Z\s&ąćęłńóśźżĄĆĘŁŃÓŚŹŻ.]{2,}$/);
  if(result)
  {
    field.style.backgroundColor="white";
    setSubmit(field, true, sub_button);
    return true;
  }
  else
  {
    field.style.backgroundColor="red";
    setSubmit(field, false, sub_button);
    return false;
  }
}
function testWire(field, sub_button)
{
  var value = field.value;
  var result = value.match(/^\b[0-9]*\b$/);
  if(result)
  {
    field.style.backgroundColor="white";
    setSubmit(field, true, sub_button);
    return true;
  }
  else
  {
    field.style.backgroundColor="red";
    setSubmit(field, false, sub_button);
    return false;
  }
}
function testAddWireForm(service)
{
  var sub_button = document.getElementById('save_button');
  var wire_date = document.getElementById('wire_installation_date_1');
  var wire_length = document.getElementById('wire_length_1');
  var wire_installer = document.getElementById('wire_installer_1');
  var result = testStartDate(wire_date, sub_button);
  var switch_ = document.getElementById('switch_1');
  if(!testWire(wire_length, sub_button))
    result = false;
  if(!testInstaller(wire_installer, sub_button))
    result = false;
  if(!testSwitch(switch_, sub_button))
    result = false;
  if(result)
    setSubmit('',true, sub_button);
  else
    setSubmit('',false, sub_button);

}
function testAddSocketForm(service)
{
  var sub_button = document.getElementById('save_button');
  var socket_date = document.getElementById('socket_installation_date_1');
  var socket_installer = document.getElementById('socket_installer_1');
  var result = testStartDate(socket_date, sub_button);
  if(!testInstaller(socket_installer, sub_button))
    result = false;
  if(service=='net')
  {
    var mac = document.getElementById('mac_1');
    if(!testMac(mac, sub_button))
      result = false;
  }
  else if(service=='phone')
  {
    var service_activation = document.getElementById('service_activation_1');
    if(!testDate(service_activation, sub_button))
      result = false;
  }
  if(result)
    setSubmit('',true, sub_button);
  else
    setSubmit('',false, sub_button);
}
function testEditWireForm(footer)
{
  var sub_button = document.getElementById('wire_save_button_'+footer);
  var wire_date = document.getElementById('wire_installation_date_'+footer);
  var wire_length = document.getElementById('wire_length_'+footer);
  var wire_installer = document.getElementById('wire_installer_'+footer);
  var result = testStartDate(wire_date, sub_button);
  if(!testWire(wire_length, sub_button))
    result = false;
  if(!testInstaller(wire_installer, sub_button))
    result = false;
  if(result)
    setSubmit('',true, sub_button);
  else
    setSubmit('',false, sub_button);

}
function testEditSocketForm(footer)
{
  var sub_button = document.getElementById('socket_save_button_'+footer);
  var socket_date = document.getElementById('socket_installation_date_'+footer);
  var socket_installer = document.getElementById('socket_installer_'+footer);
  var result = testStartDate(socket_date, sub_button);
  if(!testInstaller(socket_installer, sub_button))
    result = false;
  if(result)
    setSubmit('',true, sub_button);
  else
    setSubmit('',false, sub_button);
}

function changedField(field)
{
  var sub_button = null;
  for (var i=0; i<field.parentNode.parentNode.childNodes.length; i++)
  {
    for(var j=0; j<field.parentNode.parentNode.childNodes[i].childNodes.length; j++)
    {
      var tmp = field.parentNode.parentNode.childNodes[i].childNodes[j];
      if(tmp && tmp.type=="submit")
      {
        sub_button = tmp;
        break;
      }
    }
  }
  sub_button.style.backgroundColor="red";
}
function setToday(ptr, today)
{
  ptr.previousSibling.value=today;
}

