<?php

/**
 * 
 * GoogleMap Bounds
 * @author Fabrice Bernhard
 * 
 */
class GMapCoord
{
  /**
   * Latitude
   *
   * @var float
   */
  protected $latitude;
  /**
   * Longitude
   *
   * @var float
   */
  protected $longitude;
  
  const EARTH_RADIUS = 6380;
  
  public function __construct($latitude = null, $longitude = null)
  {
    $this->latitude     = floatval($latitude);
    $this->longitude    = floatval($longitude);
  }
  
  
  /**
   * 
   * @param string $lat_col_name
   * @param string $lng_col_name
   * @param float $lat
   * @param float $lng
   * @param Criteria $criteria
   * @return Criteria
   * @author fabriceb
   * @since Sep 9, 2009
   */
  public static function criteriaOrderByDistance($lat_col_name, $lng_col_name, $lat, $lng, $criteria = null)
  {
    if (is_null($criteria))
    {
      $criteria = new Criteria();
    }
    
    $distance_query = '(POW(( %s - %F ),2) + POW(( %s - %F ),2))';
    $distance_query = sprintf($distance_query,$lat_col_name, $lat, $lng_col_name, $lng);
        
    $criteria->addAsColumn('distance', $distance_query);
    $criteria->addAscendingOrderByColumn('distance');
    
    return $criteria;
  }
  
  /**
   * 
   * @param string $lat_col_name
   * @param string $lng_col_name
   * @param float $lat
   * @param float $lng
   * @param integer $distance in kms
   * @param Criteria $criteria
   * @param $order_by_distance
   * @return Criteria
   * @author maximep
   * @since Sep 9, 2009
   * @since 2009-09-09 fabriceb factorisation
   */
  public static function criteriaInRadius($lat_col_name, $lng_col_name, $lat, $lng, $distance, $criteria = null, $order_by_distance = true)
  {
    if (is_null($criteria))
    {
      $criteria = new Criteria();
    }
    
    $k = pow(rad2deg($distance/self::EARTH_RADIUS),2);
    
    $distance_query = 'POW(( %s - %F ),2) + POW(( %s - %F ),2) < %F';    
    $distance_query = sprintf($distance_query,$lat_col_name, $lat, $lng_col_name, $lng, $k);
    
    $criteria->add($lat_col_name,$distance_query,Criteria::CUSTOM);
    
    if($order_by_distance)
    {
      $criteria = self::criteriaOrderByDistance($lat_col_name,$lng_col_name,$lat,$lng,$criteria);
    }
    
    return $criteria;
  }
  
  /**
   * 
   * @param string $lat_col_name
   * @param string $lng_col_name
   * @param integer $distance in kms
   * @param Criteria $criteria
   * @param boolean $order_by_distance
   * @return Criteria
   * @author maximep
   * @since Sep 9, 2009
   * @since 2009-09-09 fabriceb factorisation
   */
  public function getCriteriaInRadius($lat_col_name, $lng_col_name, $distance, $criteria = null, $order_by_distance = true)
  {
    
    return self::criteriaInRadius($lat_col_name, $lng_col_name, $this->getLatitude(), $this->getLongitude(), $distance, $criteria, $order_by_distance);
  }
  
  public function getLatitude()
  {

    return $this->latitude;
  }
  
  public function getLongitude()
  {
    
    return $this->longitude;
  }
  
  public function setLatitude($latitude)
  {
    $this->latitude = floatval($latitude);
  }
  
  public function setLongitude($longitude)
  {
    $this->longitude = floatval($longitude);
  }
  
  /**
   * 
   * @param $string
   * @return GMapCoord
   * @author fabriceb
   */
  public static function createFromString($string)
  {
    $coord_array = explode(',',$string);
    if (count($coord_array)==2)
    {
      $latitude = floatval(trim($coord_array[0]));
      $longitude = floatval(trim($coord_array[1]));
      
      return new GMapCoord($latitude,$longitude);
    }

    return null;
  }
  
  /**
   * 
   * @return string
   */
  public function toJs()
  {
    
    return 'new google.maps.LatLng('.$this->__toString().')';
  }
  
  /**
   * Lng to Pix
   * cf. a World's map according to Google http://mt0.google.com/mt/v=ap.92&hl=en&x=0&y=0&z=0&s=
   *
   * @param float $lng
   * @param integer $zoom
   * @return integer
   * @author fabriceb
   * @since Feb 18, 2009 fabriceb
   */
  public static function fromLngToPix($lng,$zoom)
  {
    $lngrad = deg2rad($lng);
    $mercx = $lngrad;
    $cartx = $mercx + pi();
    $pixelx = $cartx * 256/(2*pi());
    $pixelx_zoom =  $pixelx * pow(2,$zoom);    
    
    return $pixelx_zoom;
  }
  
  /**
   * Lat to Pix
   * cf. a World's map according to Google http://mt0.google.com/mt/v=ap.92&hl=en&x=0&y=0&z=0&s=
   *
   * @param float $lat
   * @param integer $zoom
   * @return integer
   * @author fabriceb
   * @since Feb 18, 2009 fabriceb
   */
  public static function fromLatToPix($lat,$zoom)
  {
    if ($lat == 90)
    {
      $pixely = 0;
    }
    else if ($lat == -90)
    {
      $pixely = 256;
    }
    else
    {
      $latrad = deg2rad($lat);
      $mercy = log(tan(pi()/4+$latrad/2));
      $carty = pi() - $mercy;
      $pixely = $carty * 256 / 2 / pi();
      $pixely = max(0, $pixely); // correct rounding errors near north and south poles
      $pixely = min(256, $pixely); // correct rounding errors near north and south poles
    }
    $pixely_zoom = $pixely * pow(2,$zoom);
    
    return $pixely_zoom;
  }
  
  /**
   * Pix to Lng
   * cf. a World's map according to Google http://mt0.google.com/mt/v=ap.92&hl=en&x=0&y=0&z=0&s=
   *
   * @param integer $pix
   * @param integer $zoom
   * @return float
   * @author fabriceb
   * @since Feb 18, 2009 fabriceb
   */
  public static function fromPixToLng($pixelx_zoom,$zoom)
  {
    $pixelx = $pixelx_zoom / pow(2,$zoom);    
    $cartx = $pixelx / 256 * 2 * pi();    
    $mercx = $cartx - pi();
    $lngrad = $mercx;
    $lng = rad2deg($lngrad);
    
    return $lng;
  }
  
  /**
   * Pix to Lat
   * cf. a World's map according to Google http://mt0.google.com/mt/v=ap.92&hl=en&x=0&y=0&z=0&s=
   *
   * @param integer $pix
   * @param integer $zoom
   * @return float
   * @author fabriceb
   * @since Feb 18, 2009 fabriceb
   */
  public static function fromPixToLat($pixely_zoom,$zoom)
  {    
    $pixely = $pixely_zoom / pow(2,$zoom);
    if ($pixely == 0)
    {
      $lat = 90;
    }
    else if ($pixely == 256)
    {
      $lat = -90;
    }
    else
    {
      $carty = $pixely / 256 * 2 * pi();
      $mercy = pi() - $carty;
      $latrad = 2 * atan(exp($mercy))-pi()/2;
      $lat = rad2deg($latrad);
    }
        
    return $lat;
  }
  
  /**
   * Calculates the center of an array of coordiantes
   * 
   * @param GMapCoord[] $coords
   * @return GMapCoord
   * @author fabriceb
   * @since 2009-05-02
   */
  public static function getMassCenterCoord($coords)  
  {
    if (count($coords)==0)
    {
      
      return null;
    }
    $center_lat = 0;
    $center_lng = 0;
    foreach($coords as $coord)
    {
      /* @var $coord GMapCoord */
      $center_lat += $coord->getLatitude();
      $center_lng += $coord->getLongitude();
    }
  
    return new GMapCoord($center_lat/count($coords),$center_lng/count($coords));
  }
  
  /**
   * Calculates the center of an array of coordiantes
   * 
   * @param GMapCoord[] $coords
   * @return GMapCoord
   * @author fabriceb
   * @since 2009-05-02
   */
  public static function getCenterCoord($coords)  
  {
    $bounds = GMapBounds::getBoundsContainingCoords($coords);
  
    return $bounds->getCenterCoord();
  }
  
  /**
   * toString method
   * @return string
   * @author fabriceb
   * @since 2009-05-02
   */
  public function __toString()
  {
    
    return $this->getLatitude().', '.$this->getLongitude();
  }
  
  /**
   * very approximate calculation of the distance in kilometers between two coordinates
   * @param GMapCoord $coord2
   * @return float
   * @author fabriceb
   * @since 2009-05-03
   */
  public function distanceFrom($coord2)
  {
    $lat_dist = abs($this->getLatitude()-$coord2->getLatitude());
    $lng_dist = abs($this->getLongitude()-$coord2->getLongitude());
    
    $rad_dist = deg2rad(sqrt(pow($lat_dist,2)+pow($lng_dist,2)));
  
    return $rad_dist * self::EARTH_RADIUS;
  }
  
    /**
   * very approximate calculation of the distance in kilometers between two coordinates
   * @param GMapCoord $coord1
   * @param GMapCoord $coord2
   * @return float
   * @author fabriceb
   * @since 2009-05-03
   */
  public static function distance($coord1, $coord2)
  {
  
    return $coord1->distanceFrom($coord2);
  }
  
  /**
   * 
   * @param GMapBounds $gmap_bounds
   * @return boolean $is_inside
   * @author fabriceb
   * @since Jun 2, 2009 fabriceb
   */
  public function isInsideBounds(GMapBounds $gmap_bounds)
  {
  
    return $gmap_bounds->containsGMapCoord($this);
  }
}
