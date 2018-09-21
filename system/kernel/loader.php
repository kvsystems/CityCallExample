<?

namespace Evie\System\Kernel;

use Evie\System\Config\Config;

class Loader  {

  const TCATCH   = 'trycatch';
  const ALOAD    = 'autoload';
  const MOD_DIR  = 'modules';
  const MDL_DIR  = 'models';
  const HLP_DIR  = 'helpers';
  const LNG_DIR  = 'language';
  const LIB_DIR  = 'libraries';
  const FILE_EXT = '.php';  

  public $config;

  public function __construct() {
    
    self::Helper( self::TCATCH );
    self::Autoload();
    
  }

  public static function Language( $name, $lang = 'ru_RU' ) {

      $File = implode(
          array( APP_DIR, self::LNG_DIR, DS, $lang, DS, strtolower( $name ), self::FILE_EXT )
      );

      if( is_file( $File ) ) {

          require_once( $File );

      } else {

          $_SESSION['message'] = 'Language File Missing';
          redirect( 'error' );

      }

  }

  public static function Model( $name ) {
    
    if( strpos( $name, '/' ) !== false ) {
      
      $Name = explode( '/', $name );
      
      require_once( implode( array(      
        APP_DIR, self::MOD_DIR, DS, $Name[0], DS, 
        self::MDL_DIR, DS, strtolower( $Name[1] ), self::FILE_EXT
      )));

      $Module    = ucfirst( $Name[0] );
      $Model     = ucfirst( $Name[1] );

      $Class = "Evie\\App\\Modules\\$Module\\Models\\$Model";
      $Instance = new $Class;

    } else {

      require_once( implode( array(
        APP_DIR, self::MDL_DIR, DS, strtolower( $name ), self::FILE_EXT 
      )));

      $Name = ucfirst( $name );
      $Class = "\\Evie\\App\\Models\\$Name";
      $Instance = new $Class;

    }

    return $Instance;
    
  }

  public static function Helper( $name )  {
    
    $File = implode(
      array( APP_DIR, self::HLP_DIR, DS, strtolower( $name ), self::FILE_EXT )
    );

    if( is_file( $File ) ) {
      
      require_once( $File );
      
    } else {
    
      $_SESSION['message'] = 'Helper File Missing';
      redirect( 'error' );      
    
    }

  }


  public static function Library()  {
        
    $Params = func_get_args();
    
    if( strpos( $Params[0],'/') !== false )  {
      
      $Name = explode( '/',$Params[0] );      
      $File = implode( array(
        APP_DIR, self::MOD_DIR, DS, $Name[0], DS, 
        self::LIB_DIR, DS, $Name[1], self::FILE_EXT
      ));
      $Helper = $Name[1];
      
    } else {
      
      $File = implode( array( 
        APP_DIR, self::LIB_DIR, DS, $Params[0], self::FILE_EXT
      ));
      $Helper = $Params[0];
     
    }

    if( is_file( $File ) ) {
      
      require_once($File);
      
    } else {
      
      $_SESSION['error_message'] = "Library File : {$Params[0]} Missing";
      redirect( 'error' );
      
    }
        
    return count( $Params ) > 1
      ? new $Helper( $params[1] ) 
      : new $Helper();

    }


    public function Database()  {

    }

    public static function Config( $name )  {

      return new Config( $name );
        
  }

  public static function Autoload() {

    $Config = new Config( self::ALOAD );
    $Autoload = $Config::Get();

    if( !isset( $Autoload ) ) return;

    if( isset( $Autoload['libraries'] ) ) {
      
      foreach( $Autoload['helpers'] as $Item )
          self::Library( $Item );
          
    }
    
    if( isset( $Autoload['helpers'] ) ) {
      
      foreach( $Autoload['helpers'] as $Item )
          self::Helper( $Item );
          
    }        
    
    if( isset( $Autoload['models'] ) ) {
      
      foreach( $Autoload['models'] as $Item )
          self::Model( $Item );
          
    }
      
  }

}