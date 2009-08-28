<?php

class sfEasyGMapPluginActions extends sfActions
{

  public function executeIndex()
  {

  }

  /**
   * Here we learn to display a simple Map and some markers
   * @author Fabrice Bernhard
   *
   */
  public function executeSample1()
  {
    $this->gMap = new GMap();

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
  }


  /**
   * Here we learn to display a simple Map and geocode some addresses, and add an info window html
   * @author Fabrice Bernhard
   *
   */
  public function executeSample2()
  {
    $this->gMap = new GMap();

    // some places in the world
    $addresses = array(
      'Graf-Recke-Strasse 220 - 40237 Düsseldorf',
      'Avenue des sports 01210 FERNEY-VOLTAIRE - FRANCE',
      '44 boulevard Saint-Michel, Paris',
      'Route Saclay 91120 Palaiseau',
      'Rämistrasse 101, Zürich'
    );

    foreach ($addresses as $address)
    {
      $geocoded_address = $this->gMap->geocode($address);
      $gMapMarker = new GMapMarker($geocoded_address->getLat(),$geocoded_address->getLng());
      $gMapMarker->addHtmlInfoWindow('<b>Address:</b><br />'.$address);
      $this->gMap->addMarker($gMapMarker);
    }

    $this->setTemplate('sample1');
  }


  /**
   * Here we learn to:
   *  - keep the markers in a global variable,
   *  - change a marker's icon
   *  - and create a few custom events
   */
  public function executeSample3()
  {
    $this->gMap = new GMap(
      array(
        'zoom'=>4,
        'center_lat'=>45,
        'center_lng'=>8,
        'control'=>'new google.maps.SmallMapControl()'
      )
    );

    // some places in the world
    $coordinates = array(
      array(51.245475,6.821373),
      array(46.262248,6.115969),
      array(48.848959,2.341577),
      array(48.718952,2.219180),
      array(47.376420,8.547995)
    );

    $this->gMap->addGlobalVariable('markers','new Array()');

    $gMapIcon = new GMapIcon(
      'nice_icon',
      '/sfEasyGMapPlugin/images/nice_icon.png',
      array(
        'width'=>18,
        'height'=>25,
        'info_window_anchor_x'=>9,
        'info_window_anchor_y'=>25
      )
    );
    $gMapEvent1 = new GMapEvent(
      'mouseover',
      "document.getElementById('console_div').innerHTML = 'Mouse over marker number '+this.num;"
    );
    $gMapEvent2 = new GMapEvent(
      'mouseout',
      "document.getElementById('console_div').innerHTML = '';"
    );

    foreach ($coordinates as $key=>$coordinate)
    {
      $gMapMarker = new GMapMarker($coordinate[0],$coordinate[1],'markers['.$key.']',$gMapIcon);
      $gMapMarker->addHtmlInfoWindow('<b>Coordinates:</b><br />'.implode(', ',$coordinate));
      $gMapMarker->setCustomProperty('num',$key);
      $gMapMarker->addEvent($gMapEvent1);
      $gMapMarker->addEvent($gMapEvent2);

      $this->gMap->addMarker($gMapMarker);
    }

    $this->setTemplate('sample1');
  }

  /**
   * Here we learn to:
   *  - adapt the map to the current markers using complex but hidden formulas :-)
   */
  public function executeSample4()
  {
    $this->gMap = new GMap();
    $this->gMap->setWidth(512);
    $this->gMap->setHeight(400);

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
    $this->gMap->centerAndZoomOnMarkers(0.3);

    $this->setTemplate('sample1');
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

    $bounds = $this->gMap->getBoundsFromCenterAndZoom(48.857939,2.346611,11,512,400);


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
  }

}