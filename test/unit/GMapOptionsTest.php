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
$tab = '  ';
$separator = "\n".$tab.$tab;

$gMap->setCenter(47, 52);
$t->is($gMap->optionsToJs(), '{'.$separator.$tab.'center: new google.maps.LatLng(47, 52)'.$separator.'}', 'correct output with center set');
$gMap->setZoom(8);
$t->is($gMap->optionsToJs(), '{'.$separator.$tab.'center: new google.maps.LatLng(47, 52),'.$separator.$tab.'zoom: 8'.$separator.'}', 'correct output with zoom set');

$t->is($gMap->getJavascript(), '', 'correct output with zoom set');