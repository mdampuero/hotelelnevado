<?php
//require_once '../application/modules/visor/models/DbTable/Alert.php';

class Zend_Controller_Action_Helper_Token extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * @var Zend_Loader_PluginLoader
     */
    public $pluginLoader;
    
 
    /**
     * Constructor: initialize plugin loader * 
     * @return void
     */
    public function __construct()
    {
        $this->pluginLoader = new Zend_Loader_PluginLoader();
    }
    
    public function getToken(){
        $exp_reg = "[^A-Z0-9]";
        $token=substr(eregi_replace($exp_reg, "", md5(rand())) .eregi_replace($exp_reg, "", md5(rand())) .eregi_replace($exp_reg, "", md5(rand())), 0, 50);
        return $token;  
    }
    
    public function direct($mensaje)
    {
        return $this->verificar($mensaje);
    }
}
?>
