<?php
//require_once '../application/modules/visor/models/DbTable/Alert.php';

class Zend_Controller_Action_Helper_Imagen extends Zend_Controller_Action_Helper_Abstract
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
    
    public function processImage($imageFile){    
    }
    
    /**
     * Strategy pattern: call helper as broker method
     * 
     * @param  int $month 
     * @param  int $year 
     * @return int
     */
    public function direct($mensaje)
    {
        return $this->verificar($mensaje);
    }
}
?>
