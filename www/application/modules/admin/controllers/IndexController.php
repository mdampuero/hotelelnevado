<?php

class Admin_IndexController extends Zend_Controller_Action {

    public function init() {
        $options = array(
            'layout' => 'admin',
            'layoutPath' => '../application/layouts/scripts/'
        );
        Zend_Layout::startMvc($options);

        $storage = new Zend_Auth_Storage_Session();
        $data = $storage->read();
        if (!$data) {
            $this->_redirect('/admin/login');
        }

        $this->response = $this->getResponse();
        $this->view->menuCurrent = 'user';
        $this->response->insert('menuLogin', $this->view->render('/menuLogin.phtml'));
        $this->response->insert('menu', $this->view->render('/menu.phtml'));
    }

    public function indexAction() {
        $this->_redirect('/admin/room');
    }

    public function presskitAction() {
        
    }

}

