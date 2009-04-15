<?php

class CPond_Form_Ride_Search extends Zend_Dojo_Form {
    public function init() {
        $this->setMethod('post');

        $this->addElement('RadioButton', 'repeatable', array(
                              'label' => 'Che tipo di passaggio cerchi?',
                              'multiOptions' => array(
                                  0 => 'Occasionale',
                                  1 => 'Periodico'
                              ),
                              'value' => 0,
                              'onClick' => 'rideDateChange(this);'
                          ));

        $this->addElement('DateTextBox', 'date', array(
                              'label' => 'Data di partenza:',
                              'required' => false
                          ));

        $this->addElement('multiselect', 'weekdays', array(
                              'label' => 'Giorni di validità:',
                              'required' => false,
                              'multiOptions' => array(
                                  'MONDAY' => 'Lunedì',
                                  'TUESDAY' => 'Martedì',
                                  'WEDNESDAY' => 'Mercoledì',
                                  'THURSDAY' => 'Giovedì',
                                  'FRIDAY' => 'Venerdì',
                                  'SATURDAY' => 'Sabato',
                                  'SUNDAY' => 'Domenica'
                              ),
                              'size' => 5,
                              'multiple' => true
                          ));

        $this->addDisplayGroup(array('date'), 'ride_date', array('legend' => 'Data'));
        $this->addDisplayGroup(array('weekdays'), 'ride_days', array('legend' => 'Periodo'));

        $db = Zend_Db_Table::getDefaultAdapter();
        $values = $db->fetchPairs( "SELECT code, name FROM Municipality" );

        //Departure town
        $this->addElement('FilteringSelect', 'dep_municipality', array(
                              'label' => 'Inserisci il comune di partenza:',
                              'autocomplete' => false,
                              'multiOptions' => $values,
                              'validators' => array(array('validator' => 'StringLength', 'options' => array(6,6))),
                              'required' => false,
                              'onChange' => 'fetchTowns(this,"dep");'
                          ));

        $this->addElement('FilteringSelect', 'dep_town', array(
                              'label' => 'Inserisci la località di partenza:',
                              'autocomplete' => false,
                              'storeId' => 'depTownStore',
                              'storeType' => 'dojo.data.ItemFileReadStore',
                              'storeParams' => array(
                                  'clearOnClose' => true
                              ),
                              'dijitParams' => array(
                                  'searchAttr' => 'name'
                              )
                          ));

        //Departure time
        $this->addElement('TimeTextBox', 'dep_time', array(
                              'label' => 'Orario di partenza:',
                              'required' => false,
                              'clickableIncrement' => 'T00:15:00',
                              'visibleIncrement' => 'T00:15:00'
                          ));

        $this->addDisplayGroup(array('dep_municipality', 'dep_town', 'dep_time'), 'departure', array('legend' => 'Partenza'));

        //Arrival town
        $this->addElement('FilteringSelect', 'arr_municipality', array(
                              'label' => 'Inserisci il comune di arrivo:',
                              'autocomplete' => false,
                              'multiOptions' => $values,
                              'validators' => array(array('validator' => 'StringLength', 'options' => array(6,6))),
                              'required' => false,
                              'onChange' => 'fetchTowns(this,"arr");',
                          ));

        $this->addElement('FilteringSelect', 'arr_town', array(
                              'label' => 'Inserisci la località di arrivo:',
                              'autocomplete' => false,
                              'storeId' => 'arrTownStore',
                              'storeType' => 'dojo.data.ItemFileReadStore',
                              'storeParams' => array(
                                  'clearOnClose' => true
                              ),
                              'dijitParams' => array(
                                  'searchAttr' => 'name'
                              )
                          ));
        //Arrival time
        $this->addElement('TimeTextBox', 'arr_time', array(
                              'label' => 'Orario di arrivo:',
                              'required' => false,
                              'clickableIncrement' => 'T00:15:00',
                              'visibleIncrement' => 'T00:15:00'
                          ));

        $this->addDisplayGroup(array('arr_municipality', 'arr_town', 'arr_time'), 'arrival', array('legend' => 'Arrivo'));

        $this->addElement('submit', 'submit', array('label' => 'Cerca'));
    }

    public function setTownsUrl($url, $selected = array()) {
        $params = $this->getElement('dep_town')->getStoreParams();
        if( !empty($selected['dep_municipality']) ) {
            $params['url'] = $url . "/municipality/" . $selected['dep_municipality'];
            if( !empty($selected['dep_town']) ) {
                $this->getElement("dep_municipality")->setValue($selected['dep_municipality']);
                $this->getElement("dep_town")->setValue($selected['dep_town']);
            }
        } else {
            $params['url'] = $url;
        }
        $this->getElement('dep_town')->setStoreParams($params);
        $params = $this->getElement('arr_town')->getStoreParams();
        if( !empty($selected['arr_municipality']) ) {
            $params['url'] = $url . "/municipality/" . $selected['arr_municipality'];
            if( !empty($selected['arr_town']) ) {
                $this->getElement("arr_municipality")->setValue($selected['arr_municipality']);
                $this->getElement("arr_town")->setValue($selected['arr_town']);
            }
        } else {
            $params['url'] = $url;
        }
        $this->getElement('arr_town')->setStoreParams($params);
    }
}
