<?php
/**
 *
 * @author fabriceb
 * @since Feb 16, 2009 fabriceb
 */
include(dirname(__FILE__).'/../bootstrap/unit.php');

$t = new lime_test(3, new lime_output_color());

$t->diag('GMap Tests');

/** @var $gMap GMap */
$gMap = new GMap();
$tab = '  ';
$new_line = "\n";
$separator = $new_line.$tab.$tab;


$gMap->setCenter(47, 52);
$t->is($gMap->optionsToJs(), '{'.$separator.$tab.'center: new google.maps.LatLng(47, 52),'.$separator.$tab.'mapTypeId: google.maps.MapTypeId.ROADMAP'.$separator.'}', 'correct output with center set');
$gMap->setZoom(8);
$t->is($gMap->optionsToJs(), '{'.$separator.$tab.'center: new google.maps.LatLng(47, 52),'.$separator.$tab.'zoom: 8,'.$separator.$tab.'mapTypeId: google.maps.MapTypeId.ROADMAP'.$separator.'}', 'correct output with zoom set');

$t->is($gMap->getJavascript(), $new_line.$tab.'var map = null;'.$new_line.$tab.'//  Call this function when the page has been loaded'.$new_line.$tab.'function initialize()'.$new_line.$tab.'{'.$new_line.$tab.$tab.'var mapOptions = {'.$new_line.$tab.$tab.$tab.'center: new google.maps.LatLng(47, 52),'.$new_line.$tab.$tab.$tab.'zoom: 8,'.$new_line.$tab.$tab.$tab.'mapTypeId: google.maps.MapTypeId.ROADMAP'.$new_line.$tab.$tab.'};'.$new_line.$tab.$tab.'map = new google.maps.Map(document.getElementById("map"), mapOptions);'.$new_line.$tab.'}'.$new_line.'window.onload = function(){initialize()};'.$new_line, 'correct output with zoom set');