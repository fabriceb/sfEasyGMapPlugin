<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * GMapCachedGeocode filter form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseGMapCachedGeocodeFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'lon'      => new sfWidgetFormFilterInput(),
      'lat'      => new sfWidgetFormFilterInput(),
      'accuracy' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'lon'      => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'lat'      => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'accuracy' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('g_map_cached_geocode_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'GMapCachedGeocode';
  }

  public function getFields()
  {
    return array(
      'address'  => 'Text',
      'lon'      => 'Number',
      'lat'      => 'Number',
      'accuracy' => 'Number',
    );
  }
}
