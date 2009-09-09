<?php

/**
 * 
 * GoogleMap Bounds
 * @author Fabrice Bernhard
 * 
 */
class GMapBounds
{
  protected $sw = null;
  protected $ne = null;
  
  /**
   * Create a new Bounds object
   *
   * @param GMapCoord $nw
   * @param GMapCoord $se
   */
  public function __construct(GMapCoord $sw = null, GMapCoord $ne = null)
  {
    if (is_null($sw))
    {
      $sw = new GMapCoord();
    }
    if (is_null($ne))
    {
      $ne = new GMapCoord();
    }
    $this->sw = $sw;
    $this->ne = $ne;
  }
  public function getNorthEast()
  {
    
    return $this->ne;
  }
  
  public function getSouthWest()
  {
    
    return $this->sw;
  }
  
  static public function createFromString($string)
  {
    preg_match('/\(\((.*?)\), \((.*?)\)\)/',$string,$matches);
    if (count($matches)==3)
    {
      $sw = GMapCoord::createFromString($matches[1]);
      $ne = GMapCoord::createFromString($matches[2]);
      if ( !is_null($sw) && !is_null($ne))
      {
        
        return new GMapBounds($sw,$ne);
      }
      
      return null;
    }
    
    //((48.82415805606007,%202.308330535888672),%20(48.867086142850226,%202.376995086669922))
  }
  
  /**
   * Google String representations
   *
   * @return string
   * @author fabriceb
   * @since Feb 17, 2009 fabriceb
   */
  public function __toString()
  {  

    return '(('.$this->getSouthWest()->getLatitude().', '.$this->getSouthWest()->getLongitude().'), ('.$this->getNorthEast()->getLatitude().', '.$this->getNorthEast()->getLongitude().'))';
  }
  
  /**
   * returns a criteria on two columns to condition on "inside the bounds"
   *
   * @param string $lat_col_name
   * @param string $lng_col_name
   * @param Criteria $criteria
   * @param integer $margin
   * @return Criteria
   * @author fabriceb
   * @since 2008-12-03
   */
  public function criteriaInBounds($lat_col_name, $lng_col_name, $criteria = null, $margin = 0)
  {
    if (is_null($criteria))
    {
      $criteria = new Criteria();
    }
    
    $lat_tl = $this->getNorthEast()->getLatitude();
    $lat_br = $this->getSouthWest()->getLatitude();
    $lng_tl = $this->getNorthEast()->getLongitude();
    $lng_br = $this->getSouthWest()->getLongitude();
    
    if ($margin!=0)
    {
      $lat_margin = $margin * ($lat_tl-$lat_br);
      $lat_tl -= $lat_margin;
      $lat_br += $lat_margin;
      
      $lng_margin = $margin * ($lng_br-$lng_tl);
      $lng_tl += $lng_margin;
      $lng_br -= $lng_margin;
    }
    
    $sub_query = '%s BETWEEN %F AND %F';
    $lng_subquery = sprintf($sub_query,$lat_col_name, $lat_br, $lat_tl);
    $lat_subquery = sprintf($sub_query,$lng_col_name, $lng_br, $lng_tl);
    
    $criteria->add($lat_col_name,$lat_subquery,CRITERIA::CUSTOM);
    $criteria->add($lng_col_name,$lng_subquery,CRITERIA::CUSTOM);

    return $criteria;
  }
  
  /**
   * Get the latitude of the center of the zone
   *
   * @return integer
   * @author fabriceb
   * @since 2008-12-03 
   */
  public function getCenterLat()
  {
    if (is_null($this->getSouthWest()) || is_null($this->getNorthEast()))
    {
      
      return null;
    }
    
    return floatval(($this->getSouthWest()->getLatitude()+$this->getNorthEast()->getLatitude())/2);
  }
  
   /**
   * Get the longitude of the center of the zone
   *
   * @return integer
   * @author fabriceb
   * @since 2008-12-03 
   */
  public function getCenterLng()
  {
    if (is_null($this->getSouthWest()) || is_null($this->getNorthEast()))
    {
      
      return null; 
    }
    
    return floatval(($this->getSouthWest()->getLongitude()+$this->getNorthEast()->getLongitude())/2);
  }
  
   /**
   * Get the coordinates of the center of the zone
   *
   * @return GMapCoord
   * @author fabriceb
   * @since 2008-12-03 
   */
  public function getCenterCoord()
  {
  
    return new GMapCoord($this->getCenterLat(), $this->getCenterLng());
  }
  
  /**
   * Hauteur du carré
   *
   * @return float
   * @author fabriceb
   * @since Feb 17, 2009 fabriceb
   */
  public function getHeight()
  {
    
    return abs($this->getNorthEast()->getLatitude()-$this->getSouthWest()->getLatitude());
  }
  
  /**
   * Largeur du carré
   *
   * @return float
   * @author fabriceb
   * @since Feb 17, 2009 fabriceb
   */
  public function getWidth()
  {
    
    return abs($this->getNorthEast()->getLongitude()-$this->getSouthWest()->getLongitude());
  }
  
  /**
   * Does a homthety transformtion on the bounds, centered on the center of the bounds
   *
   * @param float $factor
   * @return GMapBounds $bounds
   * @author fabriceb
   * @since Feb 17, 2009 fabriceb
   */
  public function getHomothety($factor)
  {
    $bounds = new GMapBounds();
    $lat = $this->getCenterLat();
    $lng = $this->getCenterLng();
    $bounds->getNorthEast()->setLatitude($factor*$this->getNorthEast()->getLatitude()+$lat*(1-$factor));
    $bounds->getSouthWest()->setLatitude($factor*$this->getSouthWest()->getLatitude()+$lat*(1-$factor));
    $bounds->getNorthEast()->setLongitude($factor*$this->getNorthEast()->getLongitude()+$lng*(1-$factor));
    $bounds->getSouthWest()->setLongitude($factor*$this->getSouthWest()->getLongitude()+$lng*(1-$factor));
    
    return $bounds;
  }
  
  /**
   * gets zoomed out bounds
   *
   * @param integer $zoom_coef
   * @return GMapBounds
   * @author fabriceb
   * @since Feb 18, 2009 fabriceb
   */
  public function getZoomOut($zoom_coef)
  {
    if ($zoom_coef > 0)
    {
      $bounds = $this->getHomothety(pow(2,$zoom_coef));
      
      return $bounds;
    }
    
    return $this;
  }
  
  
  
  /**
   * Returns the most appropriate zoom to see the bounds on a map with min(width,height) = $min_w_h
   *
   * @param integer $min_w_h width or height of the map in pixels
   * @return integer
   * @author fabriceb
   * @since Feb 18, 2009 fabriceb
   */
  public function getZoom($min_w_h, $default_zoom = 14)
  {
    $infinity = 999999999;
    $factor_h = $infinity;
    $factor_w = $infinity;

    /*
      
    formula: the width of the bounds in "pixels" is pix_w * 2^z
    We want pix_w * 2^z to fit in min_w_h so we are looking for
    z = round ( log2 ( min_w_h / pix_w  ) )
     */
  
    $sw_lat_pix = GMapCoord::fromLatToPix($this->getSouthWest()->getLatitude(),0);
    $ne_lat_pix = GMapCoord::fromLatToPix($this->getNorthEast()->getLatitude(),0);
    $pix_h = abs($sw_lat_pix-$ne_lat_pix);
    if ($pix_h > 0)
    {
      $factor_h = $min_w_h / $pix_h;
    }
    
    $sw_lng_pix = GMapCoord::fromLngToPix($this->getSouthWest()->getLongitude(),0);
    $ne_lng_pix = GMapCoord::fromLngToPix($this->getNorthEast()->getLongitude(),0);
    $pix_w = abs($sw_lng_pix-$ne_lng_pix);
    if ($pix_w > 0)
    {
      $factor_w = $min_w_h / $pix_w;
    }
    
    $factor = min($factor_w,$factor_h);
    
    // bounds is one point, no zoom can be determined
    if ($factor == $infinity)
    {
      
      return $default_zoom;
    }
    
    return round(log($factor,2));
  }
  
  /**
   * Retourne les bounds qui contiennent toutes les autres
   *
   * @param GMapBounds[] $boundss
   * @param float $margin
   * @return GMapBounds
   * @author fabriceb
   * @since Feb 18, 2009 fabriceb
   */
  public static function getBoundsContainingAllBounds($boundss, $margin = 0)
  {
    $min_lat = 1000;
    $max_lat = -1000;
    $min_lng = 1000;
    $max_lng = -1000;
    foreach($boundss as $bounds)
    {
      $min_lat = min($min_lat, $bounds->getSouthWest()->getLatitude());
      $min_lng = min($min_lng, $bounds->getSouthWest()->getLongitude());
      $max_lat = max($max_lat, $bounds->getNorthEast()->getLatitude());
      $max_lng = max($max_lng, $bounds->getNorthEast()->getLongitude());
    }
    
    if ($margin > 0)
    {
      $min_lat = $min_lat - $margin*($max_lat-$min_lat); 
      $min_lng = $min_lng - $margin*($max_lng-$min_lng);
      $max_lat = $max_lat + $margin*($max_lat-$min_lat); 
      $max_lng = $max_lng + $margin*($max_lng-$min_lng);
    }
    
    $bounds = new GMapBounds(new GMapCoord($min_lat, $min_lng),new GMapCoord($max_lat, $max_lng));
    return $bounds;
  }
  
  /**
   * Retuns bounds containg an array of coordinates
   *
   * @param GMapCoord[] $coords
   * @param float $margin
   * @return GMapBounds
   * @author fabriceb
   * @since Mar 13, 2009 fabriceb
   */
  public static function getBoundsContainingCoords($coords, $margin = 0)
  {
    $min_lat = 1000;
    $max_lat = -1000;
    $min_lng = 1000;
    $max_lng = -1000;
    foreach($coords as $coord)
    {
      /* @var $coord GMapCoord */
      $min_lat = min($min_lat, $coord->getLatitude());
      $max_lat = max($max_lat, $coord->getLatitude());
      $min_lng = min($min_lng, $coord->getLongitude());
      $max_lng = max($max_lng, $coord->getLongitude());
    }
    
    if ($margin > 0)
    {
      $min_lat = $min_lat - $margin*($max_lat-$min_lat); 
      $min_lng = $min_lng - $margin*($max_lng-$min_lng);
      $max_lat = $max_lat + $margin*($max_lat-$min_lat); 
      $max_lng = $max_lng + $margin*($max_lng-$min_lng);
    }
    $bounds = new GMapBounds(new GMapCoord($min_lat, $min_lng),new GMapCoord($max_lat, $max_lng));
    
    return $bounds;
  }
  
  
  /**
  *
  * @param GMapMarker[] $markers array of MArkers
  * @param float $margin margin factor for the bounds
  * @return GMapBounds
  * @author fabriceb
  * @since 2009-05-02
  *
  **/
  public static function getBoundsContainingMarkers($markers, $margin = 0)
  {
    $coords = array();
    foreach($markers as $marker)
    {
      array_push($coords, $marker->getGMapCoord());
    }
   
    return GMapBounds::getBoundsContainingCoords($coords, $margin);
  }
  
  
  /**
   * Calculate the bounds corresponding to a specific center and zoom level for a give map size in pixels
   * 
   * @param GMapCoord $center_coord
   * @param integer $zoom
   * @param integer $width
   * @param integer $height
   * @return GMapBounds
   * @author fabriceb
   * @since Jun 2, 2009 fabriceb
   */
  public static function getBoundsFromCenterAndZoom(GMapCoord $center_coord, $zoom, $width, $height = null)
  {
    if (is_null($height))
    {
      $height = $width;
    }
    
    $center_lat = $center_coord->getLatitude();
    $center_lng = $center_coord->getLongitude();

    $pix = GMapCoord::fromLatToPix($center_lat, $zoom);
    $ne_lat = GMapCoord::fromPixToLat($pix - round(($height-1) / 2), $zoom);
    $sw_lat = GMapCoord::fromPixToLat($pix + round(($height-1) / 2), $zoom);
    
    $pix = GMapCoord::fromLngToPix($center_lng, $zoom);
    $sw_lng = GMapCoord::fromPixToLng($pix - round(($width-1) / 2), $zoom);
    $ne_lng = GMapCoord::fromPixToLng($pix + round(($width-1) / 2), $zoom);

    return new GMapBounds(new GMapCoord($sw_lat, $sw_lng), new GMapCoord($ne_lat, $ne_lng));
  }
  
  /**
   * 
   * @param GMapCoord $gmap_coord
   * @return boolean $is_inside
   * @author fabriceb
   * @since Jun 2, 2009 fabriceb
   */
  public function containsGMapCoord(GMapCoord $gmap_coord)
  {
    $is_inside = 
      (
      $gmap_coord->getLatitude() < $this->getNorthEast()->getLatitude()
      &&
      $gmap_coord->getLatitude() > $this->getSouthWest()->getLatitude()
      &&
      $gmap_coord->getLongitude() < $this->getNorthEast()->getLongitude()
      &&
      $gmap_coord->getLongitude() > $this->getSouthWest()->getLongitude()
      );
  
    return $is_inside;
  }
  
  
}
