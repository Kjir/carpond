<?php

class CPond_View_Helper_BaseUrl extends Zend_View_Helper_Abstract {
	public function baseUrl() {
		return Zend_Controller_Front::getInstance()->getBaseUrl();
	}
}
