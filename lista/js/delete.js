function deleteObject(obj, id, obj_type)
{
  var fields = new Object();
  fields[0] = new Object();
  fields[0]['name'] = 'del_id';
  fields[0]['type'] = 'hidden';
  fields[0]['value'] = id;
  fields[0]['label'] = '';
  var vtop = obj.parentNode.offsetTop;
  if(obj_type=='con')
    appendForm('Usunięcie spowoduje nieodwracalną utratę danych!', fields, 'usuń', 'ajax/deleteConnection.php', vtop, 400, 250, 120, true);
  else if(obj_type=='inst')
    appendForm('Usunięcie spowoduje nieodwracalną utratę danych!', fields, 'usuń', 'ajax/deleteInstallation.php', vtop, 400, 250, 120, true);
}
