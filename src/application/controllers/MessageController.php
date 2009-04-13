<?php

class MessageController extends Zend_Controller_Action {
  /**
   * indexAction lists available messages and allows to send a message to someone else
   */
	public function indexAction() {
	  $form = $this->_getMessageForm();
	  require_once APPLICATION_PATH . "/model/Message.php";
	  $table = new Message;
	  $messages = $table->fetchAll($table->select()->from($table, array('id', 'date', 'sender', 'subject'))->where('receiver = ?', Zend_Auth::getInstance()->getIdentity()->username));
	  $this->view->messages = $messages->toArray();
	  if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
	    $values = $form->getValues();
	    $date = new Zend_Date();
	    $data['date'] = $date->getIso();
	    $data['subject'] = $values['subject'];
	    $data['text'] = $values['message'];
	    $data['sender'] = Zend_Auth::getInstance()->getIdentity()->username;
	    $data['receiver'] = $values['username'];
	    $table->insert($data);
	    $this->_helper->redirector('index', 'message', 'default', array('sent' => true));
	  }
	  $this->view->messageSent = $this->_getParam('sent');
	  $this->view->messageDeleted = $this->_getParam('deleted');
	  $this->view->form = $form;
	}

	/**
	 * readAction is used to read a message
	 */
	public function readAction() {
	  $id = $this->_getParam("id");
	  require_once APPLICATION_PATH . "/model/Message.php";
	  $table = new Message;
	  $message = $table->fetchRow($table->select()->from($table, array('id', 'date', 'sender', 'subject', 'text'))->where('id = ?', $id));
	  $this->view->message = $message;
	}

	/**
	 * deleteAction removes a message
	 */
	public function deleteAction() {
	  $id = $this->_getParam("id");
	  if(!empty($id)) {
	    require_once APPLICATION_PATH . "/model/Message.php";
	    $table = new Message;
	    $table->delete($table->getAdapter()->quoteInto('id = ?', $id));
	    $this->_helper->redirector('index', 'message', 'default', array('deleted' => true));
	    return;
	  }
	  $this->_helper->redirector('index', 'message');
	}

	protected function _getMessageForm() {
	  $form = new CPond_Form_Message;
	  $form->setAction($this->_helper->url('index'));
	  return $form;
	}
}
