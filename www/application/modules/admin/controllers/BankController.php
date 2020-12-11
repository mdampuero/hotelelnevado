<?php

require_once 'Contact.php';
require_once 'Common.php';

class Admin_BankController extends Zend_Controller_Action {

    var $fields = array(
        array('field' => 'IDSlider', 'label' => 'N° Imagen', 'list' => true, 'width' => 100, 'class' => 'id', 'order' => true),
        array('field' => 'SName', 'label' => 'Nombre', 'required' => true, 'list' => true, 'search' => true, 'order' => true),
        array('field' => 'SImage', 'label' => 'Vista Previa', 'required' => true, 'list' => true, 'search' => true, 'order' => true, 'image' => true, 'path_image' => '/images/slider/'),
    );
    var $actions = array(
        array('type' => 'link', 'label' => 'Editar datos de Medios de Pago', 'icon' => 'edit', 'controller' => 'bank', 'action' => 'edit')
    );
    var $options = array(
        array('type' => 'link', 'title' => 'Ver más', 'icon' => 'eye-open text-primary', 'controller' => 'slider', 'action' => 'detail'),
        array('type' => 'link', 'title' => 'Editar', 'icon' => 'edit text-primary', 'controller' => 'slider', 'action' => 'edit'),
        array('type' => 'link', 'title' => 'Eliminar', 'icon' => 'ban-circle text-danger', 'controller' => 'slider', 'action' => 'delete')
    );
    var $users;

    public function init() {
        //Datos de login y sesion
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
        $this->view->ULoginname = $data->ULoginname;
        $this->view->UName = $data->UName;
        $this->view->ULastname = $data->ULastname;
        $this->view->IDUser = $data->IDUser;

        //Vars and params
        $this->parameters = $this->_request->getParams();
        $this->parameters['id'] = 1;
        $this->view->parameters = $this->parameters;
        $this->view->controller = $this->_getParam('controller');
        $this->view->menuCurrent = 'slider';
        $this->view->currentIcon = 'glyphicon glyphicon-usd';
        $this->view->currentBrand = 'Datos bancarios';

        //Messages (error and success)
        $this->errorMessage = 'Ocurrio algun error en la ejecución de la transacción.';
        $this->newSuccessMessage = '<b>Información:</b> Página guardada con éxito!';
        $this->editSuccessMessage = '<b>Información:</b> Página editada con éxito!';
        $this->deleteSuccessMessage = '<b>Información:</b> Iamgen eliminada con éxito!';
        $this->cancelledMessage = '<b>Información:</b> Transacción cancelada.';

        //Fields, actions, options
        $this->view->fields = $this->fields;
        $this->view->actions = $this->actions;
        $this->view->options = $this->options;

        //Helpers
        $this->response = $this->getResponse();
        $this->_redirector = $this->_helper->getHelper('Redirector');
        $this->messages = $this->_helper->flashMessenger->getMessages();
        $statusBar = $this->_getParam('status');
        $this->view->statusBar = $statusBar;

        //Partials
        $this->response->insert('menuLogin', $this->view->render('/menuLogin.phtml'));
        $this->response->insert('menu', $this->view->render('/menu.phtml'));
        $this->response->insert('paginationControl', $this->view->render('/paginationControl.phtml'));

        //Some extra scripts
        $this->path = PUBLIC_PATH . DS . "images" . DS . "slider" . DS;
        //Models
        $this->contact = new Model_DBTable_Contact();
        $this->form = new Zend_Form();
    }

    public function indexAction() {
        $this->_redirect('admin/bank/edit/id/1');
    }

    public function editAction() {
        if ($this->getRequest()->isPost()) {
            try {
                $arrayUpdate = array(
                    'CBank' => $_POST["CBank"],
                    'CBankEn' => ($_POST["CBankEn"]),
                    'CCard' => $_POST["CCard"],
                    'CCardEn' => ($_POST["CCardEn"]),
                    'CMp' => $_POST["CMp"],
                    'CMpEn' => ($_POST["CMpEn"]),
                    'CNote' => $_POST["CNote"],
                    'CNoteEn' => ($_POST["CNoteEn"])
                );
                $this->contact->edit($arrayUpdate, $this->parameters["id"]);
            } catch (Zend_Exception $exc) {
                //Redirecciono al index con error. Evita colgar la aplicación
                $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage()));
                $this->_redirector->gotoSimple('edit', null, null, array('status' => 'danger'));
            }
            //Manejo avisos y redirecciono
            $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => $this->newSuccessMessage));
            $this->_redirector->gotoSimple('edit', null, null, array('status' => 'success'));
        }

        //idFormulario para validacion
        $this->view->formId = 'userForm';

        //Icono, titulo y descripcion para el formulario
        $this->view->icon = 'pencil';
        $this->view->title = 'Editar datos de medios de Pago';
        $this->view->description = 'Por favor ingrese los datos según sus medios de Pago';
        $this->view->result = $this->contact->get($this->parameters["id"]);

        //Envio mensajes nuevos a la vista
        $this->view->messages = $this->messages;
    }

}
