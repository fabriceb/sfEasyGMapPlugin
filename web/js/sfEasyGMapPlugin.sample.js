// Sample 2 function
var moveToMarker = function ()
{
  var darwin = new google.maps.LatLng(-12.461334, 130.841904);
  map.set_zoom(13);
  map.set_center(darwin);
  
  gmapSample_AddConsoleLine("You just click on Darwin's marker !");
}


var gmapSample_Toggle = function (id)
{
  var current_display = document.getElementById(id).style.display;

  if (current_display == 'block')
  {
    document.getElementById(id).style.display = 'none';
  }
  else
  {
    document.getElementById(id).style.display = 'block';
  }
  
  return false;
}

var gmapSample_AddConsoleLine = function (content)
{
  var date = new Date();
  var time = date.toLocaleTimeString();
  var console = document.getElementById('console_div');
  var inner_console = document.getElementById('console_div').innerHTML;
  var line = '<div class="line">' + time + ' <span class="begin-line">></span>' + content + '</div>';
  
  console.innerHTML = line + inner_console;
  
  return false;
}
