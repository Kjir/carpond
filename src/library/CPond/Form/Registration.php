<?php

/**
 * RegistrationForm is the form to signup to the site
 */
class CPond_Form_Registration extends Zend_Dojo_Form {
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

		//Phone
		$this->addElement('text', 'phone', array(
							 'label' => 'Telefono:',
							 'class' => 'text',
							 'required' => false,
							 'validators' => array('Digits')
							 ));

		//E-mail
		$this->addElement('text', 'email', array(
							 'label' => 'E-mail:',
							 'class' => 'text',
							 'required' => false,
							 'validators' => array('EmailAddress')
							 ));

		//Live town
		$db = Zend_Db_Table::getDefaultAdapter();
		$values = $db->fetchPairs( "SELECT code, name FROM Municipality" );
		$values = array_merge(array('0' => ''), $values);

		$this->addElement('FilteringSelect', 'live_municipality', array(
			'label' => 'Inserisci il comune:',
			'autocomplete' => false,
			'multiOptions' => $values,
			'validators' => array(array('validator' => 'StringLength', 'options' => array(6,6))),
			'required' => false,
			'onChange' => 'fetchTowns(this,"live");'
		));

		$this->addElement('FilteringSelect', 'live_town', array(
								       'label' => 'Inserisci la frazione:',
								       'autocomplete' => false,
								       'storeId' => 'townStore',
								       'storeType' => 'dojo.data.ItemFileReadStore',
								       'storeParams' => array(
											      'clearOnClose' => true
											      ),
								       'djitParams' => array(
											     'searchAttr' => 'name'
											     )
								       ));

		//Work town
		$this->addElement('FilteringSelect', 'work_municipality', array(
										'label' => 'Inserisci il comune:',
										'autocomplete' => true,
										'multiOptions' => $values,
										'validators' => array(array('validator' => 'StringLength', 'options' => array(6,6))),
										'required' => false,
										'onChange' => 'fetchTowns(this,"work")'
										));
		$this->addElement('FilteringSelect', 'work_town', array(
									'label' => 'Inserisci la frazione',
									'autocomplete' => true,
									'storeId' => 'workTownStore',
									'storeType' => 'dojo.data.ItemFileReadStore',
									'storeParams' => array(
											       'clearOnClose' => true
											       ),
									'djitParams' => array(
											      'searchAttr' => 'name'
											      )
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

		//Group elements together
		$this->addDisplayGroup(
				       array('username', 'password', 'first_name', 'last_name', 'phone', 'email'),
				       'personal',
				       array('legend' => 'Dati personali')
		);

		$this->addDisplayGroup(
				       array('live_municipality', 'live_town'),
				       'live_place',
				       array('legend' => 'Dove abiti?')
				       );

		$this->addDisplayGroup(
				       array('work_municipality', 'work_town'),
				       'work_place',
				       array('legend' => 'Dove lavori?')
				       );
		$this->addDisplayGroup(
				       array('licence', 'own_car', 'submit'),
				       'other',
				       array('legend' => 'Altre informazioni')
				       );

	}

	public function setTownsUrl($url) {
	  $params = $this->getElement('live_town')->getStoreParams();
	  $params['url'] = $url;
	  $this->getElement('live_town')->setStoreParams($params);
	  $params = $this->getElement('work_town')->getStoreParams();
	  $params['url'] = $url;
	  $this->getElement('work_town')->setStoreParams($params);
	}
}
