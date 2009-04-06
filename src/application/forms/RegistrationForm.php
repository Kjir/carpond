<?php

/**
 * RegistrationForm is the form to signup to the site
 */
class RegistrationForm extends Zend_Form {
	/**
	 * init the form with all its components
	 */
	public function init() {
		$this->setMethod('post');

		//username
		$this->addElement('text', 'username', array(
			'label' => 'Nome utente:',
			'required' => true,
			'filters' => array('StringTrim'),
			'validators' => array('Alnum', array('validator' => 'StringLength', 'options' => array(1,25)))
		));

		//password
		$this->addElement('password', 'password', array(
			'label' => 'Password:',
			'required' => true
		));

		//First name
		$this->addElement('text', 'first_name', array(
			'label' => 'Nome:',
			'required' => true,
			'validators' => array('Alpha', array('validator' => 'StringLength', 'options' => array(1,100)))
		));

		//Last name
		$this->addElement('text', 'last_name', array(
			'label' => 'Cognome:',
			'required' => true,
			'validators' => array('Alpha', array('validator' => 'StringLength', 'options' => array(1,100)))
		));

		//Licence
		$this->addElement('checkbox', 'licence', array(
			'label' => 'Hai a disposizione la patente?'
		));

		//Own car
		$this->addElement('checkbox', 'own_car', array(
			'label' => 'Hai a disposizione un tuo mezzo?'
		));

		//Submit
		$this->addElement('submit', 'submit', array(
			'label' => 'Registrati'
		));
	}
}
