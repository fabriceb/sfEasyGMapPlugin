<?php

/**
 * GMapDirectionWaypoint class
 *
 * @author Vincent Guillon <vincentg@theodo.fr>
 * @since 2009-11-20 16:14:23
 */
class GMapDirectionWaypoint
{  
  protected $location;
  protected $stopover;
  
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
  public function __construct($location = null, $stopover = true)
  {
    $this->setLocation($location);
    $this->setStopOver($stopover);
  }
  
  /**
   * $location getter
   *
   * @return GMapCoord $this->location
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2009-11-20 16:16:55
   */
  public function getLocation()
  {
    
    return $this->location;
  }
  
  /**
   * $stopover getter
   *
   * @return boolen $this->stopover
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2009-11-20 16:17:14
   */
  public function getStopOver()
  {
    
    return $this->stopover;
  }
  
  /**
   * $location setter
   *
   * @param GMapCoord $location
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2009-11-20 16:17:42
   */
  public function setLocation($location = null)
  {
    if (!$location instanceof GMapCoord)
    {
      throw new sfException('The destination must be an instance of GMapCoord !');
    }
    
    $this->location = $location;
  }
  
  /**
   * $stopover setter
   *
   * @param boolean $stopover
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2009-11-20 16:19:37
   */
  public function setStopOver($stopover = true)
  {
    $this->stopover = $stopover;
  }
  
  /**
   * Generate javascript code fo GMapDirection waypoints option
   *
   * @return string
   * @author Vincent Guillon <vincentg@theodo.fr>
   * @since 2009-11-20 16:31:42
   */
  public function optionsToJs()
  {
    $stopover = $this->getStopOver() ? 'true' : 'false';
    
    return '{location : '.$this->getLocation()->toJs().', stopover: '.$stopover.'}';
  }
}