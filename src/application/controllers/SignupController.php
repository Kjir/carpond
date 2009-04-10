<?php

/**
 * SignupController is the controller to signup to the site
 */
class SignupController extends Zend_Controller_Action {
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
	        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/registration.js', 'text/javascript');
		$form = new CPond_Form_Registration();
		$form->setAction($this->_helper->url('signup'));
		$form->setTownsUrl($this->_helper->url('towns'));
		return $form;
	}

	public function townsAction() {
	  $municipality = $this->_getParam('municipality');
	  $db = Zend_Db_Table::getDefaultAdapter();
	  $towns = $db->fetchAll("SELECT id, name FROM Town WHERE code = ?", $municipality);
	  $data = new Zend_Dojo_Data('id', $towns);
	  $data->setLabel('name');
	  $this->_helper->json($data);
	}
}
