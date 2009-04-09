<?php

/**
 * RegistrationForm is the form to signup to the site
 */
class CPond_Form_Registration extends Zend_Form {
	/**
	 * init the form with all its components
	 */
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

		//First name
		$this->addElement('text', 'first_name', array(
			'label' => 'Nome:',
			'class' => 'text',
			'required' => true,
			'validators' => array('Alpha', array('validator' => 'StringLength', 'options' => array(1,100)))
		));

		//Last name
		$this->addElement('text', 'last_name', array(
			'label' => 'Cognome:',
			'class' => 'text',
			'required' => true,
			'validators' => array('Alpha', array('validator' => 'StringLength', 'options' => array(1,100)))
		));

		//Live town
		$db = Zend_Db_Table::getDefaultAdapter();
		$values = $db->fetchPairs( "SELECT code, name FROM Municipality" );
		$values = array_merge(array('0' => 'Seleziona il comune'), $values);
		$select = new Zend_Form_Element_Select('live_municipality', array(
			'label' => 'Seleziona il comune in cui abiti:',
			'validators' => array(array('validator' => 'StringLength', 'options' => array(6,6)))
		));
		$select->setMultiOptions($values);
		$this->addElement($select);

		//Work town
		$select = new Zend_Form_Element_Select('work_municipality', array(
			'label' => 'Seleziona il comune in cui lavori:',
			'validators' => array(array('validator' => 'StringLength', 'options' => array(6,6)))
		));
		$select->setMultiOptions($values);
		$this->addElement($select);


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

		//Group elements together
		$this->addDisplayGroup(
			array('username', 'password', 'first_name', 'last_name', 'live_municipality', 'work_municipality', 'licence', 'own_car', 'submit'),
			'signup',
			array('legend' => 'Registrazione')
		);
	}
}
