<?php

class GMapOptions
{
  /**
   * If true, do not clear the contents of the Map div.
   * @param boolean
   */
  public $noClear = null;
  /**
   * Color used for the background of the Map div. This color will be visible when tiles have not yet loaded as a user pans.
   * @param string
   */
  public $backgroundColor = null;
  /**
   * The name or url of the cursor to display on a draggable object.
   * @param string
   */
  public $draggableCursor = null;
  /**
   * The name or url of the cursor to display when an object is dragging.
   * @param string
   */
  public $draggingCursor = null;
  /**
   * If false, prevents the map from being dragged. Dragging is enabled by default.
   * @param boolean
   */
  public $draggable = null;
  /**
   * If true, enables scrollwheel zooming on the map. The scrollwheel is disabled by default.
   * @param boolean
   */
  public $scrollwheel = null;
  /**
   * If false, prevents the map from being controlled by the keyboard. Keyboard shortcuts are enabled by default.
   * @param boolean
   */
  public $keyboardShortcuts = null;
  /**
   * The initial Map center. Required.
   * @param GMapLatLng
   */
  public $center = null;
  /**
   * The initial Map zoom level. Required.
   * @param number
   */
  public $zoom = null;
  /**
   * The initial Map mapTypeId. Required.
   * @param string
   */
  public $mapTypeId = null;
  /**
   * Enables/disables all default UI. May be overridden individually.
   * @param boolean
   */
  public $disableDefaultUI = null;
  /**
   * The initial enabled/disabled state of the Map type control.
   * @param boolean
   */
  public $mapTypeControl = null;
  /**
   * The initial display options for the Map type control.
   * @param MapTypeControl options
   */
  public $mapTypeControlOptions = null;
  /**
   * The initial enabled/disabled state of the scale control.
   * @param boolean
   */
  public $scaleControl = null;
  /**
   * The initial display options for the scale control.
   * @param ScaleControl options
   */
  public $scaleControlOptions = null;
  /**
   * The initial enabled/disabled state of the navigation control.
   * @param boolean
   */
  public $navigationControl = null;
  /**
   * The initial display options for the navigation control.
   * @param NavigationControl options 
   */
  public $navigationControlOptions  = null;


}