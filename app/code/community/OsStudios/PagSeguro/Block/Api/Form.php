<?php
class OsStudios_PagSeguro_Block_Api_Form extends OsStudios_PagSeguro_Block_Form
{
    
    public function _construct() {
        parent::_construct();
        $this->setTemplate('osstudios/pagseguro/api/form.phtml');        
    }
    
}