<?php

/**
 * CPond_Form_Login creates the user login form
 */
class CPond_Form_Login extends Zend_Form {
	public function init() {
		$this->setMethod('post');

		//username
		$this->addElement('text', 'username', array(
			'label' => 'Nome utente:',
			'class' => 'text',
			'required' => true,
			'filters' => array('StringTrim'),
			'validators' => array('Alnum', array('validator' => 'StringLength', 'options' => array(2,25)))
		));

		//password
		$this->addElement('password', 'password', array(
			'label' => 'Password:',
			'class' => 'text',
			'required' => true
		));

		//submit
		$this->addElement('submit', 'submit', array('label' => 'Login'));

		$this->addDisplayGroup(array('username','password','submit'), 'login', array('legend'=>'Login'));
	}
}
