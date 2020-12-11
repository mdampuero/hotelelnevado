<?php

require_once 'Sliders.php';
require_once 'Common.php';

class Admin_SliderController extends Zend_Controller_Action {

    var $fields = array(
        array('field' => 'IDSlider', 'label' => 'N° Imagen', 'list' => true, 'width' => 100, 'class' => 'id', 'order' => true),
        array('field' => 'SName', 'label' => 'Nombre', 'required' => true, 'list' => true, 'search' => true, 'order' => true),
        array('field' => 'SImage', 'label' => 'Vista Previa', 'required' => true, 'list' => true, 'search' => true, 'order' => true, 'image' => true, 'path_image' => '/images/slider/'),
    );
    var $actions = array(
        array('type' => 'link', 'label' => 'Agregar nueva imagen', 'icon' => 'plus', 'controller' => 'slider', 'action' => 'add'),
        array('type' => 'link', 'label' => 'Mostrar todas las imágenes', 'icon' => 'list', 'controller' => 'slider', 'action' => 'index'),
        array('type' => 'link', 'label' => 'Cambiar el orden de las imágenes', 'icon' => 'sort-by-alphabet', 'controller' => 'slider', 'action' => 'order'),
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
        $this->view->parameters = $this->parameters;
        $this->view->controller = $this->_getParam('controller');
        $this->view->menuCurrent = 'slider';
        $this->view->currentIcon = 'glyphicon glyphicon-camera';
        $this->view->currentBrand = 'Slider de Imágenes';

        //Messages (error and success)
        $this->errorMessage = 'Ocurrio algun error en la ejecución de la transacción.';
        $this->newSuccessMessage = '<b>Información:</b> Iamgen guardada con éxito!';
        $this->editSuccessMessage = '<b>Información:</b> Imagen editada con éxito!';
        $this->deleteSuccessMessage = '<b>Información:</b> Imagen eliminada con éxito!';
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
        $this->slider = new Model_DBTable_Sliders();
        $this->form = new Zend_Form();
    }

    public function indexAction() {
        if (!empty($this->parameters['search'])) {
            $statusBar = 'info';
            $this->view->search = $this->parameters['search'];
            $where = create_where($this->parameters['search'], $this->fields);
        }

        try {

            $results_all = $this->slider->showAll($where, $this->parameters['sort'], $this->parameters['order']);
            $paginator = Zend_Paginator::factory($results_all);
            $paginator->setItemCountPerPage(COUNTPERPAGE)
                    ->setCurrentPageNumber($this->_getParam('page', 1))
                    ->setPageRange(PAGERANGE);

            $this->view->results = $paginator;
            $this->view->enableSearch = true;
        } catch (Zend_Exception $exc) {
            echo $exc->getMessage();
            exit();
        }

        //Sending params and vars to view
        $this->view->messages = $this->messages;
        if ($statusBar) {
            $this->view->statusBar = $statusBar;
        }
    }

    public function addAction() {

        //Accion con POST
        if ($this->getRequest()->isPost()) {

            //Llamo al modelo y guardo los datos
            try {
                $this->errorMessage = "Formato de imagen no soportado";
                if ($extension = is_valid_image($_FILES["SImage"]["name"])) {
                    $name = uniqid() . "." . $extension;
                    move_uploaded_file($_FILES["SImage"]["tmp_name"], $this->path . $name);
                    smart_resize_image($this->path . $name, 940, 0, true, $this->path . "b_" . $name, false, false);
                    smart_resize_image($this->path . $name, 520, 0, true, $this->path . "m_" . $name, false, false);
                    smart_resize_image($this->path . $name, 100, 0, true, $this->path . "s_" . $name, false, false);
                    unlink($this->path . $name);
                }
                $arrayInsert = array('SImage' => $name, 'SText' => $_POST["SText"], 'STextEn' => $_POST["STextEn"], 'SStatus' => 1, 'SName' => $_POST["SName"], 'SNameEn' => $_POST["SNameEn"]);
                $id = $this->slider->add($arrayInsert);
            } catch (Zend_Exception $exc) {
                //Redirecciono al index con error. Evita colgar la aplicación
                $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $this->errorMessage));
                $this->_redirector->gotoSimple('index', null, null, array('status' => 'danger'));
            }

            //Manejo avisos y redirecciono
            $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => $this->newSuccessMessage));
            $this->_redirector->gotoSimple('index', null, null, array('status' => 'success'));
        }

        //idFormulario para validacion
        $this->view->formId = 'userForm';

        //Icono, titulo y descripcion para el formulario
        $this->view->icon = 'plus';
        $this->view->title = 'Agregar nueva imagen';
        $this->view->description = 'Complete el formulario para agregar una nueva imagen para el Slider de la Home';

        //Envio mensajes nuevos a la vista
        $this->view->messages = $this->messages;
    }

    public function viewAction() {
        $this->view->h2 = 'Área N° ' . $this->parameters["id"];
        $this->view->result = $this->slider->get($this->parameters["id"]);
    }

    public function editAction() {
        if ($this->getRequest()->isPost()) {
            try {
                $this->errorMessage = "Formato de imagen no soportado";
                if ($_FILES["SImage"]["name"]) {
                    if ($extension = is_valid_image($_FILES["SImage"]["name"])) {
                        unlink($this->path . "b_" . $_POST["SImageOld"]);
                        unlink($this->path . "m_" . $_POST["SImageOld"]);
                        unlink($this->path . "s_" . $_POST["SImageOld"]);
                        $name = uniqid() . "." . $extension;
                        move_uploaded_file($_FILES["SImage"]["tmp_name"], $this->path . $name);
                        smart_resize_image($this->path . $name, 940, 0, true, $this->path . "b_" . $name, false, false);
                        smart_resize_image($this->path . $name, 520, 0, true, $this->path . "m_" . $name, false, false);
                        smart_resize_image($this->path . $name, 100, 0, true, $this->path . "s_" . $name, false, false);
                        unlink($this->path . $name);
                    }
                } else {
                    $name = $_POST["SImageOld"];
                }
                $arrayUpdate = array('SImage' => $name, 'SText' => $_POST["SText"], 'STextEn' => $_POST["STextEn"], 'SStatus' => 1, 'SName' => $_POST["SName"], 'SNameEn' => $_POST["SNameEn"]);
                $this->slider->edit($arrayUpdate, $this->parameters["id"]);
            } catch (Zend_Exception $exc) {
                //Redirecciono al index con error. Evita colgar la aplicación
                $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $this->errorMessage));
                $this->_redirector->gotoSimple('index', null, null, array('status' => 'danger'));
            }
            //Manejo avisos y redirecciono
            $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => $this->newSuccessMessage));
            $this->_redirector->gotoSimple('index', null, null, array('status' => 'success'));
        }

        //idFormulario para validacion
        $this->view->formId = 'userForm';

        //Icono, titulo y descripcion para el formulario
        $this->view->icon = 'pencil';
        $this->view->title = 'Editar Imagen';
        $this->view->description = 'Vea y modifique el formulario para la imagen del Slider de la Home';
        $this->view->result = $this->slider->get($this->parameters["id"]);

        //Envio mensajes nuevos a la vista
        $this->view->messages = $this->messages;
    }

    public function deleteAction() {
        $id = $this->_getParam('id', 0);
        if ($id > 0) {
            $this->view->result = $this->slider->get($id);
        } else {
            $this->_redirect($this->parameters["module"] . '/' . $this->parameters["controller"]);
        }
        if ($this->getRequest()->isPost()) {
            if ($this->form->isValid($this->getRequest()->getPost())) {
                $delete_request = $this->getRequest()->getPost('delete_request');

                if ($delete_request == "Yes") {
                    try {
                        unlink($this->path . "b_" . $this->view->result["SImage"]);
                        unlink($this->path . "m_" . $this->view->result["SImage"]);
                        unlink($this->path . "s_" . $this->view->result["SImage"]);
                        $this->view->result = $this->slider->delete_row($id);
                    } catch (Zend_Exception $exc) {
                        $this->_helper->flashMessenger->addMessage(array('type' => 'error', 'message' => $this->errorMessage));
                        $this->_redirector->gotoSimple('index', null, null, array('status' => 'danger'));
                    }
                    $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => $this->deleteSuccessMessage));
                    $this->_redirector->gotoSimple('index', null, null, array('status' => 'success'));
                } else if ($delete_request == "No") {
                    $this->_helper->flashMessenger->addMessage(array('type' => 'warning', 'message' => $this->cancelledMessage));
                    $this->_redirector->gotoSimple('index', null, null, array('status' => 'warning'));
                }
            }
        }
        //Icono, titulo y descripcion para el formulario
        $this->view->icon = 'ban-circle';
        $this->view->title = 'Eliminar Imagen';
        $this->view->description = 'Seleccione una opción';
    }

    public function detailAction() {
        $this->view->result = $this->slider->get($this->parameters["id"]);
    }

    public function orderAction() {
        try {
            if ($this->getRequest()->isPost()) {
                if ($this->form->isValid($this->getRequest()->getPost())) {
                    krsort($_POST["IDSlider"]);
                    $SOrder=0;
                    foreach($_POST["IDSlider"] as $key => $id){
                        $SOrder++;
                        $this->slider->edit(array('SOrder'=>$SOrder), $id);
                    }
                    $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => "El orden de las Imágenes se cambio correctamente"));
                    $this->_redirector->gotoSimple('index', null, null, array('status' => 'success'));
                }
            }
            $this->view->icon = 'sort-by-alphabet';
            $this->view->title = 'Cambiar el orden de las imágenes';
            $this->view->description = 'Utilice las flechas para cambiar el orden de las imágenes';
            $results_all = $this->slider->showAll();
            $this->view->results = $results_all;
        } catch (Zend_Exception $exc) {
            echo $exc->getMessage();
            exit();
        }
    }

}
