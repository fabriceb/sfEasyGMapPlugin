<?php
class GMapJsShortcuts
{
  protected static $prefix = 'gms_';
  protected static $jsShortcuts = array();
  protected static $enabled = false;

  protected static $current = 0;

  /**
   * generates short cuts for often used javascript functions to compress the
   * generated code.
   * 
   * @param string $longVersion
   * @return string
   * @author Johannes
   * @author Laurent Bachelier
   */
  public static function getJsShortcut($longVersion)
  {
    if (!self::isEnabled())
    {

      return $longVersion;
    }

  	if (!is_string($longVersion))
  		throw new InvalidArgumentException('longVersion must be a string.');

    if (!isset(self::$jsShortcuts[$longVersion]))
    {
      $shortcut = base_convert(self::$current++, 10, 36);
      $prefix = self::getPrefix();
      self::$jsShortcuts[$longVersion] = $prefix.$shortcut;
    }

    return self::$jsShortcuts[$longVersion];
  }

  /**
   * Enables or disables the JavaScript shortcuts feature
   * @param boolean $enabled
   * @author Laurent Bachelier
   */
  public static function setEnabled($enabled)
  {
    self::$enabled = $enabled;
  }

  /**
   * @return boolean
   * @author Laurent Bachelier
   */
  public static function isEnabled()
  {

    return self::$enabled;
  }

  /**
   * Enables or disables the JavaScript shortcuts feature
   * @param string $prefix
   * @author Laurent Bachelier
   */
  public static function setPrefix($prefix)
  {
    self::$prefix = $prefix;
  }

  /**
   * @return string
   * @author Laurent Bachelier
   */
  public static function getPrefix()
  {

    return self::$prefix;
  }

  /**
   * @return array
   * @author Laurent Bachelier
   */
  public static function getJsShortcuts()
  {
    return self::$jsShortcuts;
  }
}
