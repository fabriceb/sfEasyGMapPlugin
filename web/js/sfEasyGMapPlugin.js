geocode_and_show = function (address)
{
  geocoder = new google.maps.ClientGeocoder();
  geocoder.setBaseCountryCode('FR');  
  geocoder.getLocations(address,set_location);
}
function set_location(response)
{
  if(response.Status.code != 200)
  {
    alert('Oops... adress not recognized by Google !');
    return false;
  }
  
  var zoom=15;
  switch(response.Placemark[0].AddressDetails.Accuracy)
  {
    case 0:
      zoom=1;
      break;
    // country level
    case 1:
      zoom=4;
      break;
    case 2:
      zoom=7;
      break;
    case 3:
      zoom=9;
      break;
    // city level
    case 4:
      zoom=11;
      break;
    case 5:
      zoom=12;
      break;
    case 6:
      zoom=13;
      break;
    default:      
      zoom=14;
      break;
  }
  
  point = new google.maps.LatLng(response.Placemark[0].Point.coordinates[1], response.Placemark[0].Point.coordinates[0]);
  
  map.setCenter(point, zoom);
}