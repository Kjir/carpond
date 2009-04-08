<?php

/**
 * The LoginController handles user access to the site
 */
class LoginController extends Zend_Controller_Action {

	public function indexAction() {
		$form = $this->_getLoginForm();

		if(!$this->getRequest()->isPost() || !$form->isValid($_POST)) {
			$this->view->loginForm = $form;
			return;
		}

		$val = $form->getValues();

		$adapter = new Zend_Auth_Adapter_DbTable( Zend_Db_Table::getDefaultAdapter() );
		$adapter->setTableName('User');
		$adapter->setIdentityColumn('username');
		$adapter->setCredentialColumn('password');
		$adapter->setIdentity($val['username']);
		$adapter->setCredential(sha1($val['password']));

		$auth = Zend_Auth::getInstance();
		$result = $auth->authenticate($adapter);

		if($result->isValid()) {
			// Do not store password for security purposes
			$auth->getStorage()->write($adapter->getResultRowObject(null, 'password'));
			$this->_helper->redirector('index','index');
		} else {
			$this->view->failed = true;
			$this->view->loginForm = $form;
		}
	}


	protected function _getLoginForm() {
		$form = new CPond_Form_Login();
		$form->setAction($this->_helper->url('index'));
		return $form;
	}
}
