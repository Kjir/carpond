<?php

class UserController extends Zend_Controller_Action {

  public function profileAction() {
    $username = $this->_getParam('username');
    if(!empty($username)) {
      $db = Zend_Db_Table::getDefaultAdapter();
      $this->view->user = $db->fetchRow($db->quoteInto("SELECT username, first_name, last_name, CONVERT(licence, UNSIGNED) licence, CONVERT(own_car, UNSIGNED) own_car FROM User WHERE username = ?", $username));
      require_once APPLICATION_PATH . "/model/Evaluation.php";
      $eval = new Evaluation;
      $this->view->evaluations = $eval->fetchAll($eval->select()->from($eval, array('date', 'rating', 'motivation', 'evaluator'))->where('evaluated_user = ?', $username))->toArray();
    }
    $this->view->evaluationInserted = $this->_getParam('evaluationInserted');
  }

  public function evaluateAction() {
    $username = $this->_getParam('username');
    if(!empty($username)) {
      $form = $this->_getEvaluationForm($username);
      if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
	$values = $form->getValues();
	require_once APPLICATION_PATH . "/model/Evaluation.php";
	$table = new Evaluation;
	$date = new Zend_Date();
	$data['date'] = $date->getIso();
	$data['rating'] = $values['rating'];
	$data['motivation'] = $values['motivation'];
	$data['evaluator'] = Zend_Auth::getInstance()->getIdentity()->username;
	$data['evaluated_user'] = $username;
	$table->insert($data);
	$this->_helper->redirector('profile', 'user', 'default', array('username' => $username, 'evaluationInserted' => true));
      }
      $this->view->form = $form;
    }
  }

  protected function _getEvaluationForm($username) {
    $form = new CPond_Form_Evaluation();
    $form->setAction($this->_helper->url('evaluate', 'user', 'default', array('username' => $username)));
    return $form;
  }
}