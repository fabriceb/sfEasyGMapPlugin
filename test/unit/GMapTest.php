<?php
/**
 * Teste la sauvegarde d'équipes dans le backend
 * @author fabriceb
 * @since Feb 16, 2009 fabriceb
 */
include(dirname(__FILE__).'/../bootstrap/unit.php');

$t = new lime_test(10, new lime_output_color());

$t->diag('GMap Tests');

/** @var $gMap GMap */
$gMap = new GMap();

$gMap->addMarker(
  new GMapMarker(51.245475,6.821373)
);
$gMap->addMarker(
  new GMapMarker(46.262248,6.115969)
);
$gMap->addMarker(
  new GMapMarker(48.848959,2.341577)
);
$gMap->addMarker(
  new GMapMarker(48.718952,2.219180)
);
$gMap->addMarker(
  new GMapMarker(47.376420,8.547995)
);

$t->diag('->getWidth test');
$t->is($gMap->getWidth(),512,'Correct width');
$t->is($gMap->getHeight(),512,'Correct height');
$gMap->setWidth(256);
$gMap->setHeight(128);
$t->is($gMap->getWidth(),256,'Correct width');
$t->is($gMap->getHeight(),128,'Correct height');
$gMap->setWidth(512);
$gMap->setHeight(512);

$t->diag('->getMarkersCenterCoord test');

$t->is($gMap->getMarkersCenterCoord()->__toString(),'48.7538615, 5.3835875','The center of our markers is ok');
$gMap->centerOnMarkers();
$t->is($gMap->getCenterCoord()->__toString(),'48.7538615, 5.3835875','The center of the map is ok');

$t->diag('->getMarkersFittingZoom test');

$t->is($gMap->getMarkersFittingZoom(),7,'The zoom on our markers is ok');
$gMap->zoomOnMarkers();
$t->is($gMap->getZoom(),7,'The zoom of the map is ok');

$gMap->centerAndZoomOnMarkers();
$t->is($gMap->getCenterCoord()->__toString(),'48.7538615, 5.3835875','The center of the map is ok');
$t->is($gMap->getZoom(),7,'The zoom of the map is ok');

