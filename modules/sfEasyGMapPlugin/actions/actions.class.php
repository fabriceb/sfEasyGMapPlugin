<?php

class sfEasyGMapPluginActions extends sfActions
{

  public function executeIndex()
  {
    $this->available_samples = array(
      '1'  => array('url' => 'sfEasyGMapPlugin/sample1', 'name' => 'Sample 1', 'message' => 'Simple map with some markers, using longitudes and latitudes.'),
      '2'  => array('url' => 'sfEasyGMapPlugin/sample2', 'name' => 'Sample 2', 'message' => 'Basic events on marker and map.'),
      '3'  => array('url' => 'sfEasyGMapPlugin/sample3', 'name' => 'Sample 3', 'message' => 'Basic GMapInfoWindow sample.'),
      '4'  => array('url' => 'sfEasyGMapPlugin/sample4', 'name' => 'Sample 4', 'message' => 'How to center the map on a marker cloud.'),
      '5'  => array('url' => 'sfEasyGMapPlugin/sample5', 'name' => 'Sample 5', 'message' => 'Center the map on a given map and display inside markers.'),
      '6'  => array('url' => 'sfEasyGMapPlugin/sample6', 'name' => 'Sample 6', 'message' => 'How to set a custom marker.'),
      '7'  => array('url' => 'sfEasyGMapPlugin/sample7', 'name' => 'Sample 7', 'message' => 'GMapGeocodedAddress sample.'),
      '8'  => array('url' => 'sfEasyGMapPlugin/sample8', 'name' => 'Sample 8', 'message' => 'GMapDirection sample.'),
      '9'  => array('url' => 'sfEasyGMapPlugin/sample9', 'name' => 'Sample 9', 'message' => 'Reverse geocoding sample.'),
      '10' => array('url' => 'sfEasyGMapPlugin/sample10', 'name' => 'Sample 10', 'message' => 'Multiple info windows'),
    );
  }

  /**
   * Simple map with markers
   *
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2009-10-02 17:17:18
   */
  public function executeSample1()
  {
    // Initialize the google map
    $this->gMap = new GMap();
    $this->gMap->setCenter(48.397, 3.644);
    $this->gMap->setZoom(5);

    // Add some markers on the map
    $this->gMap->addMarker(
      new GMapMarker(51.245475,6.821373)
    );
    $this->gMap->addMarker(
      new GMapMarker(46.262248,6.115969)
    );
    $this->gMap->addMarker(
      new GMapMarker(48.848959,2.341577)
    );
    $this->gMap->addMarker(
      new GMapMarker(48.718952,2.219180)
    );
    $this->gMap->addMarker(
      new GMapMarker(47.376420,8.547995)
    );

    // END OF ACTION
    $this->message = 'Simple google map with markers';
    $this->action_source = $this->functionToString('executeSample1');
  }


  /**
   * Marker/Map event sample
   *
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2009-10-02 16:56:25
   */
  public function executeSample2()
  {
    // Initialize the google map
    $gMap = new GMap();
    $gMap->setZoom(4);
    $gMap->setCenter(-25.363882, 131.044922);

    // Create marker
    $marker = new GMapMarker(-12.461334, 130.841904, array('title' => '"Hello World !"'));
    
    // Add event on marker
    $marker->addEvent(new GMapEvent('click', 'moveToMarker();'));
    
    // Add marker on the map
    $gMap->addMarker($marker);

    // Add event on the map
    $gMap->addEvent(new GMapEvent('click', 'gmapSample_AddConsoleLine("You just click on the map !")'));

    $this->gMap = $gMap;

    $this->setTemplate('sample1');

    // END OF ACTION
    $this->message = 'Simple events : <br /> - click marker to focus on<br /> - click on the map for test map event';
    $this->action_source = $this->functionToString('executeSample2');
  }


  /**
   * GMapInfoWindow sample
   *
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2009-10-02 17:16:44
   */
  public function executeSample3()
  {
    // Initialize the google map
    $gMap = new GMap();
    $gMap->setZoom(13);
    $gMap->setCenter(-12.461334, 130.841904);

    // Create GMapInfoWindow
    $info_window = new GMapInfoWindow('<div>Your HTML content here !</div>');

    // Create marker
    $marker = new GMapMarker(-12.461334, 130.841904, array('title' => '"Darwin"'));
    $marker->addHtmlInfoWindow($info_window);
    $gMap->addMarker($marker);

    $this->gMap = $gMap;

    $this->setTemplate('sample1');

    // END OF ACTION
    $this->message = 'Simple GMapInfoWindow : click marker to open info window';
    $this->action_source = $this->functionToString('executeSample3');
  }

  /**
   * Here we learn to:
   *  - adapt the map to the current markers using complex but hidden formulas :-)
   */
  public function executeSample4()
  {
    // Initialize google map
    $this->gMap = new GMap();

    // Add some markers
    $this->gMap->addMarker(
      new GMapMarker(51.245475,6.821373)
    );
    $this->gMap->addMarker(
      new GMapMarker(46.262248,6.115969)
    );
    $this->gMap->addMarker(
      new GMapMarker(48.848959,2.341577)
    );
    $this->gMap->addMarker(
      new GMapMarker(48.718952,2.219180)
    );
    $this->gMap->addMarker(
      new GMapMarker(47.376420,8.547995)
    );
    
    // Center the map on marker width 0.3 margin
    $this->gMap->centerAndZoomOnMarkers(0.3);
    
    $this->setTemplate('sample1');

    // END OF ACTION
    $this->message = 'Center the map on the markers.';
    $this->action_source = $this->functionToString('executeSample4');
  }

  /**
   * Here we learn to:
   *  - center on a given place and guess the bounds from the zoom and center
   */
  public function executeSample5()
  {
    $this->gMap = new GMap();
    $this->gMap->setWidth(512);
    $this->gMap->setHeight(400);
    // center on Paris
    $this->gMap->setCenter(48.857939,2.346611);
    // nice zoom
    $this->gMap->setZoom(11);

    $bounds = $this->gMap->getBoundsFromCenterAndZoom(48.857939, 2.346611, 11, 512, 400);

    $markers = array(
      new GMapMarker(51.245475,6.821373),
      new GMapMarker(46.262248,6.115969),
      new GMapMarker(48.848959,2.341577),
      new GMapMarker(48.718952,2.219180),
      new GMapMarker(47.376420,8.547995),
    );

    foreach($markers as $marker)
    {
      if ($marker->isInsideBounds($bounds))
      {
        $this->gMap->addMarker($marker);
      }
    }

    $this->setTemplate('sample1');

    // END OF ACTION
    $this->message = 'Center the map on a given place and display only inside markers.';
    $this->action_source = $this->functionToString('executeSample5');
  }

  /**
   * Custom GMap marker sample
   * 
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2009-10-02 17:36:37
   */
  public function executeSample6()
  {
    // Initialize the google map
    $this->gMap = new GMap();
    $this->gMap->setWidth(512);
    $this->gMap->setHeight(400);
    $this->gMap->setCenter(48.857939,2.346611);
    $this->gMap->setZoom(11);
    
    // Create GMapIcon
    $icon = new GMapMarkerImage(
      '/sfEasyGMapPlugin/images/nice_icon.png',
      array(
        'width' => 18,
        'height' => 25,
      )
    );

    // Marker
    $marker = new GMapMarker(48.848959, 2.341577, array('icon' => $icon));
    $this->gMap->addMarker($marker);

    $this->setTemplate('sample1');

    // END OF ACTION
    $this->message = 'Custom marker.';
    $this->action_source = $this->functionToString('executeSample6');
  }
  
  /**
   * GMapGeocodedAddress sample
   *
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2009-10-16 15:25:11
   */
  public function executeSample7()
  {
    // Initialize the google map
    $this->gMap = new GMap();
    $this->gMap->setWidth(512);
    $this->gMap->setHeight(400);
    $this->gMap->setZoom(16);

    $sample_address = '60 rue de Seine, 75006 Paris, France';
    
    // Create geocoded address
    $geocoded_address = new GMapGeocodedAddress($sample_address);
    $geocoded_address->geocode($this->gMap->getGMapClient());
    
    // Center the map on geocoded address
    $this->gMap->setCenter($geocoded_address->getLat(), $geocoded_address->getLng());
    
    // Add marker on geocoded address
    $this->gMap->addMarker(
      new GMapMarker($geocoded_address->getLat(), $geocoded_address->getLng())
    );

    $this->setTemplate('sample1');

    // END OF ACTION
    $this->message = 'Display a marker on geocoded address "'.$sample_address.'" and center the map.';
    $this->action_source = $this->functionToString('executeSample7');
  }
  
  /**
   * GMapDirection sample
   * 
   * @param sfWebRequest $request
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2009-10-30 17:30:11
   */
  public function executeSample8()
  {
    // Initialize the google map
    $this->gMap = new GMap();
    $this->gMap->setWidth(512);
    $this->gMap->setHeight(400);
    $this->gMap->setZoom(6);
    $this->gMap->setCenter(47.1224758, 1.36230468);
    
    // Paris coordinates
    $paris = new GMapCoord(48.857939, 2.346611);
    
    // Bordeaux coordinates
    $bordeaux = new GMapCoord(44.837368, -0.576144); 
    
    $dijon = new GMapCoord(47.327213, 5.043988);
    $lyon  = new GMapCoord(45.767299, 4.834329);

    // Waypoint samples
    $waypoints = array(
      new GMapDirectionWaypoint($dijon),
      new GMapDirectionWaypoint($lyon, false)
    );
    
    // Initialize GMapDirection
    $direction = new GMapDirection($paris, $bordeaux, 'direction_sample', array('waypoints' => $waypoints, 'panel' => "document.getElementById('direction_pane')"));
    $this->gMap->addDirection($direction);
    
    $this->setTemplate('sample1');

    // END OF ACTION
    $this->view_panel = true;
    $this->message = 'GMapDirection sample from Paris to Bordeaux';
    $this->action_source = $this->functionToString('executeSample8');
  }

  /**
   * Reverse geocoding sample
   *
   * @param sfWebRequest $request
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2009-10-30 17:30:11
   */
  public function executeSample9()
  {
    // Initialize the google map
    $this->gMap = new GMap();
    $this->gMap->setWidth(512);
    $this->gMap->setHeight(400);
    $this->gMap->setZoom(16);
    $this->gMap->setCenter(48.857939, 2.346611);

    // Marker
    $marker = new GMapMarker($this->gMap->getCenterLat(), $this->gMap->getCenterLng());
    $this->gMap->addMarker($marker);

    // Reverse geocoding of the center of the map
    $geocoded_addr = new GMapGeocodedAddress(null);
    $geocoded_addr->setLat($this->gMap->getCenterLat());
    $geocoded_addr->setLng($this->gMap->getCenterLng());
    $geocoded_addr->reverseGeocode($this->gMap->getGMapClient());

    $this->setTemplate('sample1');

    // END OF ACTION
    $this->message = 'Reverse geocoding sample';
    $this->action_source = $this->functionToString('executeSample9');

    // Construct console message
    $values = array(
      'Reverse geocoding of coordinates \"'.$this->gMap->getCenterLat().', '.$this->gMap->getCenterLng().'\" : ',
      '<i>raw_address : </i>'.$geocoded_addr->getRawAddress(),
      '<i>accuracy : </i>'.$geocoded_addr->getAccuracy(),
      '<i>geocoded_city : </i>'.$geocoded_addr->getGeocodedCity(),
      '<i>geocoded_country_code : </i>'.$geocoded_addr->getGeocodedCountryCode(),
      '<i>geocoded_country : </i>'.$geocoded_addr->getGeocodedCountry(),
      '<i>geocoded_address : </i>'.$geocoded_addr->getGeocodedAddress(),
      '<i>geocoded_street : </i>'.$geocoded_addr->getGeocodedStreet(),
      '<i>geocoded_postal_code : </i>'.$geocoded_addr->getGeocodedPostalCode(),
    );

    foreach ($values as $value)
    {
      $this->gMap->addAfterInitJs('gmapSample_AddConsoleLine("'.$value.'");');
    }
  }

  /**
   * Multiple GMapInfoWindow sample
   *
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2010-03-04
   */
  public function executeSample10()
  {
    // Initialize the google map
    $gMap = new GMap();

    $markers = array(
      new GMapMarker(51.245475, 6.821373),
      new GMapMarker(46.262248, 6.115969),
      new GMapMarker(48.848959, 2.341577),
      new GMapMarker(48.718952, 2.219180),
      new GMapMarker(47.376420, 8.547995),
    );

    foreach($markers as $marker)
    {
      // Reverse geocoding of the center of the map
      $geocoded_addr = new GMapGeocodedAddress(null);
      $geocoded_addr->setLat($marker->getLat());
      $geocoded_addr->setLng($marker->getLng());
      $geocoded_addr->reverseGeocode($gMap->getGMapClient());

      $info_window = new GMapInfoWindow('<div>'.$geocoded_addr->getRawAddress().'</div>');
      $marker->addHtmlInfoWindow($info_window);
      $gMap->addMarker($marker);
    }

    // Center the map on marker width 0.3 margin
    $gMap->centerAndZoomOnMarkers(0.3);

    $this->gMap = $gMap;

    $this->setTemplate('sample1');

    // END OF ACTION
    $this->message = 'Multiple info window : click marker to open info window';
    $this->action_source = $this->functionToString('executeSample10');
  }
  
  function functionToString($function_name)
  {
    $text = file_get_contents(__FILE__);
    preg_match('/function '.$function_name.'\(\)\n  \{\n(.*)\n    \/\/ END OF ACTION$/msU', $text, $matches);

    return str_replace('&nbsp;&nbsp;&nbsp;&nbsp;' , '', str_replace(' ', '&nbsp;', $matches[1]));
  }
}