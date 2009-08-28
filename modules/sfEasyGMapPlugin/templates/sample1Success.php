<?php use_helper('Javascript','GMap') ?>

<h1>The Map</h1>
<?php include_map($gMap); ?>

Search on the map:
<?php include_search_location_form() ?>
<br />
<br />
<br />
<div id="console_div" style="font-size:large">
</div>

<br />
<!-- Javascript included at the bottom of the page -->
<?php include_map_javascript($gMap); ?>