function modyficationCloseForm(obj, id, installer, desc, cost, con_id)
{
  var fields = new Object();
  fields[0] = new Object();
  fields[0]['name'] = 'installer';
  fields[0]['obj'] = document.createElement('input');
  fields[0]['obj'].setAttribute('type', 'text');  
  fields[0]['obj'].setAttribute('id', 'installer');
  fields[0]['obj'].setAttribute('name', 'installer');  
  fields[0]['obj'].setAttribute('value', installer);  
  fields[0]['label'] = 'Monter';
  fields[1] = new Object();
  fields[1]['obj'] = document.createElement('select');
  fields[1]['obj'].setAttribute('id', 'fullfill');
  fields[1]['obj'].setAttribute('name', 'fullfill');  

  var option = document.createElement('option');
  fields[1]['obj'].appendChild(option);

  option = document.createElement('option');
  option.setAttribute('value', 1);
  option.appendChild(document.createTextNode('Tak'));
  fields[1]['obj'].appendChild(option);

  option = document.createElement('option');
  option.setAttribute('value', 0);
  option.appendChild(document.createTextNode('Nie'));
  fields[1]['obj'].appendChild(option);

  fields[1]['label'] = 'Wykonano';
  fields[2] = new Object();
  fields[2]['name'] = 'desc';
  fields[2]['obj'] = document.createElement('textarea');
  fields[2]['obj'].setAttribute('id', 'desc');
  fields[2]['obj'].setAttribute('name', 'desc');  
  fields[2]['obj'].setAttribute('cols', '30');  
  fields[2]['obj'].setAttribute('rows', '4');  
  fields[2]['obj'].appendChild(document.createTextNode(desc));
  fields[2]['label'] = 'Info do montażu';
  fields[3] = new Object();
  fields[3]['obj'] = document.createElement('input');
  fields[3]['obj'].setAttribute('type', 'text');  
  fields[3]['obj'].setAttribute('id', 'cost');
  fields[3]['obj'].setAttribute('name', 'cost');  
  fields[3]['obj'].setAttribute('value', cost);  
  fields[3]['label'] = 'Koszt';
  fields[4] = new Object();
  fields[4]['name'] = 'id';
  fields[4]['obj'] = document.createElement('input');
  fields[4]['obj'].setAttribute('type', 'hidden');  
  fields[4]['obj'].setAttribute('id', 'id');
  fields[4]['obj'].setAttribute('name', 'id');  
  fields[4]['obj'].setAttribute('value', id);  
  fields[4]['label'] = '';
  fields[5] = new Object();
  fields[5]['name'] = 'id';
  fields[5]['obj'] = document.createElement('input');
  fields[5]['obj'].setAttribute('type', 'hidden');  
  fields[5]['obj'].setAttribute('id', 'con_id');
  fields[5]['obj'].setAttribute('name', 'con_id');  
  fields[5]['obj'].setAttribute('value', con_id);  
  fields[5]['label'] = '';
  var vtop = obj.parentNode.offsetTop + 200;
  appendForm2('Zamykanie zdarzenia montażu<br>', fields, 'Zamknij zdarzenie', 'ajax/closeModyfication.php', vtop, 400, 310, 260, true);
  var form = document.getElementById('socket_form');
  var append_button = document.getElementById('newdiv_b_append');
  var ff = document.getElementById('fullfill');
  var inst = document.getElementById('installer');
  var cost_f = document.getElementById('cost');
  addEvent(ff, "change", function() {checkModyfF()});
  addEvent(inst, "keyup" , function() {checkModyfF()});
  addEvent(cost_f, "keyup" , function() {checkModyfF()});
  checkModyfF();



    addEvent(append_button, "click", function () {alert("Zamknięto montaż")});
  }
  function checkModyfF()
{
  var ff = document.getElementById('fullfill');
  var inst = document.getElementById('installer');
  var cost_f = document.getElementById('cost');
  inst.disabled = true;
  var ab = document.getElementById('newdiv_b_append');
  var ff_disable = false;
  var inst_disable = testInstaller(inst);
  var cost_disable = !testCost(cost_f);
  if(ff.value!='1' && ff.value!='0')
  {
    ff.style.backgroundColor="red";
    ff_disable = true;
  }
  else
  {
    ff.style.backgroundColor="white";
    ff_disable = false;
  }
  if(inst.value=='')
  {
    inst.style.backgroundColor="red";
    inst_disable = true;
  }
  else
  {
    inst.style.backgroundColor="white";
      inst_disable = false;
  }
  if(ff_disable==true || inst_disable==true || cost_disable==true)
  {
    ab.disabled = true;
    ab.style.backgroundColor="red";
  }
  else
  {
    ab.style.backgroundColor="white";
    ab.disabled = false;
  }
}
