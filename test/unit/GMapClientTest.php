<?php
/**
 * Teste la sauvegarde d'équipes dans le backend
 * @author fabriceb
 * @since Feb 16, 2009 fabriceb
 */
include(dirname(__FILE__).'/../bootstrap/unit.php');

$t = new lime_test(11, new lime_output_color());

$t->diag('GMapClient Tests');
$gMapClient = new GMapClient('test');

$t->diag('->getApiKey');
$t->is($gMapClient->getAPIKey(),'test','key returned well');

$t->diag('->setApiKey');
$gMapClient->setAPIKey('test2');
$t->is($gMapClient->getAPIKey(),'test2','key returned well');

$t->diag('->getGoogleJsUrl');
$t->is($gMapClient->getGoogleJsUrl(false),'http://www.google.com/jsapi?&key=test2','getGoogleJsUrl working');
$t->is($gMapClient->getGoogleJsUrl(true),'http://www.google.com/jsapi?&key=test2&autoload=%7B%22modules%22%3A%5B%7B%22name%22%3A%22maps%22%2C%22version%22%3A%222%22%7D%5D%7D','getGoogleJsUrl working');
$t->is($gMapClient->getGoogleJsUrl(),'http://www.google.com/jsapi?&key=test2&autoload=%7B%22modules%22%3A%5B%7B%22name%22%3A%22maps%22%2C%22version%22%3A%222%22%7D%5D%7D','getGoogleJsUrl working');

$t->diag('->setCache / getCache / hasCache');
$t->ok(!$gMapClient->hasCache(),'is not using cache');

require_once(dirname(__FILE__).'/../../lib/GMapClientTestCache.class.php');
$gMapClientTestCache = new GMapClientTestCache();
$gMapClient->setCache($gMapClientTestCache);
$t->ok($gMapClient->hasCache(),'is using cache');

$t->is($gMapClient->getCache(),$gMapClientTestCache,'getCache working');

$t->diag('->getGeocodingInfo');
$t->is($gMapClient->getGeocodingInfo('60 rue de Seine, Paris'),'200,8,48.8537950,2.3369433','Cached geocoding working');
$t->is($gMapClient->getGeocodingInfo('60 rue de Seine, Paris','xml'),'<?xml version="1.0" encoding="UTF-8" ?>
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
</Response></kml>','Cached geocoding working');
$t->is($gMapClient->getGeocodingInfo('60 rue de Seine, Paris','json'),'{
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
}','Cached geocoding working');