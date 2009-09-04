<?php

/*
 * 
 * A GoogleMap MarkerImage
 * @author Maxime Picaud
 * 
 */
class GMapMarkerImage
{
  //String $url url of image
  protected $url;
   
  //Size $size array('width' => null , 'height' => null ) 
  protected $size = array(
    'width' => null,
    'height' => null
  );
   
  //Point $origin array('x' => null , 'y' => null ) 
  protected $origin = array(
    'x' => null,
    'y' => null
  );
  
  //Point $anchor array('x' => null , 'y' => null ) 
  protected $anchor = array(
    'x' => null,
    'y' => null
  );
  
 
  protected $custom_properties = array();
  
  /**
   * @param string $js_name Javascript name of the marker
   * @param string $url url of image 
   * @param array $size array('width' => $width,'height' => $height)
   * @param array $origin array('x' => $x,'y' => $y)
   * @param array $anchor array('x' => $x,'y' => $y)
   * @author Maxime Picaud
   */
  public function __construct($url,$size=null,$origin=null,$anchor=null)
  {
    $this->url = $url;
    if(is_array($size) && isset($size['width']) && isset($size['height']))
    {
    	$this->setSize($size['width'],$size['height']);
    }
    
    if(is_array($origin) && isset($origin['x']) && isset($origin['y']))
    {
      $this->setOrigin($origin['x'],$origin['y']);
    }
    
    if(is_array($anchor) && isset($anchor['x']) && isset($anchor['y']))
    {
      $this->setAnchor($anchor['x'],$anchor['y']);
    }
  }
  

  
  /**
  * @return string $js_name Javascript name of the marker  
  */
  public function getName()
  {
    
    return $this->js_name;
  }
  /**    
  * @return string $url  
  */
  public function getUrl()
  {
    return $this->url;
  }
  /**    
  * @return array $size  
  */
  public function getSize()
  {
    return $this->size;
  }
  /**    
  * @return int $size['width']  
  */
  public function getWidth()
  {
    return $this->size['width'];
  }
  /**    
  * @return int $size['height']  
  */
  public function getHeight()
  {
    return $this->size['height'];
  }
  
  /**    
  * @param int $width
  * @param int $height     
  */
  public function setSize($width,$height)
  {
    $this->size['width'] = $width;
    $this->size['height'] = $height;
  }
  
  /**    
  * @return array $origin  
  */
  public function getOrigin()
  {
    return $this->origin;
  }
  /**    
  * @return int $origin['x']  
  */
  public function getOriginX()
  {
    return $this->origin['x'];
  }
  /**    
  * @return int $origin['y']  
  */
  public function getOriginY()
  {
    return $this->origin['y'];
  }
  
  /**    
  * @param int $x
  * @param int $y
  */
  public function setOrigin($x,$y)
  {
    $this->origin['x'] = $x;
    $this->origin['y'] = $y;
  }
  
  /**    
  * @return array $anchor  
  */
  public function getAnchor()
  {
    return $this->anchor;
  }
  /**    
  * @return int $anchor['x']  
  */
  public function getAnchorX()
  {
    return $this->anchor['x'];
  }
  /**    
  * @return int $anchor['y']  
  */
  public function getAnchorY()
  {
    return $this->anchor['y'];
  }
  /**    
  * @param int $x
  * @param int $y  
  */
  public function setAnchor($x,$y)
  {
    $this->anchor['x'] = $x;
    $this->anchor['y'] = $y;
  }

  public function sizeToJs()
  {
  	$size = 'null';
    if(!empty($this->size['width']) && !empty($this->size['height']))
    {
      $size = 'new google.maps.Size('.$this->getWidth().','.$this->getHeight().')'; 
    }
    return $size;
  }
  
  public function originToJs()
  {
    $origin = 'null';
    if(!empty($this->origin['x']) && !empty($this->origin['y']))
    {
      $origin = 'new google.maps.Point('.$this->getOriginX().','.$this->getOriginY().')'; 
    }
    return $origin;
  }
  
  public function anchorToJs()
  {
  	$anchor = 'null';
    if(!empty($this->anchor['x']) && !empty($this->anchor['y']))
    {
      $anchor = $this->getName().'new google.maps.Point('.$this->getAnchorX().','.$this->getAnchorY().')'; 
    }
    return $anchor;
  }
  
  
  /** 
  * @return string Javascript code to create the markerImage
  * @author Maxime Picaud
  */
  public function toJs()
  {
  	$params = array();
  	
  	$params[] = '"'.$this->getUrl().'"';
  	$params[] = $this->sizeToJs();
  	$params[] = $this->originToJs();
    $params[] = $this->anchorToJs();
    
    $return = 'new google.maps.MarkerImage('.implode(',',$params).")";
    
    return $return;
  }

}
