function setAraId(obj, id, address, ara_id)
{
  if(!ara_id)
    ara_id = '';
  var fields = new Object();
  fields[0] = new Object();
  fields[0]['name'] = 'ara_id';
  fields[0]['type'] = 'text';
  fields[0]['value'] = ara_id;
  fields[0]['label'] = 'AraID: ';
  fields[1] = new Object();
  fields[1]['name'] = 'id';
  fields[1]['type'] = 'hidden';
  fields[1]['value'] = id;
  fields[1]['label'] = '';
  var vtop = getElementTopPosition(obj);
  appendForm('Modyfikacja AraID abonenta <b>'+address+'</b><br>', fields, 'zmień', 'ajax/setAraId.php', vtop, 400, 250, 140, true);
}
