<?php

class CPond_Form_Evaluation extends Zend_Dojo_Form {
  public function init() {
    $this->setMethod('post');
    //TODO: Maybe find a central, smarter way to specify ratings?
    $ratings = array(
		     'VERYBAD' => 'Molto negativa',
		     'BAD' => 'Negativa',
		     'NORMAL' => 'Normale',
		     'GOOD' => 'Buona',
		     'VERYGOOD' => 'Molto buona'
		     );
    $this->addElement('FilteringSelect', 'rating', array(
							 'label' => 'Valutazione:',
							 'required' => true,
							 'autocomplete' => true,
							 'multiOptions' => $ratings,
							 'validators' => array(array('validator' => 'InArray', 'options' => array(array_keys($ratings))))
							 ));
    $this->addElement('Textarea', 'motivation', array(
						      'label' => 'Motivazione:',
						      'required' => 'true',
						      //FIXME: HtmlEntities filter screws utf-8
						      //'filters' => array('HtmlEntities')
						      ));
    $this->addElement('submit', 'submit', array(
						'label' => 'Valuta!'
						));
  }
}