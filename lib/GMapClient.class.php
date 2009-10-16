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
  
  /**
   * API key
   *
   * @var string
   */
  protected $api_key = null;

  /**
   * API key array
   *
   * @var array
   */
  protected $api_keys = null;

  const API_URL = 'http://maps.google.com/maps/geo?';
  const JS_URL  = 'http://maps.google.com/maps/api/js?sensor=false';

  /**
   *
   * @param string $api_key
   * @author Fabrice Bernhard
   * @since 2009-06-17
   */
  public function __construct($api_key = null, $api_keys = null)
  {
    if (!is_null($api_keys))
    {
      $this->api_keys = $api_keys;
    }

    if (!is_null($api_key))
    {
      $this->api_key = $api_key;
    }
    else
    {
      $this->api_key = self::guessAPIKey($this->getAPIKeys());
    }
  }

  /**
   * Sets the Google Maps API key
   * @param string $key
   */
  public function setAPIKey($key)
  {
    $this->api_key = $key;
  }

  /**
   * Gets the Google Maps API key
   * @return string $key
   */
  public function getAPIKey()
  {

    return $this->api_key;
  }

   /**
   * Guesses and sets the API Key
   * @author Fabrice
   *
   */
  protected function guessAndSetAPIKey()
  {
    $this->setAPIKey(self::guessAPIKey($this->getAPIKeys()));
  }

  /**
   * Sets the Google Map API Key using the array_google_keys defined in the app.yml of your application
   * @param string $domain The domaine name
   * @author Fabrice
   *
   */
  public function setAPIKeyByDomain($domain)
  {
    $this->setAPIKey(self::getAPIKeyByDomain($domain, $this->getAPIKeys()));
  }

  /**
   * Guesses the GoogleMap key for the current domain
   * @param string[] $api_keys
   * @return string $api_key
   * @author Fabrice
   *
   */
  public static function guessAPIKey($api_keys = null)
  {
    if (isset($_SERVER['SERVER_NAME']))
    {
      return self::getAPIKeyByDomain($_SERVER['SERVER_NAME'], $api_keys);
    }
    else if (isset($_SERVER['HTTP_HOST']))
    {
      return self::getAPIKeyByDomain($_SERVER['HTTP_HOST'], $api_keys);
    }

    return self::getAPIKeyByDomain('default', $api_keys);
  }

  /**
   * abstract the sfConfig layer to override it when outside of symfony
   * @return string[]
   * @author fabriceb
   * @since Jun 17, 2009 fabriceb
   */
  public function getAPIKeys()
  {

    return $this->api_keys;
  }

   /**
   * abstract the sfConfig layer to override it when outside of symfony
   * @param string
   * @author fabriceb
   * @since Jun 17, 2009 fabriceb
   */
  public static function setAPIKeys($api_keys)
  {
    $this->api_keys = $api_keys;
  }

  /**
   * Static method to retrieve API key
   *
   * @param unknown_type $domain
   * @return unknown
   */
  public static function getAPIKeyByDomain($domain, $api_keys = null)
  {
    if (is_null($api_keys) && class_exists('sfConfig'))
    {
      $api_keys = sfConfig::get('app_google_maps_api_keys');
    }

    if (is_array($api_keys) && array_key_exists($domain, $api_keys))
    {
      $api_key = $api_keys[$domain];
    }
    else
    {
      if (is_array($api_keys) && array_key_exists('default', $api_keys))
      {
        $api_key = $api_keys['default'];
      }
      else
      {
        throw new sfException('No Google Map API key defined in the app.yml file of your application');
      }
    }

    return $api_key;
  }



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

    $apiURL = self::API_URL.'&output='.$format.'&key='.$this->getAPIKey().'&q='.urlencode($address);
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
