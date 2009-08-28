<?php

/**
 * 
 * GoogleMap Icon class
 * 
 * @author Fabrice Bernhard
 * 
 */
class GMapIcon
{
  protected $name         = null;
  protected $icon_src     = '';
  protected $shadow_src   = '';
  protected $options      = array();
  
  public function __construct($name,$icon_src,$options = array(),$shadow_src='')
  {
    $this->name       = $name;
    $this->icon_src   = $icon_src;
    $this->shadow_src = $shadow_src;
    $default_options  = array(
      'width'=>12,
      'height'=>20,
      'shadow_width'=>22,
      'shadow_height'=>20,
      'anchor_x'=>6,
      'anchor_y'=>20,
      'info_window_anchor_x'=>6,
      'info_window_anchor_y'=>3,
    );
    $this->options = array_merge($default_options,$options);
  }
  
  /**
   * Set Icon's path
   * @param string $icon_src Icon's path
   */
  public function setIconSrc($icon_src)
  {
    $this->icon_src=$icon_src;
  }
  /**
   * Get Icon's path
   * @return string   
   */
  public function getIconSrc()
  {
    
    return $this->icon_src;
  }
  /**
   * Set Shadow's path
   * @param string $shadow_src Shadow's path
   */
  public function setShadowSrc($shadow_src)
  {
    $this->shadow_src=$shadow_src;
  }
  /**
   * Get Shadow's path   
   */
  public function getShadowSrc()
  {
    
    return $this->shadow_src;
  }
  /**
   * Get Icon's Javascript variable's name
   * @return string $name 
   */
  public function getName()
  {
    
    return $this->name;
  }
  /**
   * Change Icon's JavaScript name
   * @param string $name
   */
  public function setName($name)
  {
    $this->name = $name;
  }
  /**
   * Gets an option
   */
  public function getOption($name)
  {
    
    return $this->options[$name];
  }
  
  /**
   * Returns the javascript code tthat defines an icon
   *
   * @return string
   */
  public function getIconJs()
  {
    $return ="";
    $return .= $this->getName().' = new google.maps.Icon(); ';
    $return .= $this->getName().'.image = "'.$this->getIconSrc().'";';
    $return .= $this->getName().'.iconSize = new google.maps.Size('.$this->getOption('width').','.$this->getOption('height').');';
    $return .= $this->getName().'.iconAnchor = new google.maps.Point('.$this->getOption('anchor_x').','.$this->getOption('anchor_y').');';
    $return .= $this->getName().'.infoWindowAnchor = new google.maps.Point('.$this->getOption('info_window_anchor_x').','.$this->getOption('info_window_anchor_y').');';
    if (!is_null($this->getShadowSrc()))
    {
      $return .= $this->getName().'.shadow = "'.$this->getShadowSrc().'";';        
      $return .= $this->getName().'.shadowSize = new google.maps.Size('.$this->getOption('shadow_width').','.$this->getOption('shadow_height').');';
    }
    
    return $return;
  }
}
