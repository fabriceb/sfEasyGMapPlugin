<?php
/**
 * Teste la sauvegarde d'équipes dans le backend
 * @author fabriceb
 * @since Feb 16, 2009 fabriceb
 */
include(dirname(__FILE__).'/../bootstrap/unit.php');

$t = new lime_test(15, new lime_output_color());

$t->diag('GMapGeocodedAddress Tests');
$gAddress = new GMapGeocodedAddress('60 rue de Seine, Paris');

$t->diag('->getRawAddress');
$t->is($gAddress->getRawAddress(),'60 rue de Seine, Paris','->getRawAddress ok');

require_once(dirname(__FILE__).'/../../lib/GMapClientTestCache.class.php');
$gMapClient = new GMapClient('test');
$gMapClientTestCache = new GMapClientTestCache();
$gMapClient->setCache($gMapClientTestCache);

$t->diag('->geocode');
$t->is($gAddress->geocode($gMapClient),8,'Geocoded returned accuracy 8');
$t->is($gAddress->getLat(),48.8537950,'Lat ok');
$t->is($gAddress->getLng(),2.3369433,'Lng ok');
$t->is($gAddress->getAccuracy(),8,'Accuracy ok');
$t->is($gAddress->geocodeXml($gMapClient),8,'Geocoded returned accuracy 8');
$t->is($gAddress->getLat(),48.8537950,'Lat ok');
$t->is($gAddress->getLng(),2.3369433,'Lng ok');
$t->is($gAddress->getAccuracy(),8,'Accuracy ok');
$t->is($gAddress->getGeocodedAddress(),'60 Rue de Seine, 75006 Paris, France','Normalized address ok');
$t->is($gAddress->getGeocodedCity(),'Paris','Normalized City ok');
$t->is($gAddress->getGeocodedCountry(),'France','Normalized Country ok');
$t->is($gAddress->getGeocodedCountryCode(),'FR','Normalized Country code ok');
$t->is($gAddress->getGeocodedPostalCode(),'75006','Normalized postal code ok');
$t->is($gAddress->getGeocodedStreet(),'60 Rue de Seine','Normalized street ok');
