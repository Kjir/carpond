<?php

class LogoutController extends Zend_Controller_Action {

	public function indexAction() {
		Zend_Auth::getInstance()->clearIdentity();
		$this->_helper->redirector('index', 'index');
	}
}
