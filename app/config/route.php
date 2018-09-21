<?php

$config['route']['get'] = [
  '/style/(:any)/'   => 'document@document@style',
  '/script/(:any)/'  => 'document@document@script',
  '/library/(:any)/' => 'document@document@library',
  '/fonts/(:any)/'   => 'document@document@font',
  '/image/(:any)/'   => 'document@document@image',
  '/channel/(:any)/' => 'document@explorer@index'
];

$config['route']['post'] = [
  '/api/station/getall' 	=>  'actions@gstation@index',
  '/api/station/getremain' 	=>  'actions@gstation@filter',
  '/api/control/add' 	    =>  'actions@actions@create',
  '/api/control/remove' 	=>  'actions@actions@delete',
  '/api/control/edit' 	    =>  'actions@actions@modify',
  '/api/control/get' 	    =>  'actions@actions@gain'
];