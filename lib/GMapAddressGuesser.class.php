<?php
/**
 * GMap Address Guesser Class
 * @author Johannes Schmitt
 */

class GMapAddressGuesser 
{
	protected 
		$options = array(),
		$defaultOptions = array(
			'js_name'		=> 'addressGuesser',
			'js_class'		=> 'sfEasyGMapAddressGuesser',
			'form_fields' 	=> array('street', 'streetnumber', 'postal_code', 'city'),
			'query_format' 	=> '%s %s, %s %s',
			'callback'		=> null,
		),
		$apiKey = null
		;
	
	public function __construct($options = array())
	{
		$this->initialize($options);
	}
	
	protected function initialize($options = array())
	{
		$this->options = array_merge($this->defaultOptions, $options);
		
		// determine the API key
		$this->setApiKey(GMap::guessAPIKey());		
	}
	
	public function setApiKey($apiKey)
	{
		if (!is_string($apiKey))
			throw new InvalidArgumentException('apiKey must be a string.');
		
		$this->apiKey = $apiKey;
	}
	
	public function getApiKey()
	{
		return $this->apiKey;
	}
	
	public function setOption($name, $value)
	{
		$this->options[$name] = $value;
	}
	
	public function getOption($name, $default = null)
	{
		return array_key_exists($name, $this->options)? $this->options[$name] : $default;
	}

	public function getJavascript($options = array())
	{
		$oldOptions = $this->options;
		$this->options = array_merge($this->options, $options);
		
		$return = '
			google.load("maps", "2");
			var '.$this->getOption('js_name').' = null;
			google.setOnLoadCallback(function() {'.$this->getOption('js_name').' = new '.$this->getOption('js_class').'('.json_encode($this->getOption('form_fields')).', unescape("'.rawurlencode($this->getOption('query_format')).'"), '.$this->getOption('callback').')});
			document.onunload="GUnload()";
		';
		
		$this->options = $oldOptions;
		
		return $return;
	}
}
