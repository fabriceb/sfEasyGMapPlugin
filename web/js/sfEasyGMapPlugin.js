var set_location = function(response, status)
{
  console.log(response);
  if(status != google.maps.GeocoderStatus.OK)
  {
    alert('Oops... adress not recognized by Google !');
    return false;
  }
  
  var zoom=15;
  console.log(response[0].geometry.location_type);
  switch(response[0].geometry.location_type)
  {
    // country level
    case google.maps.GeocoderLocationType.APPROXIMATE:
      zoom=9;
      break;
    case google.maps.GeocoderLocationType.GEOMETRIC_CENTER:
      zoom=11;
      break;
    // city level
    case google.maps.GeocoderLocationType.RANGE_INTERPOLATED:
      zoom=13;
      break;
    case google.maps.GeocoderLocationType.ROOFTOP:
      zoom=14;
      break;
    default:      
      zoom=14;
      break;
  }
  
  point = response[0].geometry.location;
  console.log(point);
  
  if (!marker)
  {
    marker = new google.maps.Marker({'map': map});
  }
  marker.set_position(point);
  map.set_zoom(zoom);
  map.set_center(point);
}

var geocode_and_show = function (address)
{
  console.log(address);
  var geocoder = new google.maps.Geocoder();
  geocoder.geocode({'address': address}, set_location);
}