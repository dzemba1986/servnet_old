function addressForm(obj, id, osiedla)
{
  var fields = new Object();
  fields[0] = new Object();
  fields[0]['name'] = 'osiedle';
  fields[0]['type'] = 'select';
  fields[0]['value'] = osiedla;
  fields[0]['label'] = 'Osiedle';
  fields[1] = new Object();
  fields[1]['name'] = 'blok';
  fields[1]['type'] = 'text';
  fields[1]['value'] = '';
  fields[1]['label'] = 'Blok';
  fields[2] = new Object();
  fields[2]['name'] = 'mieszkanie';
  fields[2]['type'] = 'text';
  fields[2]['value'] = '';
  fields[2]['label'] = 'Mieszkanie';
  fields[3] = new Object();
  fields[3]['name'] = 'klatka';
  fields[3]['type'] = 'text';
  fields[3]['value'] = '';
  fields[3]['label'] = 'Klatka';
  fields[4] = new Object();
  fields[4]['name'] = 'other_name';
  fields[4]['type'] = 'text';
  fields[4]['value'] = '';
  fields[4]['label'] = 'Inna nazwa';
  fields[5] = new Object();
  fields[5]['name'] = 'reason';
  fields[5]['type'] = 'radio';
  fields[5]['value'] = 1;
  fields[5]['label'] = 'Wpisany adres jest błędny';
  fields[6] = new Object();
  fields[6]['name'] = 'reason';
  fields[6]['type'] = 'radio';
  fields[6]['value'] = 2;
  fields[6]['label'] = 'Wpisany adres jest źle sformatowany';
  fields[7] = new Object();
  fields[7]['name'] = 'id';
  fields[7]['type'] = 'hidden';
  fields[7]['value'] = id;
  fields[7]['label'] = '';
  var vtop = obj.parentNode.offsetTop + 200;
  appendForm('Nowy adres i powód zmiany:', fields, 'zmień', 'ajax/changeAddress.php', vtop, 400, 320, 340, true);
}
