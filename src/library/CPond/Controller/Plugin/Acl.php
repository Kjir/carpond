<?php

class CPond_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract {
	protected $_auth;
	protected $_acl;

	public function __construct(Zend_Auth $auth, Zend_Acl $acl) {
		$this->_auth = $auth;
		$this->_acl = $acl;
	}

	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		if($this->_auth->hasIdentity()) {
			$role = 'user';
			$this->loggedUser = true;
		} else {
			$role = 'guest';
		}

		$resource = $request->controller;
		$privilege = $request->action;

		if($this->_acl->has($resource) && !$this->_acl->isAllowed($role, $resource, $privilege)) {
			if($this->_auth->hasIdentity()) { //User is logged but unauthorized
				$request->setModuleName('default');
				$request->setControllerName('notallowed');
				$request->setActionName('index');
			} else { //User is not logged
				$request->setModuleName('default');
				$request->setControllerName('login');
				$request->setActionName('index');
			}
		}
	}
}
