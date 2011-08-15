function check()
{
  var sub_button = document.getElementById('zapisz');
  var start_date = document.getElementById('start_date');
  var ara_id = document.getElementById('ara_id');
  var ulic = document.getElementById('ulic');
  var blok = document.getElementById('blok');
  var mieszkanie = document.getElementById('mieszkanie');
  var klatka = document.getElementById('klatka');
  var other_name = document.getElementById('other_name');
  var phone = document.getElementById('phone');
  var phone2 = document.getElementById('phone2');
  var phone3 = document.getElementById('phone3');
  var mac = document.getElementById('mac');
  var result = testStartDate(start_date, sub_button);
  if(!testAra(ara_id))
  {
    result = false;
  }
  if(!testBlok(blok))
  {
    result = false;
  }
  if(!testUlic(ulic))
  {
    result = false;
  }
  if(!testMieszkanie(mieszkanie))
  {
    result = false;
  }
  if(!testKlatka(klatka))
  {
    result = false;
  }
  if((mieszkanie.value && klatka.value)||(!mieszkanie.value && !klatka.value))
  {
    mieszkanie.style.backgroundColor="red";
    klatka.style.backgroundColor="red";
    result = false;
  }
  if(!testOtherName(other_name))
  {
    result = false;
  }
  if(!testPhone(phone))
  {
    result = false;
  }
  if(!testPhone(phone2))
  {
    result = false;
  }
  if(!testPhone(phone3))
  {
    result = false;
  }
  if(!testMac(mac))
  {
    result = false;
  }
  if(result)
    setSubmit(true, sub_button);
  else
    setSubmit(false, sub_button);
}
function checkSearch()
{
  var sub_button = document.getElementById('szukaj');
  var od_date = document.getElementById('od');
  var do_date = document.getElementById('do');
  var result = testLongDate(od_date);
  if(!testLongDate(do_date))
  {
    result = false;
  }
  if(result)
    setSubmit(true, sub_button);
  else
    setSubmit(false, sub_button);
}
function setSubmit(active, sub_button)
{
  if(active)
    sub_button.disabled=false;
  else
    sub_button.disabled=true;
}
function testStartDate(field)
{
  var value = field.value;
  var result = value.match(/^(((0[1-9])|([1-2][0-9])|(3[01]))\.((0[1-9])|(1[0-2]))\.([0-9][0-9]))$/);
  if(result)
  {
    field.style.backgroundColor="white";
    return true;
  }
  else
  {
    field.style.backgroundColor="red";
    return false;
  }
}
function testAra(field)
{
  var value = field.value;
  var result = value.match(/^\b[0-9]{5}\b$/);
  if(result)
  {
    field.style.backgroundColor="white";
    return true;
  }
  else
  {
    field.style.backgroundColor="red";
    return false;
  }
}
function testBlok(field)
{
  var value = field.value;
  var result = value.match(/^\b[0-9a-ząćęłńóśźżĄĆĘŁŃÓŚŹŻ]{1,10}\b$/i);
  if(result)
  {
    field.style.backgroundColor="white";
    return true;
  }
  else
  {
    field.style.backgroundColor="red";
    return false;
  }
}
function testMieszkanie(field)
{
  var value = field.value;
  var result = value.match(/^\b[0-9a-ząćęłńóśźżĄĆĘŁŃÓŚŹŻ]{0,10}\b$/i);
  if(!value || result)
  {
    field.style.backgroundColor="white";
    return true;
  }
  else
  {
    field.style.backgroundColor="red";
    return false;
  }
}
function testKlatka(field)
{
  var value = field.value;
  var result = value.match(/^\b[0-9a-ząćęłńóśźżĄĆĘŁŃÓŚŹŻ]{0,10}\b$/i);
  if(!value || result)
  {
    field.style.backgroundColor="white";
    return true;
  }
  else
  {
    field.style.backgroundColor="red";
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
    return true;
  }
  else
  {
    field.style.backgroundColor="red";
    return false;
  }
}
function testOtherName(field)
{
  var value = field.value;
  var result = value.match(/^[0-9a-ząćęłńóśźżĄĆĘŁŃÓŚŹŻ.\/&,\s]{0,40}$/i);
  if(!value || result)
  {
    field.style.backgroundColor="white";
    return true;
  }
  else
  {
    field.style.backgroundColor="red";
    return false;
  }
}
function testMac(field)
{
  var value = field.value;
  var result = value.match(/^\b(([0-9a-fA-F]{2}):){5}([0-9a-fA-F]{2})\b$/);
  if(!value || result)
  {
    field.style.backgroundColor="white";
    return true;
  }
  else
  {
    field.style.backgroundColor="red";
    return false;
  }
}
function testUlic(field)
{
  var value = field.value;
  var result = value.match(/^\b[0-9]*\b$/);
  if(result)
  {
    field.style.backgroundColor="white";
    return true;
  }
  else
  {
    field.style.backgroundColor="red";
    return false;
  }
}
function testLongDate(field)
{
  var value = field.value;
  var result = value.match(/^((2[0-9][0-9][0-9])-((0[1-9])|(1[0-2]))-((0[1-9])|([1-2][0-9])|(3[01])))$/);
  if(result)
  {
    field.style.backgroundColor="white";
    return true;
  }
  else
  {
    field.style.backgroundColor="red";
    return false;
  }
}
