<?php

require_once 'Contact.php';

class AjaxController extends Zend_Controller_Action {

    public function init() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->layout->disableLayout();
        }
    }

    //I N D E X
    public function indexAction() {
        if ($this->getRequest()->isPost()) {
            $this->contact = new Model_DBTable_Contact();
            $this->view->contact = $this->contact->get(1);
            //SEND EMAIL
            $para = $this->view->contact["CEmail"];
            $titulo = 'Consulta enviada desde la web';
            $mensaje = '
<html>
<body>
  
  <table>
    <tr>
      <th>Nombre</th><td>' . $_POST["name"] . '</td>
    </tr>
    <tr>
      <th>E-Mail</th><td>' . $_POST["email"] . '</td>
    </tr>
    <tr>
      <th>Asunto</th><td>' . $_POST["subject"] . '</td>
    </tr>
    <tr>
      <th>Mensaje</th><td><p>' . $_POST["message"] . '</p></td>
    </tr>
  </table>
</body>
</html>
';
            $cabeceras = 'MIME-Version: 1.0' . "\r\n";
            $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $cabeceras .= 'To: '.$this->view->contact["CEmail"]. "\r\n";
            $cabeceras .= 'From: '.$_POST["name"].' <'.$_POST["email"].'>' . "\r\n";
            mail($para, $titulo, $mensaje, $cabeceras);
            if($this->_getParam('lang'=='en')){
                echo "Your message was sent. Thanks";
            }else{
                echo "Su mensaje fue enviado. Gracias";
            }
            exit();
        }
    }

}
