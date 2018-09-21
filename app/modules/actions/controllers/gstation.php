<?php

namespace Evie\App\Modules\Actions\Controllers;

use Evie\System\Kernel\Controller;
use Evie\System\Kernel\Loader;

class Gstation extends Controller  {

  protected $rest = null;
  protected $station = null;

  public function __construct() {
      
    parent::__construct();
    $this->rest  	= Loader::Library( 'rest' );
	$this->station 	= Loader::Model( 'station' );
    Loader::Helper( 'date' );

  }
  
  public function Index() {
  
    $data = $this->station->getAll(  );
  
    if( !$data['cities'] || !$data['numbers'] || !$data['regions'] )  {

      $Response = $this->rest->Response(
        ['status' => 'error', 'message' => 'No records found'],
        $this->rest->GetStatus( 'HTTP_NOT_FOUND' ) 
      );
  
    } else {
  
      $Response = $this->rest->Response(
        ['status' => 'success', 'data'   => $data],
		$this->rest->GetStatus( 'HTTP_OK' ) 		
      );
  
    }
    
    return $Response;

  }

  public function Filter() {
  
	$params = [
		'number'	=> !empty( $this->Post('number') ) ? $this->Post('number') : false,
		'city' 		=> !empty( $this->Post('city') )   ? $this->Post('city')   : false,
		'region' 	=> !empty( $this->Post('region') ) ? $this->Post('region') : false,
		'street' 	=> !empty( $this->Post('street') ) ? $this->Post('street') : false
	];
  
    $data = $this->station->getRemain( $params, 20 );
  
    if( empty($data) )  {

      $Response = $this->rest->Response(
        ['status' => 'error', 'message' => 'No mathces, please try another combination'],
        $this->rest->GetStatus( 'HTTP_NOT_FOUND' ) 
      );
  
    } else {
  
      $Response = $this->rest->Response(
        ['status' => 'success', 'data'   => $data],
		$this->rest->GetStatus( 'HTTP_OK' ) 		
      );
  
    }
    
    return $Response;

  }
  
}