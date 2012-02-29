function testModForm()
{
  var sub_button = document.getElementById('save_button');
  var s_date = document.getElementById('s_date');
  var s_time = document.getElementById('s_time');
  var e_time = document.getElementById('e_time');
  var cost = document.getElementById('cost');
  var inst = document.getElementById('inst');
  var type = document.getElementById('type');
  var cause = document.getElementById('cause');
  var street = document.getElementById('street');
  var building = document.getElementById('building');
  var flat = document.getElementById('flat');
  var result = testStartDate(s_date, sub_button);
  if(!testTime(s_time, false))
    result = false;
  if(!testTime(e_time, false))
    result = false;
  if(!testCost(cost))
    result = false;
  if(!testSelect(inst, false))
    result = false;
  if(!testSelect(type, false))
    result = false;
  if(street && !testSelect(street, false))
    result = false;
  if(building && !testBuilding(building, false))
    result = false;
  if(flat && !testFlat(flat, true))
    result = false;
  if(result)
    setSubmit('',true, sub_button);
  else
    setSubmit('',false, sub_button);
}
