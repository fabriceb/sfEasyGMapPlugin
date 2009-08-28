<?php

/**
 * 
 * A googleMap Event
 * @author Fabrice Bernhard
 * 
 */
class GMapEvent
{
  protected $trigger;
  protected $function;
  protected $encapsulate_function;
  
  /**
   * @param string $trigger action that will trigger the event
   * @param string $function the javascript function to be executed
   * @param string $encapsulate_function
   * @author Fabrice Bernhard
   */
  public function __construct($trigger,$function,$encapsulate_function=true)
  {
    $this->trigger      = $trigger;
    $this->function     = $function;
    $this->encapsulate_function = $encapsulate_function;
  }
  
  /**
   * @return string $trigger  action that will trigger the event   
   */
  public function getTrigger()
  {
    
    return $this->trigger;
  }
  /**   
   * @return string $function the javascript function to be executed
   */
  public function getFunction()
  {
    if (!$this->encapsulate_function)
    {
      
      return $this->function;
    }
    else
    {
      
      return 'function() {
      '.$this->function.'
    }';
    }
  }
  
  /**
   * returns the javascript code for attaching a Google event to a javascript_object
   *
   * @param string $js_object_name
   * @return string
   * @author Fabrice Bernhard
   */
  public function getEventJs($js_object_name)
  {
    
    return 'google.maps.event.addListener('.$js_object_name.', "'.$this->getTrigger().'", '.$this->getFunction().');';
  }
  
   /**
   * returns the javascript code for attaching a dom event to a javascript_object
   *
   * @param string $js_object_name
   * @return string
   * @author Fabrice Bernhard
   */
  public function getDomEventJs($js_object_name)
  {
    
    return 'google.maps.event.addDomListener('.$js_object_name.', "'.$this->getTrigger().'", '.$this->getFunction().');';
  }
  
}
