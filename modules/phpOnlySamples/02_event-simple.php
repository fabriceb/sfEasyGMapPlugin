<?php



//
// Initialisation
//
define('GMAP_LIB_PATH',dirname(__FILE__).'/../../lib/');
require_once(GMAP_LIB_PATH.'GMap.class.php');



//
// Controller
//
$gMap = new GMap();

$gMap->setZoom(4);
$gMap->setCenter(-25.363882, 131.044922);
$gMap->setHeight('500');
$gMap->setWidth('100%');

$marker = new GMapMarker(-25.363882, 131.044922, array('title'=>'"Hello World!"'));
$marker->addEvent(new GMapEvent('click', 'map.set_zoom(8);'));
$gMap->addMarker($marker);

$gMap->addEvent(new GMapEvent('zoom_changed', 'setTimeout(moveToDarwin, 1500);'));



//
// View
//
?>

<?php require_once(GMAP_LIB_PATH.'helper/GMapHelper.php'); ?>

<html>
  <head>
    <?php include_google_map_javascript_file($gMap); ?>
    <script type="text/javascript">
    function moveToDarwin() {
      var darwin = new google.maps.LatLng(-12.461334, 130.841904);
      map.set_center(darwin);
    }
  </script>
  </head>
  <body>

    <h1>The Map</h1>
    <?php include_map($gMap); ?>

    <br />
    <!-- Javascript included at the bottom of the page -->
    <?php include_map_javascript($gMap); ?>
  </body>
</html>
