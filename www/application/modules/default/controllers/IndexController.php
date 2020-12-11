<?php

require_once 'Sliders.php';
require_once 'Aboutus.php';
require_once 'Contact.php';
require_once 'Rooms.php';
require_once 'Common.php';
require_once 'Images.php';

class IndexController extends Zend_Controller_Action {

    var $helper;
    var $helperMail;

    public function init() {
        $this->contact = new Model_DBTable_Contact();
         $this->view->contact  = $this->contact->get(1);
        //CONTACT
        $date=(Int) date("md");
        if($date>='321' && $date<'621'){
            $this->view->bg="mendoza_otonio.jpg";
        }elseif($date>='621' && $date<'921'){
            $this->view->bg="mendoza_invierno.jpg";
        }elseif($date>='921' && $date<'1221'){
            $this->view->bg="mendoza_primavera.jpg";
        }elseif($date>='1221' || $date<'321'){
            $this->view->bg="mendoza_verano.jpg";
        }
        $options = array(
            'layout' => 'default',
            'layoutPath' => '../application/layouts/scripts/'
        );
        $this->response = $this->getResponse();
        $this->view->parameters = $this->_request->getParams();
        $this->slider = new Model_DBTable_Sliders();
        $this->room = new Model_DBTable_Rooms();
        $this->aboutus = new Model_DBTable_Aboutus();
        $this->image = new Model_DBTable_Images();
        
    }

    public function indexAction() {
        
        if($this->view->parameters["lang"]=="en"){
            $this->view->sufix="En";
        }
        //SLIDER
        $this->view->results  = $this->slider->showAll();
        
        //ABOUTUS
        $this->view->aboutus  = $this->aboutus->get(1);
        
        //ROOMS
        $this->view->rooms  = $this->room->showAll();
        foreach($this->view->rooms as $key => $room){
            $images=$this->image->showAll("IDRoom=" . $room["IDRoom"]);
            if(count($images)) $this->view->rooms[$key]["IName"]=$images[0]["IName"];
            else $this->view->rooms[$key]["IName"]=null;
        }
        
    }

}
