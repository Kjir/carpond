<?php

class CPond_Form_Message extends Zend_Dojo_Form {
  public function init() {
    $this->setMethod('post');

    $db = Zend_Db_Table::getDefaultAdapter();
    $users = $db->fetchPairs("SELECT username AS id, username FROM User");
    $options = array_merge(array("Scrivi il nome utente"), $users );
    $this->addElement('FilteringSelect','username', array(
							 'label' => 'Destinatario:',
							 'required' => true,
							 'autocomplete' => true,
							 'multiOptions' => $options,
							 'validators' => array( array('validator' => 'inArray', 'options' => array($users)) ),
							 'onFocus' => 'if(dijit.byId("username").attr("value") == "0") dijit.byId("username").attr("displayedValue", "")'
							 ));
    $this->addElement('ValidationTextBox', 'subject', array(
						  'label' => 'Oggetto:',
						  'required' => true,
						  'validators' => array(array('validator' => 'StringLength', 'options' => array(0,255)))
							    ));
    $this->addElement('Textarea', 'message', array(
						   'label' => 'Testo:',
						   'required' => true,
						   'filters' => array('HtmlEntities')
						   ));
    $this->addElement('submit', 'submit', array(
					       'label' => 'Invia'
					       ));
  }
}