<?php

class Model_DBTable_Rooms extends Zend_Db_Table_Abstract {

    protected $_name = 'room';
    protected $primary = 'IDRoom';
    protected $defultSort = 'ROrder';
    protected $defultOrder = 'DESC';

    public function init() {

        $this->_redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
        $this->_flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
        $this->_request = Zend_Controller_Front::getInstance()->getRequest();
    }

    public function showAll($where = null, $sort = null, $order = null) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array($this->_name), array("*","CONCAT ('$ ',RTarifer) as RTarifer","RTarifer as RTariferNumber"));
        //$select->joinLeft(array('image'), 'image.IDRoom='.$this->_name.'.IDRoom', array('image.IName'));
        $where = ($where == null) ? '1' : $where;
        $sort = ($sort == null) ? $this->defultSort : $sort;
        $order = ($order == null) ? $this->defultOrder : $order;
        $select->where($where);
        $select->order(array($sort . ' ' . $order));
        // $select->group("room.IDRoom");
        $results = $this->fetchAll($select);
        return $results->toArray();
    }

    public function add($parameters) {
        $id = $this->insert($parameters);
        if ($id > 0) {
            return $id;
        } else {
            $this->_flashMessenger->addMessage(array('type' => 'fail', 'message' => 'Error - Cannot insert the new registry'));
            $this->_redirector->gotoSimple('add', $this->_request->getParam('controller'), $this->_request->getParam('module'));
        }
        return ($id > 0) ? $id : -1;
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

    public function delete_row($id) {
        return $this->delete($this->primary . ' = ' . (int) $id);
    }

}

?>