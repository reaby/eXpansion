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
          $window = ServerOptions::Create($login);
          $window->setTitle('Server Options');
          $window->centerOnScreen();
          $window->setSize(160,100);
          $window->show();            
    }

}

?>