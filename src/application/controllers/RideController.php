<?php

class RideController extends Zend_Controller_Action {
    public function indexAction() {
    }

    public function searchAction() {
        $form = $this->_getSearchForm();
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/ride.js', 'text/javascript');
        $this->view->form = $form;

        if( $this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()) ) {
            $values = $form->getValues();
            require_once APPLICATION_PATH . "/model/Ride.php";
            $table = new Ride;
            $query = $table->select()->from($table, array('*', 'CONVERT(repeatable, UNSIGNED) AS repeatable'));
            $query->setIntegrityCheck(false);
            $query->join(array('dep' => 'Town'), 'dep_town = dep.id', array('name AS dep_town_name'));
            $query->join(array('arr' => 'Town'), 'arr_town = arr.id', array('name AS arr_town_name'));

            if( $values['repeatable'] == "0" ) {
                $query->where('repeatable = FALSE');
                if( !empty($values['date']) ) {
                    $query->where('date = ?', $values['date']);
                }
            } else {
                $query->where('repeatable = TRUE');
                if(!empty($values['weekdays']) ) {
                    foreach($values['weekdays'] as $d ) {
                        $query->where("FIND_IN_SET(?, weekdays)", $d);
                    }
                }
            }

            if( !empty($values['dep_town']) ) {
                $query->where('dep_town = ?', $values['dep_town']);
            }

            if( !empty($values['arr_town']) ) {
                $query->where('arr_town = ?', $values['arr_town']);
            }

            if( !empty($values['dep_time']) ) {
                $query->where('dep_time = ?', substr($values['dep_time'], 1));
            }

            if( !empty($values['arr_time']) ) {
                $query->where('arr_time = ?', substr($values['arr_time'], 1));
            }

            $this->view->results = $query->query()->fetchAll();
            $this->view->form = null;
        }
    }

    public function insertAction() {
        $form = $this->_getInsertForm();
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/ride.js', 'text/javascript');
        $this->view->form = $form;
        if( $this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()) ) {
            $values = $form->getValues();
            $data['type'] = $values['type'];
            $data['repeatable'] = $values['repeatable'];
            if( $data['type'] == 'OFFER' ) {
                $data['num_spots'] = $values['num_spots'];
            }
            if( $data['repeatable'] == "1" ) {
                $data['repeatable'] = true;
                $data['weekdays'] = implode(",", $values['weekdays']);
            } else {
                unset($data['repeatable']);
                $data['date'] = $values['date'];
            }
            $data['dep_time'] = substr($values['dep_time'], 1);
            $data['arr_time'] = substr($values['arr_time'], 1);
            $data['dep_town'] = $values['dep_town'];
            $data['arr_town'] = $values['arr_town'];
            $data['purpose'] = $values['purpose'];
            $data['user'] = Zend_Auth::getInstance()->getIdentity()->username;
            require_once APPLICATION_PATH . "/model/Ride.php";
            $table = new Ride();
            $table->insert($data);
            $this->view->form = null;
            if($data['type'] == 'NEED') {
                $this->view->rideNeedInserted = true;
            } else {
                $this->view->rideOfferInserted = true;
            }
        }
    }

    public function joinAction() {
        $id = $this->_getParam("id");
        if(!empty($id)) {
            require_once APPLICATION_PATH . "/model/UserRide.php";
            $table = new UserRide;
            $user = Zend_Auth::getInstance()->getIdentity()->username;
            $data['user'] = $user;
            $data['ride'] = $id;
            try {
                $table->insert($data);
            } catch( Zend_Db_Exception $ex ) {
                $this->view->alreadyIn = true;
                return;
            }
            $db = $table->getAdapter();
            $date = new Zend_Date();
            $mess['sender'] = $user;
            $mess['receiver'] = $db->fetchOne($db->quoteInto("SELECT user FROM Ride WHERE id = ?", $id));
            $mess['date'] = $date->getIso();
            $mess['subject'] = "Richiesta partecipazione a viaggio";
            $mess['text'] = "Hai ricevuto una richiesta di partecipazione al tuo viaggio da parte di $user. Se vuoi accettare <a href=\"" . $this->_helper->url('confirm', 'ride', 'default', array('username' => $user, 'ride' => $id)) . "\">clicca qui</a>";
            require_once APPLICATION_PATH . "/model/Message.php";
            $table = new Message();
            $table->insert($mess);
        }
    }

    public function confirmAction() {
        $ride = $this->_getParam("ride");
        $user = $this->_getParam("username");
        if(!empty($ride) && !empty($user)) {
            require_once APPLICATION_PATH . "/model/UserRide.php";
            $table = new UserRide;
            $table->update(array('confirmation' => true), array($table->getAdapter()->quoteInto("ride = ?", $ride), $table->getAdapter()->quoteInto( "user = ?",  $user)));
            $date = new Zend_Date();
            $owner = Zend_Auth::getInstance()->getIdentity()->username;
            $mess['sender'] = $owner;
            $mess['receiver'] = $user;
            $mess['date'] = $date->getIso();
            $mess['subject'] = "Conferma partecipazione a viaggio";
            $mess['text'] = "$owner ha accettato la tua partecipazione al suo viaggio. Contattalo per accordarvi sui dettagli!";
            require_once APPLICATION_PATH . "/model/Message.php";
            $table = new Message();
            $table->insert($mess);
        }
    }

    protected function _getInsertForm() {
        $form = new CPond_Form_Ride_Insert();
        $form->setAction($this->_helper->url('insert'));

        $db = Zend_Db_Table::getDefaultAdapter();
        $dep_town = Zend_Auth::getInstance()->getIdentity()->live_town;
        $arr_town = Zend_Auth::getInstance()->getIdentity()->work_town;
        $dep_municipality = $db->fetchOne($db->quoteInto("SELECT code FROM Town WHERE id = ?", $dep_town));
        $arr_municipality = $db->fetchOne($db->quoteInto("SELECT code FROM Town WHERE id = ?", $arr_town));

        $selected = array();
        $post = $this->getRequest()->getPost();
        if( !empty($post['dep_municipality']) ) {
            $selected['dep_municipality'] = $post['dep_municipality'];
        } else {
            $selected['dep_municipality'] = $dep_municipality;
            $selected['dep_town'] = $dep_town;
        }
        if( !empty($post['arr_municipality']) ) {
            $selected['arr_municipality'] = $post['arr_municipality'];
        } else {
            $selected['arr_municipality'] = $arr_municipality;
            $selected['arr_town'] = $arr_town;
        }
        $form->setTownsUrl($this->_helper->url('towns', 'signup'), $selected);
        return $form;
    }

    protected function _getSearchForm() {
        $form = new CPond_Form_Ride_Search();
        $form->setAction($this->_helper->url('search'));

        $db = Zend_Db_Table::getDefaultAdapter();
        $dep_town = Zend_Auth::getInstance()->getIdentity()->live_town;
        $arr_town = Zend_Auth::getInstance()->getIdentity()->work_town;
        $dep_municipality = $db->fetchOne($db->quoteInto("SELECT code FROM Town WHERE id = ?", $dep_town));
        $arr_municipality = $db->fetchOne($db->quoteInto("SELECT code FROM Town WHERE id = ?", $arr_town));

        $selected = array();
        $post = $this->getRequest()->getPost();
        if( !empty($post['dep_municipality']) ) {
            $selected['dep_municipality'] = $post['dep_municipality'];
        } else {
            $selected['dep_municipality'] = $dep_municipality;
            $selected['dep_town'] = $dep_town;
        }
        if( !empty($post['arr_municipality']) ) {
            $selected['arr_municipality'] = $post['arr_municipality'];
        } else {
            $selected['arr_municipality'] = $arr_municipality;
            $selected['arr_town'] = $arr_town;
        }
        $form->setTownsUrl($this->_helper->url('towns', 'signup'), $selected);
        return $form;
    }
}
