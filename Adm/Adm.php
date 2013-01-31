<?php

namespace ManiaLivePlugins\eXpansion\Adm;

use \ManiaLivePlugins\eXpansion\Adm\Gui\Windows\ServerOptions;
use ManiaLive\Gui\ActionHandler;

class Adm extends \ManiaLive\PluginHandler\Plugin {

    function onInit() {
        $this->setVersion("0.0.1");
    }

    function onReady() {
        //    $methods = get_class_methods($this->connection);
        if ($this->isPluginLoaded('Standard\Menubar'))
            $this->buildMenu();
      
    }

    public function buildMenu() {
        $this->callPublicMethod('Standard\Menubar', 'initMenu', \ManiaLib\Gui\Elements\Icons128x128_1::Options);
        $this->callPublicMethod('Standard\Menubar', 'addButton', 'Options', array($this, 'serverOptions'), true);        
    }
    
    public function serverOptions($login) {
          $info = ServerOptions::Create($login);
          $info->setTitle('Server Options');
          $info->centerOnScreen();
          $info->setSize(160,100);
          $info->show();            
    }

}

?>