<?php

// Make GMap independent from symfony
if (!class_exists('GMapBounds', true))
{
  require_once(dirname(__FILE__).'/GMapBounds.class.php');
}
if (!class_exists('GMapClient', true))
{
  require_once(dirname(__FILE__).'/GMapClient.class.php');
}
if (!class_exists('GMapCoord', true))
{
  require_once(dirname(__FILE__).'/GMapCoord.class.php');
}
if (!class_exists('GMapEvent', true))
{
  require_once(dirname(__FILE__).'/GMapEvent.class.php');
}
if (!class_exists('GMapGeocodedAddress', true))
{
  require_once(dirname(__FILE__).'/GMapGeocodedAddress.class.php');
}
if (!class_exists('GMapIcon', true))
{
  require_once(dirname(__FILE__).'/GMapIcon.class.php');
}
if (!class_exists('GMapMarker', true))
{
  require_once(dirname(__FILE__).'/GMapMarker.class.php');
}
if (!class_exists('GMapDirection', true))
{
  require_once(dirname(__FILE__).'/GMapDirection.class.php');
}
if (!class_exists('RenderTag', true))
{
  require_once(dirname(__FILE__).'/external/RenderTag.class.php');
}

/**
 * Google Map class
 * @author Fabrice Bernhard
 *
 */

class GMap
{

  protected $options = array(
    // boolean  If true, do not clear the contents of the Map div.  
    'noClear ' => null,
    // string Color used for the background of the Map div. This color will be visible when tiles have not yet loaded as a user pans.  
    'backgroundColor' => null,
    // string The name or url of the cursor to display on a draggable object.  
    'draggableCursor' => null,
    // string The name or url of the cursor to display when an object is dragging.  
    'draggingCursor' => null,
    // boolean If false, prevents the map from being dragged. Dragging is enabled by default.  
    'draggable' => null,
    // boolean If true, enables scrollwheel zooming on the map. The scrollwheel is disabled by default.  
    'scrollwheel' => null,
    // boolean If false, prevents the map from being controlled by the keyboard. Keyboard shortcuts are enabled by default.  
    'keyboardShortcuts' => null,
    // LatLng The initial Map center. Required.  
    'center' => null,
    // number The initial Map zoom level. Required.  
    'zoom' => null,
    // string The initial Map mapTypeId. Required.  
    'mapTypeId' => 'google.maps.MapTypeId.ROADMAP',
    // boolean Enables/disables all default UI. May be overridden individually.  
    'disableDefaultUI' => null,
    // boolean The initial enabled/disabled state of the Map type control.  
    'mapTypeControl' => null,
    // MapTypeControl options The initial display options for the Map type control.  
    'mapTypeControlOptions' => null,
    // boolean The initial enabled/disabled state of the scale control.  
    'scaleControl' => null,
    // ScaleControl options The initial display options for the scale control.  
    'scaleControlOptions' => null,
    // boolean The initial enabled/disabled state of the navigation control.  
    'navigationControl' => null,
    // NavigationControl options The initial display options for the navigation control.  
    'navigationControlOptions' => null
  );
  
  protected $parameters = array(
      'js_name' => 'map',
      'onload_method' => 'js',
      'api_keys' => null
  );

  // id of the Google Map div container
  protected $container_attributes = array(
      'id' =>'map'
  );
  
  // style of the container
  protected $container_style=array(
    'width'=>'512px',
    'height'=>'512px'
  );

  // objects linked to the map
  protected $icons=array();
  protected $markers=array();
  protected $events=array();
  protected $directions=array();

  // customise the javascript generated
  protected $after_init_js=array();
  protected $global_variables=array();

  // the interface to the Google Maps API web service
  protected $gMapClient = false;  

  /**
   * Constructs a Google Map PHP object
   *
   * @param array $options
   * @param array $attributes
   */
  public function __construct($options=array(), $container_style=array(), $container_attributes=array(), $parameters=array())
  {
    $this->setOptions($options);
    $this->setContainerAttributes($container_attributes);
    $this->setContainerStyles($container_style);
    $this->setParameters($parameters);
    
    // delcare the Google Map Javascript object as global
    $this->addGlobalVariable($this->getJsName(),'null');

  }
  /**
   * Defines the style of the Google Map div
   * @param array $style Associative array with the style of the div container
   */
  public function setContainerStyles($container_style)
  {
    $this->container_style = array_merge($this->container_style,$container_style);
  }
  /**
   * Gets the style Array of the div container
   */
  public function getContainerStyles()
  {

    return $this->container_style;
  }
  /**
   * Defines the attributes of the Google Map div
   * @param array $container_attributes Associative array with the attributes of the div container
   * @author fabriceb
   * @since 2009-08-21
   */
  public function setContainerAttributes($container_attributes)
  {
    $this->container_attributes = array_merge($this->container_attributes,$container_attributes);
  }
  /**
   * Gets the attributes array of the div container
   * @author fabriceb
   * @since 2009-08-21
   */
  public function getContainerAttributes()
  {

    return $this->container_attributes;
  }
  /**
   * @param array $options
   * @author fabriceb
   * @since 2009-08-21
   */
  public function setOptions($options)
  {
    $this->options = array_merge($this->options,$options);
  }
  /**
   * @return array $options
   * @author fabriceb
   * @since 2009-08-21
   */
  public function getOptions()
  {

    return $this->options;
  }
  /**
   * @param array $parameters
   * @author fabriceb
   * @since 2009-08-21
   */
  public function setParameters($parameters)
  {
    $this->parameters = array_merge($this->parameters,$parameters);
  }
  /**
   * @return array $parameters
   * @author fabriceb
   * @since 2009-08-21
   */
  public function getParameters()
  {

    return $this->parameters;
  }
  /**
   * @param string $name
   * @param mixed $value
   * @author fabriceb
   * @since 2009-08-21
   */
  public function setParameter($name, $value)
  {
    $this->parameters[$name] = $value;
  }
  /**
   * @return mixed $value
   * @author fabriceb
   * @since 2009-08-21
   */
  public function getParameter()
  {

    return $this->parameters[$name];
  }
  /**
   * gets an instance of the interface to the Google Map web geocoding service
   *
   * @return GMapClient
   * @author fabriceb
   * @since 2009-06-17
   */
  public function getGMapClient($api_key = null)
  {
    if ($this->gMapClient === false)
    {
      $this->gMapClient = new GMapClient($api_key, $this->parameters['api_keys']);
    }

    return $this->gMapClient;
  }

  /**
   * sets an instance of the interface to the Google Map web geocoding service
   *
   * @param GMapClient
   * @author fabriceb
   * @since 2009-06-17
   */
  public function setGMapClient($gMapClient)
  {
    $this->gMapClient = $gMapClient;
  }


  /**
   * Geocodes an address
   * @param string $address
   * @return GMapGeocodedAddress
   * @author Fabrice Bernhard
   */
  public function geocode($address)
  {
    $address = trim($address);

    $gMapGeocodedAddress = new GMapGeocodedAddress($address);
    $accuracy = $gMapGeocodedAddress->geocode($this->getGMapClient());

    if ($accuracy)
    {
      return $gMapGeocodedAddress;
    }

    return null;
  }

  /**
   * Geocodes an address and returns additional normalized information
   * @param string $address
   * @return GMapGeocodedAddress
   * @author Fabrice Bernhard
   */
  public function geocodeXml($address)
  {
    $address = trim($address);

    $gMapGeocodedAddress = new GMapGeocodedAddress($address);
    $gMapGeocodedAddress->geocodeXml($this->getGMapClient());

    return $gMapGeocodedAddress;
  }

  /**
   * @return string $this->options['js_name'] Javascript name of the googlemap
   */
  public function getJsName()
  {

    return $this->parameters['js_name'];
  }

  /**
   * Defines one style of the div container
   * @param string $style_tag name of css tag
   * @param string $style_value value of css tag
   */
  public function setContainerStyle($style_tag,$style_value)
  {
    $this->container_style[$style_tag]=$style_value;
  }
  /*
   * Gets one style of the Google Map div
   * @param string $style_tag name of css tag
   */
  public function getContainerStyle($style_tag)
  {

    return $this->container_style[$style_tag];
  }

  public function getContainerId()
  {

    return $this->container_attributes['id'];
  }

  /**
   * returns the Html for the Google map container
   * @param Array $options Style options of the HTML container
   * @return string $container
   * @author Fabrice Bernhard
   */
  public function getContainer($styles=array(),$attributes=array())
  {
    $this->container_style = array_merge($this->container_style,$styles);
    $this->container_attributes = array_merge($this->container_attributes,$attributes);

    $style="";
    foreach ($this->container_style as $tag=>$val)
    {
      $style.=$tag.":".$val.";";
    }

    $attributes = $this->container_attributes;
    $attributes['style'] = $style;

    return RenderTag::renderContent('div',null,$attributes);
  }
  
  /**
   * 
   * @return string
   * @author fabriceb
   * @since 2009-08-20
   */
  public function optionsToJs()
  {
    $options_array = array();
    foreach($this->options as $name => $value)
    {
      if (!is_null($value))
      {
        switch($name)
        {
          case 'navigationControlOptions':
          case 'scaleControlOptions':
          case 'mapTypeControlOptions':
            $options_array[] = $name.': {style: '.$value.'}';
            break;
          case 'center':
            $options_array[] = $name.': '.$value->toJs();
            break;
          default:
            $options_array[] = $name.': '.$value;
            break;
        }
      }
    }
    $tab = '  ';
    $separator = "\n".$tab.$tab;
    
    return '{'.$separator.$tab.implode(','.$separator.$tab, $options_array).$separator.'}';
  }
  
  /**
   * 
   * @return unknown_type
   * @author fabriceb
   * @since Oct 8, 2009
   */
  public function getOnloadJs()
  {
    switch ($this->parameters['onload_method'])
    {
      case 'jQuery':
        return 'jQuery(document).ready(function(){initialize();});';
        break;
      case 'prototype':
        return 'document.observe("dom:loaded", function(){initialize();});';
        break;
      default:
      case 'js':
        return 'window.onload = function(){initialize()};';
        break;
    }
  }

  /**
   * Returns the Javascript for the Google map
   * @param Array $options
   * @return $string
   * @author Fabrice Bernhard
   * @since 2009-08-21 fabriceb v3
   */
  public function getJavascript()
  {
    if (class_exists('sfContext'))
    {
      sfContext::getInstance()->getResponse()->addJavascript($this->getGoogleJsUrl());
    }


    $return ='';
    $init_events = array();
    $init_events[] = 'var mapOptions = '.$this->optionsToJs().';';
    $init_events[] = $this->getJsName().' = new google.maps.Map(document.getElementById("'.$this->getContainerId().'"), mapOptions);';

    // add some more events
    $init_events[] = $this->getEventsJs();
    $init_events[] = $this->getIconsJs();
    $init_events[] = $this->getMarkersJs();
    $init_events[] = $this->getDirectionsJs();
    foreach ($this->after_init_js as $after_init)
    {
      $init_events[] = $after_init;
    }

    foreach($this->global_variables as $name=>$value)
    {
      $return .= '
  var '.$name.' = '.$value.';';
    }
    $return .= '
  //  Call this function when the page has been loaded
  function initialize()
  {';
    foreach($init_events as $init_event)
    {
      if ($init_event)
      {
        $return .= '
    '.$init_event;
      }
    }
    $return .= '
  }
';
    $return .= $this->getOnloadJs()."\n";

    return $return;
  }

  /**
   * returns the URLS for the google map Javascript file
   * @return string $js_url
   */
  public function getGoogleJsUrl($auto_load = true)
  {

    return $this->getGMapClient()->getGoogleJsUrl($auto_load);
  }

  /**
   * Adds an icon to be loaded
   * @param GMapIcon $icon A google Map Icon
   */
  public function addIcon($icon)
  {
    $this->icons[$icon->getName()] = $icon;
  }

  /**
   * returns the GMapIcon corresponding to a name
   *
   * @param string $name
   * @return GMapIcon
   *
   * @author Vincent
   * @since 2008-12-02
   */
  public function getIconByName($name)
  {

    return $this->icons[$name];
  }

  /**
   * @param GMapMarker $marker a marker to be put on the map
   */
  public function addMarker($marker)
  {
    array_push($this->markers,$marker);
  }
  /**
   * @param GMapMarker[] $markers marker to be put on the map
   */
  public function setMarkers($markers)
  {
    $this->markers = $markers;
  }
  /**
   * @param GMapEvent $event an event to be attached to the map
   */
  public function addEvent($event)
  {
    array_push($this->events,$event);
  }

  /**
   * checks which markers have special icons and binds these icons to the map
   * 
   * @return void
   */
  public function loadMarkerIcons()
  {
    foreach($this->markers as $marker)
    {
      if ($marker->getIcon() instanceof GMapIcon)
      {
        $this->addIcon($marker->getIcon());
      }
    }
  }
  /**
   * Returns the javascript string which defines the icons
   * @return string
   */
  public function getIconsJs()
  {
    $this->loadMarkerIcons();
    $return = '';
    foreach ($this->icons as $icon)
    {
      $return .= $icon->getIconJs();
    }

    return $return;
  }
  /**
   * Returns the javascript string which defines the markers
   * @return string
   */
  public function getMarkersJs()
  {
    $return = '';
    foreach ($this->markers as $marker)
    {
      $return .= $marker->toJs($this->getJsName());
      $return .= "\n      ";
    }

    return $return;
  }

  /*
   * Returns the javascript string which defines events linked to the map
   * @return string
   */
  public function getEventsJs()
  {
    $return = '';
    foreach ($this->events as $event)
    {
      $return .= $event->getEventJs($this->getJsName());
      $return .= "\n";
    }
    
    return $return;
  }

  /*
   * Gets the Code to execute after Js initialization
   * @return string $after_init_js
   */
  public function getAfterInitJs()
  {
    return $this->after_init_js;
  }
  /*
   * Sets the Code to execute after Js initialization
   * @param string $after_init_js Code to execute
   */
  public function addAfterInitJs($after_init_js)
  {
    array_push($this->after_init_js,$after_init_js);
  }

  public function addGlobalVariable($name, $value='null')
  {
    $this->global_variables[$name] = $value;
  }
  
  /**
   * 
   * @param string $name
   * @return mixed
   * @author fabriceb
   * @since 2009-08-21
   */
  public function getOption($name)
  {
    
    return $this->options[$name];
  }
  
  /**
   * 
   * @param string $name
   * @param mixed $value
   * @return void
   * @author fabriceb
   * @since 2009-08-21
   */
  public function setOption($name, $value)
  {
    $this->options[$name] = $value;
  }
  
  /**
   * 
   * @return integer $zoom
   */
  public function getZoom()
  {

    return $this->getOption('zoom');
  }
  
  /**
   * 
   * @param integer $zoom
   * @return void
   */
  public function setZoom($zoom)
  {
    $this->setOption('zoom',$zoom);
  }
  
  /**
   * Sets the center of the map at the beginning
   *
   * @param float $lat
   * @param float $lng
   * @since 2009-08-20 fabriceb now everything is in the options array
   */
  public function setCenter($lat=null,$lng=null)
  {
    $this->setOption('center',new GMapCoord($lat, $lng));
  }
  
  /**
   *
   * @return GMapCoord
   * @author fabriceb
   * @since 2009-05-02
   * @since 2009-08-20 fabriceb now everything is in the options array
   */
  public function getCenterCoord()
  {

    return $this->getOption('center');
  }
   /**
   *
   * @return float
   * @author fabriceb
   * @since 2009-05-02
   */
  public function getCenterLat()
  {

    return $this->getCenterCoord()->getLatitude();
  }
    /**
   *
   * @return float
   * @author fabriceb
   * @since 2009-05-02
   */
  public function getCenterLng()
  {
    return $this->getCenterCoord()->getLongitude();
  }


  /**
   * gets the width of the map in pixels according to container style
   * @return integer
   * @author fabriceb
   * @since 2009-05-03
   */
  public function getWidth()
  {
    // percentage or 0px
    if (substr($this->getContainerStyle('width'),-2,2) != 'px')
    {
      
      return false;
    }
    
    return intval(substr($this->getContainerStyle('width'),0,-2));
  }

  /**
   * gets the width of the map in pixels according to container style
   * @return integer
   * @author fabriceb
   * @since 2009-05-03
   */
  public function getHeight()
  {
    // percentage or 0px
    if (substr($this->getContainerStyle('height'),-2,2) != 'px')
    {
      
      return false;
    }

    return intval(substr($this->getContainerStyle('height'),0,-2));
  }

  /**
   * sets the width of the map in pixels
   *
   * @param integer
   * @author fabriceb
   * @since 2009-05-03
   */
  public function setWidth($width)
  {
    if (is_int($width))
    {
      $width = $width.'px';
    }
    $this->setContainerStyle('width', $width);
  }

  /**
   * sets the width of the map in pixels
   *
   * @param integer
   * @author fabriceb
   * @since 2009-05-03
   */
  public function setHeight($height)
  {
    if (is_int($height))
    {
      $height = $height.'px';
    }
    $this->setContainerStyle('height',$height);
  }


  /**
   * Returns the URL of a static version of the map (when JavaScript is not active)
   * Supports only markers and basic parameters: center, zoom, size.
   * @param string $map_type = 'mobile'
   * @param string $hl Language (fr, en...)
   * @return string URL of the image
   * @author Laurent Bachelier
   */
  public function getStaticMapUrl($maptype='mobile', $hl='fr')
  {
    $params = array(
      'maptype' => $maptype,
      'zoom'    => $this->getZoom(),
      'key'     => $this->getAPIKey(),
      'center'  => $this->getCenterLat().','.$this->getCenterLng(),
      'size'    => $this->getWidth().'x'.$this->getHeight(),
      'hl'      => $hl,
      'markers' => $this->getMarkersStatic()
    );
    $pairs = array();
    foreach($params as $key => $value)
    {
      $pairs[] = $key.'='.$value;
    }

    return 'http://maps.google.com/staticmap?'.implode('&',$pairs);
  }

  /**
   * Returns the static code to create markers
   * @return string
   * @author Laurent Bachelier
   */
  protected function getMarkersStatic()
  {
    $markers_code = array();
    foreach ($this->markers as $marker)
    {
      $markers_code[] = $marker->getMarkerStatic();
    }

    return implode('|',$markers_code);
  }

  /**
   *
   * calculates the center of the markers linked to the map
   *
   * @return GMapCoord
   * @author fabriceb
   * @since 2009-05-02
   */
  public function getMarkersCenterCoord()
  {

    return GMapMarker::getCenterCoord($this->markers);
  }

  /**
   * sets the center of the map at the center of the markers
   *
   * @author fabriceb
   * @since 2009-05-02
   */
  public function centerOnMarkers()
  {
    $center = $this->getMarkersCenterCoord();

    $this->setCenter($center->getLatitude(), $center->getLongitude());
  }

  /**
   *
   * calculates the zoom which fits the markers on the map
   *
   * @param integer $margin a scaling factor around the smallest bound
   * @return integer $zoom
   * @author fabriceb
   * @since 2009-05-02
   */
  public function getMarkersFittingZoom($margin = 0, $default_zoom = 14)
  {
    $bounds = GMapBounds::getBoundsContainingMarkers($this->markers, $margin);
    
    return $bounds->getZoom(min($this->getWidth(),$this->getHeight()), $default_zoom);
  }

  /**
   * sets the zoom of the map to fit the markers (uses mercator projection to guess the size in pixels of the bounds)
   * WARNING : this depends on the width in pixels of the resulting map
   *
   * @param integer $margin a scaling factor around the smallest bound
   * @author fabriceb
   * @since 2009-05-02
   */
  public function zoomOnMarkers($margin = 0, $default_zoom = 14)
  {
    $this->setZoom($this->getMarkersFittingZoom($margin, $default_zoom));
  }

   /**
   * sets the zoom and center of the map to fit the markers (uses mercator projection to guess the size in pixels of the bounds)
   *
   * @param integer $margin a scaling factor around the smallest bound
   * @author fabriceb
   * @since 2009-05-02
   */
  public function centerAndZoomOnMarkers($margin = 0, $default_zoom = 14)
  {
    $this->centerOnMarkers();
    $this->zoomOnMarkers($margin, $default_zoom);
  }

  /**
   *
   * @return GMapBounds
   * @author fabriceb
   * @since Jun 2, 2009 fabriceb
   */
  public function getBoundsFromCenterAndZoom()
  {

    return GMapBounds::getBoundsFromCenterAndZoom($this->getCenterCoord(),$this->getZoom(),$this->getWidth(),$this->getHeight());
  }

  /**
   * backwards compatibility
   * @param string[] $api_keys
   * @return string
   * @author fabriceb
   * @since Jun 17, 2009 fabriceb
   */
  public static function guessAPIKey($api_keys = null)
  {

    return GMapClient::guessAPIKey($api_keys);
  }
  
  /**
   * $directions getter
   *
   * @return array $directions
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2009-11-13 17:18:29
   */
  public function getDirections()
  {
    
    return $this->directions;
  }
  
  /**
   * $directions setter
   *
   * @param array $directions
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2009-11-13 17:21:18
   */
  public function setDirections($directions = null)
  {
    $this->directions = $directions;
  }
  
  /**
   * Add direction to list ($this->directions)
   *
   * @param GMapDirection $directions
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2009-11-20 14:59:55
   */
  public function addDirection($direction = null)
  {
    if (!$direction instanceof GMapDirection)
    {
      throw new sfException('The direction must be an instance of GMapDirection !');
    }
    
    array_push($this->directions, $direction);
  }
  
  /**
   * Get the directions javascript code
   *
   * @return string $js_code
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2009-11-20 15:03:00
   */
  public function getDirectionsJs()
  {
    $js_code = '';
    
    foreach ($this->directions as $direction)
    {
      $js_code .= $direction->toJs($this->getJsName());
      $js_code .= "\n      ";
    }

    return $js_code;
  }
}
