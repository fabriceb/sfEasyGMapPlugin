<?php

/**
 * A GoogleMap InfoWindow
 * 
 * @author Maxime Picaud
 *
 */
class GMapInfoWindow
{
   /**
   * javascript name of the marker
   *
   * @var string
   */
  protected $js_name        = null;
  protected $options = array(
    //  String   Content to display in the InfoWindow. This can be an HTML element, a plain-text string, or a string containing HTML. The InfoWindow will be sized according to the content. To set an explicit size for the content, set content to be a HTML element with that size.
    'content'  => null,
  
    //  boolean   Disable auto-pan on open. By default, the info window will pan the map so that it is fully visible when it opens.
    'disableAutoPan' => null,
  
    // number  Maximum width of the infowindow, regardless of content's width. This value is only considered if it is set before a call to open. To change the maximum width when changing content, call close, setOptions, and then open.
    'maxWidth' => null,
  
     //Size  The offset, in pixels, of the tip of the info window from the point on the map at whose geographical coordinates the info window is anchored. If an InfoWindow is opened with an anchor, the pixelOffset will be calculated from the top-center of the anchor's bounds.
    'pixelOffset' => null,
    
    //  LatLng  The LatLng at which to display this InfoWindow. If the InfoWindow is opened with an anchor, the anchor's position will be used instead.
    'position' => null,
  
      //number  All InfoWindows are displayed on the map in order of their zIndex, with higher values displaying in front of InfoWindows with lower values. By default, InfoWinodws are displayed according to their latitude, with InfoWindows of lower latitudes appearing in front of InfoWindows at higher latitudes. InfoWindows are always displayed in front of markers.
    'zIndex' => null,
  );
  
  protected $content = null;
  protected $events  = array();
  protected $custom_properties = array();
  
  /**
   * @param string content
   * @param array $options
   * @param string $js_name
   * @param array $events
   * @author Maxime Picaud
   * @since 7 sept. 2009
   */
  public function __construct($content,$options = array(),$js_name='info_window',$events=array())
  {
    $this->js_name = $js_name;
    $this->setContent($content);
    $this->setOptions($options);
    $this->events  = $events;    
  }
  
  
  /**
  * @return string $js_name Javascript name of the marker  
  */
  public function getName()
  {
    return $this->js_name;
  }
  
  /**
   * 
   * @return string
   * @author Maxime Picaud
   * @since 7 sept. 2009
   */
  public function getContent()
  {
    return $this->content;
  }
  
  /**
   * 
   * @param string $content
   * @author Maxime Picaud
   * @since 7 sept. 2009
   */
  public function setContent($content)
  {
    $content = preg_replace('/\r\n|\n|\r/', "\\n", $content);
    $content = preg_replace('/(["\'])/', '\\\\\1', $content);
    
    $this->content = '"'.$content.'"';
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
   * @return string Js for size pixelOffset
   * @author Maxime Picaud
   * @since 7 sept. 2009
   */
  public function pixelOffsetToJs()
  {
    $pixelOffset = $this->getOption('pixelOffset');
    $size = 'null';
    if(is_array($pixelOffset) && isset($pixelOffset['width']) && isset($pixelOffset['height']))
    {
      $size = 'new google.maps.Size('.$this->getWidth().','.$this->getHeight().')'; 
    }
    
    return $size;
  }
  
  
  /**
   * 
   * @return string
   * @author fabriceb
   * @since 2009-08-21
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
          case 'pixelOffset':
            $options_array[] = $name.': '.$this->pixelOffsetToJs();
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
  * @param string $map_js_name 
  * @return string Javascript code to create the marker
  * @author Fabrice Bernhard
  * @since 2009-08-21
  */
  public function toJs($map_js_name = 'map')
  {
    $content = $this->getContent();
    if (!empty($content))
    {
      $this->setOption('content', $content);
    }
    $return = '';
    $return .= $this->getName().' = new google.maps.InfoWindow('.$this->optionsToJs().");\n";
    foreach ($this->custom_properties as $attribute=>$value)
    {
      $return .= $this->getName().".".$attribute." = '".$value."';";
    }
    foreach ($this->events as $event)
    {
      $return .= '    '.$event->getEventJs($this->getName());
    }   
    
    return $return;
  }
  
  /**
   * Adds an event listener to the marker
   *
   * @param GMapEvent $event
   */
  public function addEvent($event)
  {
    array_push($this->events,$event);
  }
  
  public function setCustomProperties($custom_properties)
  {
    $this->custom_properties=$custom_properties;
  }
  
  public function getCustomProperties()
  {
    return $this->custom_properties;
  }
  
  /**
   * Sets a custom property to the generated javascript object
   *
   * @param string $name
   * @param string $value
   */
  public function setCustomProperty($name,$value)
  {
    $this->custom_properties[$name] = $value;
  }
}
