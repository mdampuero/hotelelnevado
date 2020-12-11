<?php

require_once 'Images.php';
require_once 'Rooms.php';
require_once 'Common.php';

class RoomController extends Zend_Controller_Action {

    var $helper;
    var $helperMail;

    public function init() {
        $options = array(
            'layout' => 'default_popup',
            'layoutPath' => '../application/layouts/scripts/'
        );
        Zend_Layout::startMvc($options);
        $this->response = $this->getResponse();
        $this->parameters = $this->_request->getParams();
        $this->view->parameters = $this->parameters;
        $this->image = new Model_DBTable_Images();
        $this->room = new Model_DBTable_Rooms();
    }

    public function getAction() {
        
        $this->view->result = $this->room->get($this->parameters["id"]);
        $this->view->images = $this->image->showAll("IDRoom=" . $this->parameters["id"]);
        
    }

}
