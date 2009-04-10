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
				'live_town' => $values['live_town'],
				'work_town' => $values['work_town']
				);

		  if(!empty($values['phone'])) {
		    $data['phone'] = $values['phone'];
		  }
		  if(!empty($values['email'])) {
		    $data['email'] = $values['email'];
		  }
		  if(!empty($values['licence'])) {
		    $data['licence'] = true;
		  }
		  if(!empty($values['own_car'])) {
		    $data['own_car'] = true;
		  }

		  $table->insert($data);

		  $this->view->successful = true;
		} else {
		  $this->view->form = $form;
		}
	}

	/**
	 * _getRegistrationForm() returns an instance of RegistrationForm
	 */
	protected function _getRegistrationForm() {
	        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/registration.js', 'text/javascript');
		$form = new CPond_Form_Registration();
		$form->setAction($this->_helper->url('signup'));
		$selected = array();
		$post = $this->getRequest()->getPost();
		if( !empty($post['live_municipality']) ) {
		  $selected['live_municipality'] = $post['live_municipality'];
		}
		if( !empty($post['work_municipality']) ) {
		  $selected['work_municipality'] = $post['work_municipality'];
		}
		$form->setTownsUrl($this->_helper->url('towns'), $selected);
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
