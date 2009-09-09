<?php

if (!class_exists('sfCache'))
{
  class sfCache
  {
    
  }
}
/**
 * A fake cache class to be able to test the GMapClient class
 * @author Fabrice Bernhard
 */

class GMapClientTestCache extends sfCache
{
  public function get($key, $default = null)
  {
    $format = substr($key,0,3);
    switch($format)
    {
      case 'csv':
        return '200,8,48.8537950,2.3369433';
        break;
      case 'xml':
        return '<?xml version="1.0" encoding="UTF-8" ?>
<kml xmlns="http://earth.google.com/kml/2.0"><Response>
  <name>60 rue de Seine, Paris</name>
  <Status>
    <code>200</code>
    <request>geocode</request>
  </Status>
  <Placemark id="p1">

    <address>60 Rue de Seine, 75006 Paris, France</address>
    <AddressDetails Accuracy="8" xmlns="urn:oasis:names:tc:ciq:xsdschema:xAL:2.0"><Country><CountryNameCode>FR</CountryNameCode><CountryName>France</CountryName><AdministrativeArea><AdministrativeAreaName>Île-de-France</AdministrativeAreaName><SubAdministrativeArea><SubAdministrativeAreaName>Paris</SubAdministrativeAreaName><Locality><LocalityName>Paris</LocalityName><Thoroughfare><ThoroughfareName>60 Rue de Seine</ThoroughfareName></Thoroughfare><PostalCode><PostalCodeNumber>75006</PostalCodeNumber></PostalCode></Locality></SubAdministrativeArea></AdministrativeArea></Country></AddressDetails>
    <ExtendedData>
      <LatLonBox north="48.8569426" south="48.8506474" east="2.3400909" west="2.3337957" />
    </ExtendedData>

    <Point><coordinates>2.3369433,48.8537950,0</coordinates></Point>
  </Placemark>
</Response></kml>';
        break;
      case 'json':
      default:
        return '{
  "name": "60 rue de Seine, Paris",
  "Status": {
    "code": 200,
    "request": "geocode"
  },
  "Placemark": [ {
    "id": "p1",
    "address": "60 Rue de Seine, 75006 Paris, France",
    "AddressDetails": {"Country": {"CountryNameCode": "FR","CountryName": "France","AdministrativeArea": {"AdministrativeAreaName": "Île-de-France","SubAdministrativeArea": {"SubAdministrativeAreaName": "Paris","Locality": {"LocalityName": "Paris","Thoroughfare":{"ThoroughfareName": "60 Rue de Seine"},"PostalCode": {"PostalCodeNumber": "75006"}}}}},"Accuracy": 8},
    "ExtendedData": {
      "LatLonBox": {
        "north": 48.8569426,
        "south": 48.8506474,
        "east": 2.3400909,
        "west": 2.3337957
      }
    },
    "Point": {
      "coordinates": [ 2.3369433, 48.8537950, 0 ]
    }
  } ]
}';
        break;
    }
  }

  public function set($key, $data, $lifetime = null)
  {
    return true;
  }
  public function has($key)
  {
    return true;
  }
  public function remove($key)
  {
    return true;
  }
  public function removePattern($pattern)
  {
    return true;
  }
  public function clean($mode = self::ALL)
  {
    return true;
  }
  public function getTimeout($key)
  {
    return null;
  }
  public function getLastModified($key)
  {
    return null;
  }

}
