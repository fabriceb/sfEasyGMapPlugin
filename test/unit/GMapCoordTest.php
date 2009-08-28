<?php
/**
 * Teste la sauvegarde d'équipes dans le backend
 * @author fabriceb
 * @since Feb 16, 2009 fabriceb
 */
include(dirname(__FILE__).'/../bootstrap/unit.php');
//$app='frontend';
//include(dirname(__FILE__).'/../bootstrap/functional.php');


$t = new lime_test(289, new lime_output_color());

$t->diag('GMapCoords Tests');

for ($zoom=0; $zoom<15;$zoom += 3)
{
  for($lat=90; $lat>=-90; $lat-=10)
  {
    $t->is(GMapCoord::fromPixToLat(GMapCoord::fromLatToPix($lat, $zoom),$zoom),(float)$lat,'les projections mercator sur les latitudes marchent');
  }
  for($lng=-180; $lng<=180; $lng+=10)
  {
    $t->is(GMapCoord::fromPixToLng(GMapCoord::fromLngToPix($lng, $zoom),$zoom),(float)$lng,'les projections mercator sur les longitudes marchent');
  }
}

$lat = 0;
$lng =  0;
$zoom = 0;

$pix = GMapCoord::fromLatToPix($lat, $zoom);
$t->is($pix,128,'Latitude 0 is at the middle of the map for zoom 0');
$pix = GMapCoord::fromLngToPix($lng, $zoom);
$t->is($pix,128,'Longitude 0 is at the middle of the map for zoom 0');


$lat = 0;
$lng =  -180;
$zoom = 12;
$pix = GMapCoord::fromLatToPix($lat, $zoom);
$t->is($pix,256*pow(2,$zoom-1),'Latitude 0 is at the middle of the map whatever the zoom');
$pix = GMapCoord::fromLngToPix($lng, $zoom);
$t->is($pix,0,'Longitude -180 is at the left of the map whathever the zoom');


$coord_paris = new GMapCoord(48.857939,2.346611);
$coord_le_mans = new GMapCoord(48.007381,0.202131);
$t->is(round(GMapCoord::distance($coord_le_mans, $coord_paris)),257,'Approximate distance between Le Mans and Paris is 257');

$coord_luxembourg = new GMapCoord(48.846559,2.340689);
$coord_saint_michel = new GMapCoord(48.853717,2.344015);
$t->is(round(GMapCoord::distance($coord_luxembourg, $coord_saint_michel)*1000),879,'Approximate distance between RER Luxembourg and Saint-Michel is 879 meters');


$coord_luxembourg = new GMapCoord(48.846559,2.340689);
$coord_saint_michel = new GMapCoord(48.853717,2.344015);
$center_of_the_world = new GMapCoord(0,0);
$bounds_paris = GMapBounds::createFromString('((48.791033113791144, 2.2240447998046875), (48.926559723513435, 2.4300384521484375))');
$t->ok($coord_saint_michel->isInsideBounds($bounds_paris),'Saint-Michel Notre-Dame is in Paris');
$t->ok($coord_luxembourg->isInsideBounds($bounds_paris),'RER Luxembourg is in Paris');
$t->ok(!$center_of_the_world->isInsideBounds($bounds_paris),'Center of the world is not in Paris (amazingly)');
