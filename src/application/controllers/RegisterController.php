<?php

/**
 * RegisterController is the controller to signup to the site
 */
class RegisterController extends Zend_Controller_Action {
	/**
	 * The index action is the starting point for the registration process, show the form
	 */
	public function indexAction() {
		$form = $this->_getRegistrationForm();
		$this->view->form = $form;
	}

	public function signupAction() {
		$form = $this->_getRegistrationForm();
		if( $form->isValid($this->getRequest()->getPost()) ) {
			$this->view->text = 'Successo!';
		}
		$this->view->form = $form;
	}

	/**
	 * _getRegistrationForm() returns an instance of RegistrationForm
	 */
	protected function _getRegistrationForm() {
		require_once APPLICATION_PATH . '/forms/RegistrationForm.php';
		$form = new RegistrationForm();
		$form->setAction($this->_helper->url('signup'));
		return $form;
	}
}
