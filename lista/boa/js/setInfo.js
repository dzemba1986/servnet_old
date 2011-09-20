function setInfo(obj, id, address, info)
{
  if(!info)
    info = '';
  var fields = new Object();
  fields[0] = new Object();
  fields[0]['name'] = 'info';
  fields[0]['obj'] = document.createElement('textarea');
  fields[0]['obj'].setAttribute('id', 'info');
  fields[0]['obj'].setAttribute('name', 'info');  
  fields[0]['obj'].setAttribute('cols', '50');  
  fields[0]['obj'].setAttribute('rows', '4');  
  fields[0]['obj'].appendChild(document.createTextNode(info));
  fields[0]['label'] = 'Dod. info: ';
  fields[1] = new Object();
  fields[1]['obj'] = document.createElement('input');
  fields[1]['obj'].setAttribute('type', 'hidden');
  fields[1]['obj'].setAttribute('id', 'id');
  fields[1]['obj'].setAttribute('name', 'id');
  fields[1]['obj'].setAttribute('value', id);
  fields[1]['label'] = '';
  var vtop = getElementTopPosition(obj);
  appendForm2('Modyfikacja info podłączenia <b>'+address+'</b><br>', fields, 'zmień', 'ajax/setInfo.php', vtop, 400, 500, 180, true);
}
