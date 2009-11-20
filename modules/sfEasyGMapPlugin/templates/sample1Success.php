<?php use_helper('Javascript','GMap') ?>

<div class="sample-message">
  <a href="/sfEasyGMapPlugin/index">Samples list</a> > <?php echo $message ?>
</div>

<div>
  <div class="sample-map">
    <div id="map-search">
      <div id="map_search_title">
        Search on the map :
      </div>
      
      <div id="map_search_form">
        <?php include_search_location_form() ?>
      </div>
      
      <div style="clear: both;"></div>
    </div>
  
    <?php include_map($gMap); ?>
    
    <div class="console">
      <span id="console_title">Console</span>
      <div id="console_div"></div>
    </div>
  </div>

  <div class="sample-sources">  
    <div id="sample-source-action">
      <a href="#" onclick="gmapSample_Toggle('action_source'); return false;">&bull; <?php echo "Display/Hide action source" ?></a>
      
      <div id="action_source">
        <?php echo preg_replace('/.*(\/\/.*)<br \/>.*/', '<span class="sample-comment">$0</span>', nl2br($action_source)) ?>
      </div>
    </div>
    
    <div id="sample-source-js">
      <a href="#" onclick="gmapSample_Toggle('generated-js'); return false;">&bull; <?php echo "Display/Hide generated javascript" ?></a>
      
      <div id="generated-js">
        <?php echo preg_replace('/.*(\/\/.*)<br \/>.*/', '<span class="sample-comment">$0</span>', nl2br($generated_js)) ?>
      </div>
    </div>
    
    <?php if (isset($view_panel)): ?>
      <div id="sample-direction-pane">
        <div id="direction_pane">
        </div>
      </div>
    <?php endif; ?>
  </div>
  
  <div style="clear: both;"></div>
</div>
<!-- Javascript included at the bottom of the page -->
<?php include_map_javascript($gMap); ?>
