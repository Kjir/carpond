<?php

class MessageController extends Zend_Controller_Action {
	public function indexAction() {
	  $this->view->form = $this->_getMessageForm();
	}

	protected function _getMessageForm() {
	  $form = new CPond_Form_Message;
	  $form->setAction($this->_helper->url('index'));
	  return $form;
	}
}
