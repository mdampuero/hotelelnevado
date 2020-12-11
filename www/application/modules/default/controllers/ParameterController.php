<?php

require_once 'Contact.php';

class ParameterController extends Zend_Controller_Action {

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
        $this->contact = new Model_DBTable_Contact();
        if ($this->view->parameters["lang"] == "en") {
            $this->view->sufix = "En";
        }
    }

    public function bankAction() {
        $this->view->contact = $this->contact->get(1);
    }

    public function cardAction() {
        $this->view->contact = $this->contact->get(1);
    }

    public function mpAction() {
        $this->view->contact = $this->contact->get(1);
    }

}
