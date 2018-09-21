<?php

namespace Evie\App\Modules\Actions\Controllers;

use Evie\System\Kernel\Controller;
use Evie\System\Kernel\Loader;

class Actions extends Controller  {

  const COUNT_LIMIT = 5;

  protected $rest    = null;
  protected $station = null;
  private $_requiredParameters = [
    'number_azs'  => false,
    'city_azs'    => false,
    'street_azs'  => false,
    'region_azs'  => false
  ];
  private $_counter = 1;

  public function __construct() {

    parent::__construct();
    $this->station 	= Loader::Model( 'station' );
    $this->rest     = Loader::Library( 'rest' );

  }

  private function _checkId()    {
      return !empty($this->Post('station_id')) && $this->Post('station_id')
          ? $this->Post('station_id') : false;
  }

  private function _checkParameters()    {
      foreach( $this->_requiredParameters as $key => $value )   {
          if( !empty($this->Post($key)) && $this->Post($key) )  {
              $this->_requiredParameters[$key] = $value;
          } else $this->_counter++;
      }
  }

  public function Gain()  {
      $stationId = $this->_checkId();
      if( !$stationId ) {
          return $Response = $this->rest->Response(
              ['status' => 'error', 'message' => 'Required parameter not set: station_id'],
              $this->rest->GetStatus( 'HTTP_METHOD_NOT_ALLOWED' )
          );
      }

      $stationData = $this->station->getStationById();
      if( !$stationData )  {
          return $this->rest->Response(
              ['status' => 'error', 'message' => 'No records found'],
              $this->rest->GetStatus( 'HTTP_NOT_FOUND' )
          );
      }

      if( !is_array( $stationData ) )  {
          return $this->rest->Response(
              ['status' => 'error', 'message' => 'The format of the expected answer does not match'],
              $this->rest->GetStatus( 'HTTP_INTERNAL_SERVER_ERROR' )
          );
      }

      return $this->rest->Response(
          ['status' => 'success', 'data'   => $stationData],
          $this->rest->GetStatus( 'HTTP_OK' )
      );
  }

  public function Delete()  {
      $stationId = $this->_checkId();
      if( !$stationId ) {
          return $Response = $this->rest->Response(
              ['status' => 'error', 'message' => 'Required parameter not set: station_id'],
              $this->rest->GetStatus( 'HTTP_METHOD_NOT_ALLOWED' )
          );
      }

      $processResult = $this->station->removeStationById();
      if( !$processResult ) {
          return $this->rest->Response(
              ['status' => 'error', 'message' => 'No records found to remove'],
              $this->rest->GetStatus( 'HTTP_NOT_FOUND' )
          );
      }

      return $this->rest->Response(
          ['status' => 'success', 'message'   => 'Recording deleted successfully: ' . $stationId ],
          $this->rest->GetStatus( 'HTTP_OK' )
      );
  }

  public function Modify()  {
      $stationId = $this->_checkId();
      if( !$stationId ) {
          return $Response = $this->rest->Response(
              ['status' => 'error', 'message' => 'Required parameter not set: station_id'],
              $this->rest->GetStatus( 'HTTP_METHOD_NOT_ALLOWED' )
          );
      }

      $this->_checkParameters();
      if( $this->_counter == self::COUNT_LIMIT ) {
          return $Response = $this->rest->Response(
              ['status' => 'error', 'message' => 'No parameters submitted'],
              $this->rest->GetStatus( 'HTTP_METHOD_NOT_ALLOWED' )
          );
      }

      $processResult = $this->station->modifyStationById( $stationId, $this->_requiredParameters );
      if( !$processResult ) {
          return $this->rest->Response(
              ['status' => 'error', 'message' => 'No records found to remove'],
              $this->rest->GetStatus( 'HTTP_NOT_FOUND' )
          );
      }

      return $this->rest->Response(
          ['status' => 'success', 'message'   => 'Station successfully updated: ' . $stationId ],
          $this->rest->GetStatus( 'HTTP_OK' )
      );
  }

  public function Create() {
      $this->_checkParameters();
      if( !in_array(false, $this->_requiredParameters ) ) {

          $errorParams = [];
          foreach( $this->_requiredParameters as $key => $value )
              $errorParams[] = $key;

          return $Response = $this->rest->Response(
              ['status' => 'error', 'message' => 'No parameters submitted: ' . implode(', ', $errorParams )],
              $this->rest->GetStatus( 'HTTP_METHOD_NOT_ALLOWED' )
          );
      }

      $insertId = $this->station->createStation( $this->_requiredParameters );
      if( !$insertId )  {
          return $this->rest->Response(
              ['status' => 'error', 'message' => 'Erroneous write completion'],
              $this->rest->GetStatus( 'HTTP_INTERNAL_SERVER_ERROR' )
          );
      }

      return $this->rest->Response(
          ['status' => 'success', 'message'   => 'Station successfully added: ' . $insertId ],
          $this->rest->GetStatus( 'HTTP_OK' )
      );
  }

  
}