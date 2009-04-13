<?php

class CPond_Acl extends Zend_Acl {
	public function __construct(Zend_Auth $auth) {
		//Add resources
		$this->add(new Zend_Acl_Resource('ride'));
		$this->add(new Zend_Acl_Resource('message'));
		$this->add(new Zend_Acl_Resource('user'));

		//Add roles
		//Role #1: Guest, alias unauthenticated users
		$this->addRole(new Zend_Acl_Role('guest'));
		//Role #2: Authenticated users
		$this->addRole(new Zend_Acl_Role('user'), 'guest');

		//Access rules
		$this->allow('guest', 'ride', array('view','search'));
		$this->allow('guest', 'user', array('profile'));
		$this->allow('user', 'ride', array('insert', 'join'));
		$this->allow('user', 'message');
		$this->allow('user', 'user', array('evaluate'));
	}
}
