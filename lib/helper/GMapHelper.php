<?php

/**
 * includes the html of the Map container
 *
 * @param GMap $gMap
 * @param array style options of the container
 * @author fabriceb
 */
function include_map($gMap,$options=array())
{
  if ($gMap instanceof sfOutputEscaper)
  {
    $gMap = $gMap->getRawValue();
  }
  
  echo $gMap->getContainer($options);
}

/**
 * includes the javascript that initializes the Map
 *
 * @param GMap $gMap
 * @author fabriceb
 */
function include_map_javascript($gMap)
{
  if ($gMap instanceof sfOutputEscaper)
  {
    $gMap = $gMap->getRawValue();
  }

  ?>
  <script type='text/javascript'>
    //<![CDATA[
      <?php echo $gMap->getJavascript() ?>
    //]]>
  </script>
  <?php
}

/**
 * includes the javascript src for the Google Maps file
 *
 * @param GMap $gMap
 * @param array style options of the container
 * @author fabriceb
 */
function include_google_map_javascript_file($gMap)
{
  if ($gMap instanceof sfOutputEscaper)
    $gMap = $gMap->getRawValue();

  ?>
  <script language="javascript" src ="<?php echo $gMap->getGoogleJsUrl() ?>"></script>
  <?php
}


function include_search_location_form()
{
  sfContext::getInstance()->getResponse()->addJavascript('/sfEasyGMapPlugin/js/sfEasyGMapPlugin.js');
  ?>
  <form onsubmit="geocode_and_show(document.getElementById('search_location_input').value);return false;">
    <input type="text" id="search_location_input" />
    <input type="submit" id="search_location_submit" value="Search" />
  </form>
  <?php
}

/**
 * Provides an address guesser using Google Maps geocode requests
 *
 * Example Javascript Callback Function:
 *  function myAddressGuesserCallback(addresses)
 *  {
 *    if (addresses.length == 0) return;
 *    alert(addresses.length + ' addresses were found.');
 *  }
 *
 *
 * @param GMapAddressGuesser $addressGuesser
 * @param Array override default options
 * @author johannes 
 */
function include_address_guesser(GMapAddressGuesser $addressGuesser, $options = array())
{
  if ($addressGuesser instanceof sfOutputEscaper)
    $addressGuesser = $addressGuesser->getRawValue(); 
  if (!($addressGuesser instanceof GMapAddressGuesser))
    throw new InvalidArgumentException('addressGuesser must be an instance of GMapAddressGuesser.');
  if (!is_array($options))
    throw new InvalidArgumentException('options must be an array.');

  use_helper('JavascriptBase');

  sfContext::getInstance()->getResponse()->addJavascript('http://www.google.com/jsapi?key='.$addressGuesser->getApiKey());
  sfContext::getInstance()->getResponse()->addJavascript('/sfEasyGMapPlugin/js/addressGuesser.js');
  echo javascript_tag($addressGuesser->getJavascript($options));
}
