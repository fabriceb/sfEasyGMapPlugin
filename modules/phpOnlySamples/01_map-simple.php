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

$gMap->setZoom(8);
$gMap->setCenter(-34.397, 150.644);
$gMap->setHeight('500');
$gMap->setWidth('100%');
//
// View
//
?>

<?php require_once(GMAP_LIB_PATH.'helper/GMapHelper.php'); ?>

<html>
  <head>
    <?php include_google_map_javascript_file($gMap); ?>
  </head>
  <body>

    <h1>The Map</h1>
    <?php include_map($gMap); ?>

    <br />
    <!-- Javascript included at the bottom of the page -->
    <?php include_map_javascript($gMap); ?>
  </body>
</html>