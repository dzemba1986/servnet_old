function changeMac(obj, id, mac)
{
  if(!id || !mac)
    exit();
  var fields = new Object();
  fields[0] = new Object();
  fields[0]['name'] = 'new_mac';
  fields[0]['obj'] = document.createElement('input');
  fields[0]['obj'].setAttribute('type', 'text');
  fields[0]['obj'].setAttribute('id', 'new_mac');
  fields[0]['obj'].setAttribute('name', 'new_mac');
  fields[0]['obj'].setAttribute('value', mac);
  fields[0]['label'] = 'Nowy MAC: ';
  fields[1] = new Object();
  fields[1]['obj'] = document.createElement('input');
  fields[1]['obj'].setAttribute('type', 'hidden');
  fields[1]['obj'].setAttribute('id', 'dev_id');
  fields[1]['obj'].setAttribute('name', 'dev_id');
  fields[1]['obj'].setAttribute('value', id);
  fields[1]['label'] = '';
  fields[2] = new Object();
  fields[2]['obj'] = document.createElement('button');
  fields[2]['obj'].appendChild(document.createTextNode('8000gs script'));
  fields[2]['obj'].onclick = function () {generateScript()}
  fields[2]['label'] = '';
  var vtop = getElementTopPosition(obj);
  appendForm2('Modyfikacja MAC urządzenia <br>', fields, 'zmień', 'ajax/changeMac.php', vtop, 400, 250, 140, true);
}
function generateScript()
{
    var port = document.getElementsByName('uplink_parent_select[0]')[0].value;
    var old_mac = document.getElementById('mac').value;
    var new_mac = document.getElementById('new_mac').value;
    var alink = 'dev/8000GS/change_mac.php?mac=' + old_mac + '&port=' + port.substring(1) + '&mac2=' + new_mac;
    window.open(alink, 'add_internet');
}
