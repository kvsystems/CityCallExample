<?

namespace Evie\App\Core;

use Evie\System\Kernel\Controller;

class AppController extends Controller  {

    protected $data = [];

    public static $dirs = [
      'assets'    => APP_DIR . 'views' . DS . 'assets' . DS,
      'styles'    => APP_DIR . 'views' . DS . 'assets' . DS . 'styles' . DS,
      'scripts'   => APP_DIR . 'views' . DS . 'assets' . DS . 'scripts' . DS,
      'libraries' => APP_DIR . 'views' . DS . 'assets' . DS . 'libraries' . DS,
      'image'     => APP_DIR . 'views' . DS . 'assets' . DS . 'image' . DS,
      'fonts'     => APP_DIR . 'views' . DS . 'assets' . DS . 'fonts' . DS
    ];

    public function __construct() {
        
        parent::__construct();
        global $lang;
        $this->data = ['lang' => $lang];
        
    }
    
    public static function GetDirs() {
      
      return self::$dirs;
      
    }

}