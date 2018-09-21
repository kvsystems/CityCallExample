<?php

namespace Evie\App\Controllers;

use Evie\System\Kernel\Loader;
use Evie\System\Template\Template;
use Evie\App\Core\AppController;

class Main extends AppController  {

    protected $units = null;

	public function __construct() {

        Loader::Language('main');
        parent::__construct();

	}

	public function Index()  {

		Template::View( "index", $this->data );
    
	}


}