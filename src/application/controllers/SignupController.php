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
		  $values = $form->getValues();
		  require_once APPLICATION_PATH . '/model/User.php';
		  $table = new User();
		  $data = array(
				'username' => $values['username'],
				'password' => sha1($values['password']),
				'first_name' => $values['first_name'],
				'last_name' => $values['last_name'],
				'phone' => $values['phone'],
				'email' => $values['email'],
				'live_town' => $values['live_town'],
				'work_town' => $values['work_town'],
				'licence' => $values['licence'],
				'own_car' => $values['own_car']
				);
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
