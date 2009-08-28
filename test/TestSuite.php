<?php

$test_files = glob(dirname(__FILE__)."/unit/*Test.php");
foreach($test_files as $test_file)
{
  include($test_file);
}