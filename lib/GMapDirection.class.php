<?php

/**
 * GMapDirection class
 *
 * @author Vincent Guillon <vincentg@theodo.fr>
 * @since 2009-10-30 17:18:26
 */
class GMapDirection
{  
  protected $origin;
  protected $destination;
  protected $travel_mode;
  protected $js_name;
  protected $options = array(
    // Whether or not trip alternatives should be provided.
    'provideTripAlternatives' => false,
    
    // Region code used as a bias for geocoding requests.
    'region'     => null,
    
    /**
     * Preferred unit system to use when displaying distance.
     * Defaults to the unit system used in the country of origin [IMPERIAL or METRIC]
     */
    'unitSystem' => null,
    
    // Array of intermediate waypoints. Directions will be calculated from the origin to the destination by way of each waypoint in this array.
    'waypoints'  => array(),
    
    // Travel mode [DRIVING, WALKING]
    'travelMode' => 'DRIVING',
    
    // Node
    'panel'      => null,
  );
  
  protected $prefix_list = array(
    'unitSystem' => 'google.maps.DirectionsUnitSystem.',
    'travelMode' => 'google.maps.DirectionsTravelMode.'
  );
  
  /**
   * Construct GMapDirection object
   *
   * @param GMapCoord $origin The coordinates of origin
   * @param GMapCoord $destination The coordinates of destination
   * @param string $js_name The js var name
   * @param array $options Array of options
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2009-10-30 17:20:47
   */
  public function __construct($origin = null, $destination = null, $js_name = 'gmap_direction', $options = array())
  {
    $default_options  = array(
      'travelMode' => 'DRIVING',
    );
    
    $this->setOrigin($origin);
    $this->setDestination($destination);
    $this->setOptions(array_merge($default_options, $options));
    $this->setJsName($js_name);
  }
  
  /**
   * Origin getter
   *
   * @return GMapCoord $this->origin
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2009-10-30 17:23:11
   */
  public function getOrigin()
  {
    
    return $this->origin;
  }
  
  /**
   * Destination getter
   *
   * @return GMapCoord $this->destination
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2009-10-30 17:23:30
   */
  public function getDestination()
  {
    
    return $this->destination;
  }
  
  /**
   * Options getter
   *
   * @return array $this->options 
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2009-11-13 15:38:46
   */
  public function getOptions()
  {
    
    return $this->options;
  }
  
  /**
   * Retrieve option from options list by index
   *
   * @param string $name 
   * @return $this->options[$index]
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2009-11-13 15:52:55
   */
  public function getOption($name = null)
  {
    if (is_null($name))
    {
      throw new sfException('$name can\'t be "null" !');
    }
    
    return $this->options[$name];
  }
  
  /**
   * js_name getter
   *
   * @return string $this->js_name
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2009-11-13 15:50:22
   */
  public function getJsName()
  {
    
    return $this->js_name;
  }
  
  /**
   * Origin setter
   *
   * @param GMapCoord $origin
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2009-10-30 17:25:39
   */
  public function setOrigin($origin = null)
  {
    if (!$origin instanceof GMapCoord)
    {
      throw new sfException('The origin must be an instance of GMapCoord !');
    }
    
    $this->origin = $origin;
  }
  
  /**
   * Destination setter
   *
   * @param GMapCoord $destination
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2009-10-30 17:26:17
   */
  public function setDestination($destination = null)
  {
    if (!$destination instanceof GMapCoord)
    {
      throw new sfException('The destination must be an instance of GMapCoord !');
    }
    
    $this->destination = $destination;
  }
  
  /**
   * Options setter
   *
   * @param array $options
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2009-11-13 15:39:46
   */
  public function setOptions($options = null)
  {
    $this->options = $options;
  }
  
  /**
   * Option setter
   *
   * @param $name The index
   * @param $value The option
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2009-11-13 15:56:51
   */
  public function setOption($name = null, $value = null)
  {
    if (is_null($name))
    {
      throw new sfException('$name can\'t be "null" !');
    }
    
    $this->option[$name] = $value;
  }
  
  /**
   * js_name setter
   *
   * @param string $js_name
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2009-11-13 15:51:57
   */
  public function setJsName($js_name = 'gmap_direction')
  {
    $this->js_name = $js_name;
  }
  
  /**
   * Return refix by option
   *
   * @param string $option
   * @return string
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2009-11-20 15:42:10
   */
  public function getOptionPrefix($option = '')
  {
    
    return isset($this->prefix_list[$option]) ? $this->prefix_list[$option] : '';
  }
  
  /**
   * Generate js code for direction
   *
   * @param string $map_js_name The google map js var name
   * @return $js_code The generated js to display direction
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2009-11-13 16:23:02
   */
  public function toJs($map_js_name = 'map')
  {
    $options = $this->getOptions();
    $js_name = $this->getJsName();
    
    // Construct js code
    $js_code = '';
    $js_code .= 'var '.$js_name.'Renderer = new google.maps.DirectionsRenderer();'."\n";
    $js_code .= 'var '.$js_name.'Service = new google.maps.DirectionsService();'."\n";
    $js_code .= $js_name.'Renderer.setMap('.$map_js_name.');'."\n";
    
    // Display direction panel
    if (isset($options['panel']) && $options['panel'])
    {
       $js_code .= $js_name.'Renderer.setPanel('.$options['panel'].');'."\n\n";
       unset($options['panel']);
    }
    
    $js_code .= 'var '.$js_name.'Request = {'."\n";
    $js_code .= '  origin: '.$this->getOrigin()->toJs().','."\n";
    $js_code .= '  destination: '.$this->getDestination()->toJs().','."\n";    
    
    // Add options
    foreach ($options as $name => $option)
    {
      if ($name == 'waypoints' && count($option) > 0)
      {
        $js_code .= '  '.$name.' : ['."\n";
        
        foreach ($option as $waypoint)
        {
          if ($waypoint instanceof GMapDirectionWaypoint)
          {
            $js_code .= '    '.$waypoint->optionsToJs().",\n";
          }
        }
                
        $js_code .= '  ],'."\n";
      }
      else
      {
        $js_code .= '  '.$name.' : '.$this->getOptionPrefix($name).$option.",\n";
      }
    }
    
    $js_code .= '};'."\n";
    
    $js_code .= $js_name.'Service.route('.$js_name.'Request, function(response, status)'."\n";
    $js_code .= '{'."\n";
    $js_code .= '  if (status == google.maps.DirectionsStatus.OK)'."\n";
    $js_code .= '  {'."\n";
    $js_code .= '    '.$js_name.'Renderer.setDirections(response);'."\n";
    $js_code .= '  }'."\n";
    $js_code .= '});'."\n";
    
    return $js_code;
  }
}