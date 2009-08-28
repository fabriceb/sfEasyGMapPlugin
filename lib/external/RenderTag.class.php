<?php

  /**
   * Class that compiles some functions stolen from sfWidget.class.php
   * @copyright Fabien Potencier

 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 
   */
   
  class RenderTag
  {
    /**
     * Renders a HTML tag.
     *
     * @param string $tag         The tag name
     * @param array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
     *
     * @param string An HTML tag string
     */
    static public function render($tag, $attributes = array())
    {
      if (empty($tag))
      {
        return '';
      }
  
      return sprintf('<%s%s />', $tag, self::attributesToHtml($attributes));
    }
  
    /**
     * Renders a HTML content tag.
     *
     * @param string $tag         The tag name
     * @param string $content     The content of the tag
     * @param array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
     *
     * @param string An HTML tag string
     */
    static public function renderContent($tag, $content = null, $attributes = array())
    {
      if (empty($tag))
      {
        return '';
      }
  
      return sprintf('<%s%s>%s</%s>', $tag, self::attributesToHtml($attributes), $content, $tag);
    }
  
    /**
     * Escapes a string.
     *
     * @param  string $value  string to escape
     * @return string escaped string
     */
    static public function escapeOnce($value)
    {
      $value = is_object($value) ? $value->__toString() : (string) $value;
  
      return self::fixDoubleEscape(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
    }
  
    /**
     * Fixes double escaped strings.
     *
     * @param  string $escaped  string to fix
     * @return string single escaped string
     */
    static public function fixDoubleEscape($escaped)
    {
      return preg_replace('/&amp;([a-z]+|(#\d+)|(#x[\da-f]+));/i', '&$1;', $escaped);
    }
  
    /**
     * Converts an array of attributes to its HTML representation.
     *
     * @param  array  $attributes An array of attributes
     *
     * @return string The HTML representation of the HTML attribute array.
     */
    static public function attributesToHtml($attributes)
    {
  
      return implode('', array_map(array('RenderTag', 'attributesToHtmlCallback'), array_keys($attributes), array_values($attributes)));
    }
  
    /**
     * Prepares an attribute key and value for HTML representation.
     *
     * @param  string $k  The attribute key
     * @param  string $v  The attribute value
     *
     * @return string The HTML representation of the HTML key attribute pair.
     */
    static protected function attributesToHtmlCallback($k, $v)
    {
      return is_null($v) || '' === $v ? '' : sprintf(' %s="%s"', $k, self::escapeOnce($v));
    }
  }