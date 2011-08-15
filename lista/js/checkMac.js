function checkMac(connection)
{
  var obj = document.getElementById('mac_1');
  var mac = obj.value;
  if(connection && mac)
  {
    var parameters = new Object();
    parameters['mac'] = mac;
    parameters['id'] = connection;
    var response = getAjaxData(parameters, 'ajax/checkMac.php', 'post');
    if(response!='1')
    {
      var fields = new Object();
      fields[0] = new Object();
      fields[0]['name'] = 'id';
      fields[0]['type'] = 'hidden';
      fields[0]['value'] = connection;
      fields[0]['label'] = '';
      fields[1] = new Object();
      fields[1]['name'] = 'mac';
      fields[1]['type'] = 'hidden';
      fields[1]['value'] = mac;
      fields[1]['label'] = '';
      var vtop = obj.parentNode.offsetTop + 200;
    appendForm('Podany MAC jest zajęty, czy na pewno chcesz kontynuowć?', fields, 'Tak', 'ajax/changeMac.php', vtop, 400, 220, 110, true);
    }
    else
    {
      parameters['id'] = connection;
      var response2 =  getAjaxData(parameters, 'ajax/changeMac.php', 'post');
      window.location.reload();
    }

  }
}
function checkMacEditForm(connection)
{
  var obj = document.getElementById('mac_1');
  var mac = obj.value;
  if(connection && mac)
  {
    var parameters = new Object();
    parameters['mac'] = mac;
    parameters['id'] = connection;
    var response = getAjaxData(parameters, 'ajax/checkMac.php', 'post');
    if(response!='1')
    {
      return confirm('Podany MAC jest zajęty, czy na pewno chcesz kontynuowć?');
    }
    else
    {
      return true;
    }

  }
}
function checkMacSocketForm()
{
  var obj = document.getElementById('mac_1');
  var con_obj = document.getElementById('connection_1');
  var connection = con_obj.value;
  var mac = obj.value;
  if(connection && mac)
  {
    var parameters = new Object();
    parameters['mac'] = mac;
    parameters['id'] = connection;
    var response = getAjaxData(parameters, 'ajax/checkMac.php', 'post');
    if(response!='1')
    {
      return confirm('Podany MAC jest zajęty, czy na pewno chcesz kontynuowć?');
    }
    else
    {
      return true;
    }

  }
}
