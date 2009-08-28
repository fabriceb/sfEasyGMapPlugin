<?php

/**
 * GMapCachedGeocode form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseGMapCachedGeocodeForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'address'  => new sfWidgetFormInputHidden(),
      'lon'      => new sfWidgetFormInput(),
      'lat'      => new sfWidgetFormInput(),
      'accuracy' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'address'  => new sfValidatorPropelChoice(array('model' => 'GMapCachedGeocode', 'column' => 'address', 'required' => false)),
      'lon'      => new sfValidatorNumber(array('required' => false)),
      'lat'      => new sfValidatorNumber(array('required' => false)),
      'accuracy' => new sfValidatorNumber(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('g_map_cached_geocode[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'GMapCachedGeocode';
  }


}
