<?php

require_once 'Rooms.php';
require_once 'Images.php';
require_once 'Common.php';

class Admin_RoomController extends Zend_Controller_Action {

    var $fields = array(
        array('field' => 'IDRoom', 'label' => 'N° ', 'list' => true, 'width' => 100, 'class' => 'id', 'order' => true),
        array('field' => 'RName', 'label' => 'Nombre', 'required' => true, 'list' => true, 'search' => true, 'order' => true),
        array('field' => 'RTarifer', 'label' => 'Precio por Noche', 'required' => true, 'list' => true, 'search' => true, 'order' => true, 'class' => 'id'),
        array('field' => 'RDescription', 'label' => 'Descripcion', 'required' => true, 'list' => true, 'search' => true, 'order' => true),
    );
    var $actions = array(
        array('type' => 'link', 'label' => 'Agregar nueva habitación', 'icon' => 'plus', 'controller' => 'room', 'action' => 'add'),
        array('type' => 'link', 'label' => 'Mostrar todas las habitaciones', 'icon' => 'list', 'controller' => 'room', 'action' => 'index'),
        array('type' => 'link', 'label' => 'Cambiar el orden de las habitaciones', 'icon' => 'sort-by-alphabet', 'controller' => 'room', 'action' => 'order'),
    );
    var $options = array(
        array('type' => 'link', 'title' => 'Ver más', 'icon' => 'eye-open text-primary', 'controller' => 'room', 'action' => 'detail'),
        array('type' => 'link', 'title' => 'Editar', 'icon' => 'edit text-primary', 'controller' => 'room', 'action' => 'edit'),
        array('type' => 'link', 'title' => 'Eliminar', 'icon' => 'ban-circle text-danger', 'controller' => 'room', 'action' => 'delete')
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
        $this->view->currentIcon = 'glyphicon glyphicon-briefcase';
        $this->view->currentBrand = 'Habitaciones';

        //Messages (error and success)
        $this->errorMessage = 'Ocurrio algun error en la ejecución de la transacción.';
        $this->newSuccessMessage = '<b>Información:</b> Habitación guardada con éxito!';
        $this->editSuccessMessage = '<b>Información:</b> Habitación editada con éxito!';
        $this->deleteSuccessMessage = '<b>Información:</b> Habitación eliminada con éxito!';
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
        $this->room = new Model_DBTable_Rooms();
        $this->image = new Model_DBTable_Images();
        $this->form = new Zend_Form();
    }

    public function indexAction() {
        if (!empty($this->parameters['search'])) {
            $statusBar = 'info';
            $this->view->search = $this->parameters['search'];
            $where = create_where($this->parameters['search'], $this->fields);
        }

        try {

            $results_all = $this->room->showAll($where, $this->parameters['sort'], $this->parameters['order']);
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
                $arrayInsert = array('RName' => $_POST["RName"],'RNameEn' => $_POST["RNameEn"], 'RTarifer' => $_POST["RTarifer"], 'RDescription' => $_POST["RDescription"], 'RDescriptionEn' => $_POST["RDescriptionEn"]);
                $id = $this->room->add($arrayInsert);
                if ($_POST["images"]) {
                    $order = 0;
                    foreach ($_POST["images"] as $images) {
                        $order++;
                        $this->image->add(array('IName' => $images, 'IOrder' => $order, 'IDRoom' => $id));
                    }
                }
            } catch (Zend_Exception $exc) {
                //Redirecciono al index con error. Evita colgar la aplicación
                $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage()));
                $this->_redirector->gotoSimple('index', null, null, array('status' => 'danger'));
            }

            //Manejo avisos y redirecciono
            $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => $this->newSuccessMessage));
            $this->_redirector->gotoSimple('index', null, null, array('status' => 'success'));
        }

        //idFormulario para validacion
        $this->view->formId = 'userForm';
        $this->view->folder = uniqid();
        //Icono, titulo y descripcion para el formulario
        $this->view->icon = 'plus';
        $this->view->title = 'Agregar nueva habitación';
        $this->view->description = 'Complete el formulario para agregar una nueva habitación';

        //Envio mensajes nuevos a la vista
        $this->view->messages = $this->messages;
    }

    public function editAction() {
        if ($this->getRequest()->isPost()) {
            try {
                $arrayUpdate = array('RName' => $_POST["RName"], 'RNameEn' => $_POST["RNameEn"], 'RTarifer' => $_POST["RTarifer"], 'RDescription' => $_POST["RDescription"], 'RDescriptionEn' => $_POST["RDescriptionEn"]);
                $this->room->edit($arrayUpdate, $this->parameters["id"]);
                $this->image->deleteMasive("IDRoom=" . $this->parameters["id"]);
                if ($_POST["images"]) {
                    $order = 0;
                    foreach ($_POST["images"] as $images) {
                        $order++;
                        $this->image->add(array('IName' => $images, 'IOrder' => $order, 'IDRoom' => $this->parameters["id"]));
                    }
                }
            } catch (Zend_Exception $exc) {
                //Redirecciono al index con error. Evita colgar la aplicación
                $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage()));
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
        $this->view->title = 'Editar Habitación';
        $this->view->description = 'Vea y modifique el formulario para editar una habitación';
        $this->view->result = $this->room->get($this->parameters["id"]);
        $this->view->images = $this->image->showAll("IDRoom=" . $this->parameters["id"]);

        //Envio mensajes nuevos a la vista
        $this->view->messages = $this->messages;
    }

    public function deleteAction() {
        $id = $this->_getParam('id', 0);
        if ($id > 0) {
            $this->view->result = $this->room->get($id);
        } else {
            $this->_redirect($this->parameters["module"] . '/' . $this->parameters["controller"]);
        }
        if ($this->getRequest()->isPost()) {
            if ($this->form->isValid($this->getRequest()->getPost())) {
                $delete_request = $this->getRequest()->getPost('delete_request');

                if ($delete_request == "Yes") {
                    try {
                        $this->view->result = $this->image->deleteMasive("IDRoom=" . $id);
                        $this->view->result = $this->room->delete_row($id);
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
        $this->view->title = 'Eliminar Habitación';
        $this->view->description = 'Seleccione una opción';
    }

    public function detailAction() {
        $this->view->result = $this->room->get($this->parameters["id"]);
        $this->view->images = $this->image->showAll("IDRoom=" . $this->parameters["id"]);
    }

    public function orderAction() {
        try {
            if ($this->getRequest()->isPost()) {
                if ($this->form->isValid($this->getRequest()->getPost())) {
                    krsort($_POST["IDRoom"]);
                    $SOrder = 0;
                    foreach ($_POST["IDRoom"] as $key => $id) {
                        $SOrder++;
                        $this->room->edit(array('ROrder' => $SOrder), $id);
                    }
                    $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => "El orden de las Imágenes se cambio correctamente"));
                    $this->_redirector->gotoSimple('index', null, null, array('status' => 'success'));
                }
            }
            $this->view->icon = 'sort-by-alphabet';
            $this->view->title = 'Cambiar el orden de las habitaciones';
            $this->view->description = 'Utilice las flechas para cambiar el orden';
            $results_all = $this->room->showAll();
            $this->view->results = $results_all;
        } catch (Zend_Exception $exc) {
            echo $exc->getMessage();
            exit();
        }
    }

}
