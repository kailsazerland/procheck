<?php


App::uses('AppController', 'Controller');

class MainaController extends AppController {
	public $uses = array();
        public $layout = 'maina-layout';

	public function show() {
	    //echo 123;
            $this->ajaxRender();
	}

}
