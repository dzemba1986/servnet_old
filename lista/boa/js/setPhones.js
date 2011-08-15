function phonesForm(obj, id, address, phone, phone2, phone3)
{
  var fields = new Object();
  fields[0] = new Object();
  fields[0]['name'] = 'phone';
  fields[0]['type'] = 'text';
  fields[0]['value'] = phone;
  fields[0]['label'] = 'Telefon #1: ';
  fields[1] = new Object();
  fields[1]['name'] = 'phone2';
  fields[1]['type'] = 'text';
  fields[1]['value'] = phone2;
  fields[1]['label'] = 'Telefon #2: ';
  fields[2] = new Object();
  fields[2]['name'] = 'phone3';
  fields[2]['type'] = 'text';
  fields[2]['value'] = phone3;
  fields[2]['label'] = 'Telefon #3: ';
  fields[3] = new Object();
  fields[3]['name'] = 'id';
  fields[3]['type'] = 'hidden';
  fields[3]['value'] = id;
  fields[3]['label'] = '';
  var vtop = getElementTopPosition(obj);
  appendForm('Modyfikacja numerów kontaktowych abonenta <b>'+address + '</b><br>', fields, 'zmień', 'ajax/setPhones.php', vtop, 400, 250, 205, true);
}
