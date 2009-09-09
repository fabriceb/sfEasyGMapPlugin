<?php

/**
 * A class to communicate with Google Maps
 * @author Fabrice Bernhard
 */

class GMapClient
{
  /**
   * Cache instance
   *
   * @var sfCache
   */
  protected $cache = null;


  const API_URL = 'http://maps.google.com/maps/geo?';
  const JS_URL  = 'http://maps.google.com/maps/api/js?sensor=false';




  /**
   * Connection to Google Maps' API web service
   *
   * @param string $address
   * @param string $format 'csv' or 'xml'
   * @return string
   * @author fabriceb
   * @since 2009-06-17
   */
  public function getGeocodingInfo($address, $format = 'csv')
  {
    if ($this->hasCache())
    {
      $cache = $this->getCache()->get($format.$address);
      if ($cache)
      {

        return $cache;
      }
    }

    $apiURL = self::API_URL.'&output='.$format.'&q='.urlencode($address);
    $raw_data = file_get_contents($apiURL);

    if ($this->hasCache())
    {
      $this->getCache()->put($format.$address, $raw_data);
    }

    return $raw_data;
  }

  /**
   * Dependency injection for the cache instance
   *
   * @param sfCache $cache
   * @author fabriceb
   * @since 2009-06-17
   */
  public function setCache($cache)
  {
    $this->cache = $cache;
  }

  /**
   *
   * @return sfCache
   * @author fabriceb
   * @since 2009-06-17
   */
  public function getCache()
  {

    return $this->cache;
  }



  /**
   * Is Geocode-Caching to the database enabled?
   * WARNING: this depends on the geocodes caching schema addition
   *
   * @return boolean $hasCache wether the geocodes Table is use to store address lookups
   * @author lukas.schroeder
   * @since 2009-06-09
   * @since 2009-06-17 fabriceb is now using dependency injection and the sfCache bastract class
   */
  public function hasCache()
  {

    return $this->cache instanceof sfCache;
  }


  /**
   * returns the URLS for the google map Javascript file
   * @param boolean $auto_load if the js of GMap should be loaded by default
   * @return string $js_url
   * @author fabriceb
   * @since 2009-06-17
   */
  public function getGoogleJsUrl($auto_load = true)
  {
    $js_url = self::JS_URL;

    return $js_url;
  }

}
