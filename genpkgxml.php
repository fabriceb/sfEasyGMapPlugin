<?php
/**
 * Generates and updates a package.xml file
 * dependencies : PEAR_PackageFileManager 1.6+
 * @author Laurent Bachelier <laurent@bachelier.name>
 */

error_reporting(E_ALL); // no E_STRICT
require_once('PEAR/PackageFileManager2.php');
PEAR::setErrorHandling(PEAR_ERROR_DIE);

$packagexml = new PEAR_PackageFileManager2;
$packagexml->setOptions(
array('baseinstalldir' => '/',
 'packagedirectory' => dirname(__FILE__),
 'filelistgenerator' => 'file',
 'ignore' => array('TODO'),
 'exceptions' => array('README' => 'doc', 'LICENSE' => 'doc'),
));

$packagexml->setPackage('sfEasyGMapPlugin');
$packagexml->setSummary('An easy, object-oriented, PHP abstraction of the Google Maps API');
$packagexml->setDescription('The sfEasyGMap plugin provides helpers and an objet-oriented PHP abstraction to the Google Maps API to ease the process of adding a Google Map and customising it in your symfony projects.');
$packagexml->setChannel('plugins.symfony-project.org');
$packagexml->addMaintainer('lead', 'fabriceb', 'Fabrice Bernhard', 'fabriceb@theodo.fr');
$packagexml->addMaintainer('developer', 'vincentg', 'Vincent Guillon', 'vincentg@theodo.fr');
$packagexml->addMaintainer('developer', 'laurentb', 'Laurent Bachelier', 'laurentb@theodo.fr');
$packagexml->addMaintainer('developer', 'chok', 'Maxime Picaud', 'chokorp@gmail.com');

$packagexml->setLicense('MIT License', 'http://www.symfony-project.org/license');

// This will ADD a changelog entry to an existing package.xml
$packagexml->setAPIVersion('3.1.0');
$packagexml->setReleaseVersion('3.1.0');
$packagexml->setNotes('
 * corrected package.xml
 * added GMapDirections functionalities
');

$packagexml->setReleaseStability('stable');
$packagexml->setAPIStability('stable');
$packagexml->addRelease();
$packagexml->setPackageType('php');
$packagexml->setPhpDep('5.2.0');
$packagexml->setPearinstallerDep('1.4.1');

// Supported versions of Symfony
$packagexml->addPackageDepWithChannel('required', 'symfony', 'pear.symfony-project.com', '1.0.0', '1.3.0');

$packagexml->generateContents(); // Add the files

if (isset($_GET['make']) || (isset($_SERVER['argv']) && @$_SERVER['argv'][1] == 'make'))
  $packagexml->writePackageFile();
else
  $packagexml->debugPackageFile();

