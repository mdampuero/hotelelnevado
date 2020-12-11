<?php

class Model_DBTable_Contact extends Zend_Db_Table_Abstract {

    protected $_name = 'contact';
    protected $primary = 'IDContact';
    protected $defultSort = 'IDContact';
    protected $defultOrder = 'DESC';

    public function init() {

        $this->_redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
        $this->_flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
        $this->_request = Zend_Controller_Front::getInstance()->getRequest();
    }

    public function edit($parameters, $id) {
        if ($id > 0) {
            $this->update($parameters, $this->primary . ' = ' . (int) $id);
        } else {
            $this->_flashMessenger->addMessage(array('type' => 'fail', 'message' => 'Error - Cannot insert the registry'));
            $this->_redirector->gotoSimple('index', $this->_request->getParam('controller'), $this->_request->getParam('module'));
        }
    }

    public function get($id) {
        $id = (int) $id;
        $row = $this->fetchRow($this->primary . ' = ' . $id);
        if (!$row) {
            $this->_flashMessenger->addMessage(array('type' => 'fail', 'message' => 'Error - Cannot find the registry'));
            $this->_redirector->gotoSimple('index', $this->_request->getParam('controller'), $this->_request->getParam('module'));
        }
        return $row->toArray();
    }

}

?>