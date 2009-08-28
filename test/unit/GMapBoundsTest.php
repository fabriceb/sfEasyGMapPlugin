<?php
/**
 * Teste la sauvegarde d'équipes dans le backend
 * @author fabriceb
 * @since Feb 16, 2009 fabriceb
 */
include(dirname(__FILE__).'/../bootstrap/unit.php');
//$app='frontend';
//include(dirname(__FILE__).'/../bootstrap/functional.php');



$lat = 48.856536;
$lng =  2.339307;
$zoom = 11;

$pix = GMapCoord::fromLatToPix($lat, $zoom);
$ne_lat = GMapCoord::fromPixToLat($pix - 150, $zoom);
$sw_lat = GMapCoord::fromPixToLat($pix + 150, $zoom);

$pix = GMapCoord::fromLngToPix($lng, $zoom);
$sw_lng = GMapCoord::fromPixToLng($pix - 150, $zoom);
$ne_lng = GMapCoord::fromPixToLng($pix + 150, $zoom);

$bounds = new GMapBounds(new GMapCoord($sw_lat,$sw_lng),new GMapCoord($ne_lat,$ne_lng));

$t = new lime_test(15, new lime_output_color());

$t->diag('GMapBounds test');

$t->diag('->__toString Test');
$t->is($bounds->__toString(),'((48.7887237041, 2.23631017383), (48.9242565582, 2.44230382617))','On a déduit correctement les bounds à partir de la largeur de la carte, le centre et le zoom');

$t->diag('->getZoom Test');

$bounds_world = GMapBounds::createFromString('((-90, -180), (90, 180))');
$t->is($bounds_world->getZoom(256),0,'Pour voir le monde sur une largeur/hauteur de 256 pix, il faut un zoom 0');

$bounds_world2 = GMapBounds::createFromString('((-86, -179), (86, 179))');
$t->is($bounds_world2->getZoom(256),0,'Pour voir le monde sur une largeur/hauteur de 256 pix, il faut un zoom 0');

$bounds_paris = GMapBounds::createFromString('((48.791033113791144, 2.2240447998046875), (48.926559723513435, 2.4300384521484375))');
$t->is($bounds_paris->getZoom(300),11,'Pour voir Paris sur une largeur/hauteur de 300 pix, il faut un zoom 11');

$t->diag('->createFromString Test');
$bounds_france = GMapBounds::createFromString('((42.32606244456202, -4.921875), (51.31688050404585, 8.26171875))');
$t->is($bounds_france->getZoom(300),5,'Pour voir la France sur une largeur/hauteur de 300 pix, il faut un zoom 5');

$t->diag('->getHomothety Test');
$bounds_france = GMapBounds::createFromString('((42.391008609205045, -4.833984375), (51.37178037591737, 8.349609375))');
$bounds_twice_france = $bounds_france->getHomothety(2);
$t->is($bounds_twice_france->__toString(),'((37.9006227258, -11.42578125), (55.8621662593, 14.94140625))','France zoomed out once works');
$t->diag('->getZoomOut Test');
$bounds_twice_france = $bounds_france->getZoomOut(1);
$t->is($bounds_twice_france->__toString(),'((37.9006227258, -11.42578125), (55.8621662593, 14.94140625))','France zoomed out once works');

$t->diag('->getBoundsContainingAllBounds Test');
$bounds = GMapBounds::createFromString('((48.7887996681, 2.23631017383), (48.9243326339, 2.44230382617))');
$big_bounds = GMapBounds::getBoundsContainingAllBounds(array($bounds));
$t->is($big_bounds->__toString(),$bounds->__toString(),'le bounds qui englobe un bounds cest le meme');
$big_bounds = GMapBounds::getBoundsContainingAllBounds(array($bounds,$bounds_france));
$t->is($big_bounds->__toString(),$bounds_france->__toString(),'le bounds qui contient paris et la france cest la france');

$t->diag('->getBoundsContainingCoords Test');
$coord_1 = new GMapCoord(48.7887996681, 2.23631017383);
$coord_2 = new GMapCoord(48.9243326339, 2.44230382617);
$coord_3 = new GMapCoord(48.8, 2.4);
$bounds_12 = GMapBounds::getBoundsContainingCoords(array($coord_1,$coord_2));
$bounds_123 = GMapBounds::getBoundsContainingCoords(array($coord_1,$coord_2,$coord_3));
$t->is($bounds_12->__toString(),'((48.7887996681, 2.23631017383), (48.9243326339, 2.44230382617))', 'The minimal bounds containing the coords is the rectangle containing the two coords');
$t->is($bounds_123->__toString(),'((48.7887996681, 2.23631017383), (48.9243326339, 2.44230382617))', 'The minimal bounds containing the coords is the rectangle containing the three coords');


$t->diag('->getBoundsContainingMarkers Test');
$marker_1 = new GMapMarker(48.7887996681, 2.23631017383);
$marker_2 = new GMapMarker(48.9243326339, 2.44230382617);
$marker_3 = new GMapMarker(48.8, 2.4);
$bounds_12 = GMapBounds::getBoundsContainingMarkers(array($marker_1,$marker_2));
$bounds_123 = GMapBounds::getBoundsContainingMarkers(array($marker_1,$marker_2,$marker_3));
$t->is($bounds_12->__toString(),'((48.7887996681, 2.23631017383), (48.9243326339, 2.44230382617))', 'The minimal bounds containing the markers is the rectangle containing the two markers');
$t->is($bounds_123->__toString(),'((48.7887996681, 2.23631017383), (48.9243326339, 2.44230382617))', 'The minimal bounds containing the markers is the rectangle containing the three markers');


$t->diag('->getBoundsFromCenter Test');
$center_coord = new GMapCoord(48.856536,2.339307);
$zoom = 11;
$bounds = GMapBounds::getBoundsFromCenterAndZoom($center_coord, $zoom, 300, 300);

$t->is($bounds->__toString(),'((48.7887237041, 2.23631017383), (48.9242565582, 2.44230382617))','On a déduit correctement les bounds à partir de la largeur de la carte, le centre et le zoom');

$bounds = GMapBounds::getBoundsFromCenterAndZoom($center_coord, $zoom, 1, 1);
$t->is($bounds->__toString(),'((48.856536, 2.339307), (48.856536, 2.339307))','On a déduit correctement les bounds à partir de la largeur de la carte, le centre et le zoom');

$t->diag('Fin du test');