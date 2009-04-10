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

		//Live town
		$db = Zend_Db_Table::getDefaultAdapter();
		$values = $db->fetchPairs( "SELECT code, name FROM Municipality" );
		$values = array_merge(array('0' => ''), $values);

		$this->addElement('FilteringSelect', 'live_municipality', array(
			'label' => 'Inserisci il comune in cui abiti:',
			'autocomplete' => false,
			'multiOptions' => $values,
			'validators' => array(array('validator' => 'StringLength', 'options' => array(6,6))),
			'required' => true,
			'onChange' => 'fetchTowns(this,"live");'
		));

		$this->addElement('FilteringSelect', 'live_town', array(
								       'label' => 'Inserisci la località in cui abiti:',
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
										'label' => 'Inserisci il comune in cui lavori:',
										'autocomplete' => true,
										'multiOptions' => $values,
										'validators' => array(array('validator' => 'StringLength', 'options' => array(6,6))),
										'required' => true,
										'onChange' => 'fetchTowns(this,"work")'
										));
		$this->addElement('FilteringSelect', 'work_town', array(
									'label' => 'Inserisci la località dove lavori',
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
				       array('username', 'password', 'first_name', 'last_name', 'live_municipality', 'live_town', 'work_municipality', 'work_town', 'licence', 'own_car', 'submit'),
			'signup',
			array('legend' => 'Registrazione')
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
