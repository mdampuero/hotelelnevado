<?php
require_once 'Common.php';
class Admin_UploadController extends Zend_Controller_Action {

    public function init() {
        $this->form = new Zend_Form;
        $this->parameters = $this->_request->getParams();
        $this->view->parameters = $this->parameters;
        $options = array('layout' => 'admin_popup', 'layoutPath' => '../application/layouts/scripts/');
        Zend_Layout::startMvc($options);
    }

    public function imageAction() {
        $this->view->statusBar = 'info';
        $directory = PUBLIC_PATH . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "room" . DIRECTORY_SEPARATOR;
        $directory_url = "images/room/";
        if ($this->getRequest()->isPost()) {
            if ($this->form->isValid($this->getRequest()->getPost())) {
                try {
                    $this->errorMessage = "Formato de imagen no soportado";
                    if ($extension = is_valid_image($_FILES["image"]["name"])) {
                        $name = uniqid() . "." . $extension;
                        move_uploaded_file($_FILES["image"]["tmp_name"], $directory . $name);
                        smart_resize_image($directory . $name, 600, 0, true, $directory. "b_" . $name, false, false);
                        smart_resize_image($directory . $name, 450, 0, true, $directory . "m_" . $name, false, false);
                        smart_resize_image($directory . $name, 150, 0, true, $directory . "s_" . $name, false, false);
                        unlink($directory . $name);
                        $this->view->imageUrl = $directory_url."b_".$name;
                        $this->view->image=$name;
                    }
                } catch (Zend_Exception $exc) {
                    //Redirecciono al index con error. Evita colgar la aplicación
                    $this->view->messages = array(array('type' => 'danger', 'message' => $this->errorMessage));
                }
            }
        }
    }

    public function imgprofileAction() {
        if ($this->sesion->Data["user_type_id"] == 1) {
            $this->view->title = "Foto de Perfil";
            $this->view->label = "Subí una foto desde tu PC:";
        } else {
            $this->view->title = "Logo o Marca de tu negocio";
            $this->view->label = "Subí una imagen desde tu PC:";
        }
        if ($this->parameters["user_id"]) {
            $directory = PUBLIC_PATH . DIRECTORY_SEPARATOR . "user" . DIRECTORY_SEPARATOR . $this->parameters["user_id"] . DIRECTORY_SEPARATOR;
            if ($this->parameters["cancel"] == 1) {
                unlink($directory . "b_" . $this->parameters["image"]);
                unlink($directory . "m_" . $this->parameters["image"]);
                unlink($directory . "s_" . $this->parameters["image"]);
            }
            //exit();
            if ($this->getRequest()->isPost()) {
                if ($this->form->isValid($this->getRequest()->getPost())) {
                    if ($_POST["cut"] == 1) {
                        $newImage = cutImage($directory . "b_" . $_POST["image"], null, $_POST['x1'], $_POST['y1'], $_POST['x2'], $_POST['y2'], $_POST['w'], $_POST['h']);
                        smart_resize_image($newImage, 120, 0, true, $directory . "b_" . $_POST["image"], false, false);
                        smart_resize_image($newImage, 60, 0, true, $directory . "m_" . $_POST["image"], false, false);
                        smart_resize_image($newImage, 32, 0, true, $directory . "s_" . $_POST["image"], false, false);
                        $this->view->image = $_POST["image"];
                        $this->view->acept = true;
                    }
                    if ($_FILES["image"]["name"]) {
                        if (is_valid_image(strtolower(end(explode(".", $_FILES['image']["name"]))))) {
                            if (!is_dir($directory)) {
                                mkdir($directory);
                            }
                            $name = uniqid() . '.' . strtolower(end(explode(".", $_FILES['image']["name"])));
                            move_uploaded_file($_FILES['image']["tmp_name"], $directory . $name);
                            smart_resize_image($directory . $name, 500, 0, true, $directory . "b_" . $name, false, false);
                            unlink($directory . $name);
                            $this->view->image = $name;
                        } else {
                            $this->view->error = "Formato de imagen no soportado. Solo se admiten archivos: JPG,GIF o PNG";
                        }
                    }
                }
            }
        } else {
            $this->view->close = true;
        }
    }

    public function imgcommerceAction() {
        $this->view->title = "Imagen de encabezado";
        if ($this->parameters["user_id"]) {
            $directory = PUBLIC_PATH . DIRECTORY_SEPARATOR . "user" . DIRECTORY_SEPARATOR . $this->parameters["user_id"] . DIRECTORY_SEPARATOR;
            if ($this->parameters["cancel"] == 1) {
                unlink($directory . "b_" . $this->parameters["image"]);
            }
            if ($this->getRequest()->isPost()) {
                if ($this->form->isValid($this->getRequest()->getPost())) {
                    if ($_POST["cut"] == 1) {
                        $newImage = cutImage($directory . "b_" . $_POST["image"], null, $_POST['x1'], $_POST['y1'], $_POST['x2'], $_POST['y2'], $_POST['w'], $_POST['h']);
                        smart_resize_image($newImage, 700, 150, true, $directory . "b_" . $_POST["image"], false, false);
                        smart_resize_image($newImage, 300, 0, true, $directory . "m_" . $_POST["image"], false, false);
                        $this->view->image = $_POST["image"];
                        $this->view->acept = true;
                    }
                    if ($_FILES["image"]["name"]) {
                        if (is_valid_image(strtolower(end(explode(".", $_FILES['image']["name"]))))) {
                            if (!is_dir($directory)) {
                                mkdir($directory);
                            }
                            $name = uniqid() . '.' . strtolower(end(explode(".", $_FILES['image']["name"])));
                            move_uploaded_file($_FILES['image']["tmp_name"], $directory . $name);
                            smart_resize_image($directory . $name, 1000, 0, true, $directory . "b_" . $name, false, false);
                            unlink($directory . $name);
                            $this->view->image = $name;
                        } else {
                            $this->view->error = "Formato de imagen no soportado. Solo se admiten archivos: JPG,GIF o PNG";
                        }
                    }
                }
            }
        } else {
            $this->view->close = true;
        }
    }

}
