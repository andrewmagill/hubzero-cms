<?php
// This file generated by Propel convert-props target on Tue May 18 14:04:12 2010
// from XML runtime conf file /www/neeshub/api/org/phpdb/propel/central/./runtime-conf.xml
return array (
  'log' => 
  array (
    'type' => 'file',
    'name' => '/www/neeshub/logs/propel.log',
    'ident' => 'propel-central',
    'level' => '7',
  ),
  'propel' => 
  array (
    'datasources' => 
    array (
      'NEEScentral' => 
      array (
        'adapter' => 'oracle',
        'connection' => 
//        array (
//          'phptype' => 'oracle',
//          'database' => '',
//          'hostspec' => 'ORCLSTG',
//          'username' => 'central',
//          'password' => 'bees4nees',
//        ),

        array (
          'phptype' => 'oracle',
          'database' => '',
          'hostspec' => 'NEESPROD',
          'username' => 'central',
          'password' => 'bees4nees',
        ),
      ),
      'default' => 'NEEScentral',
    ),
  ),
);
