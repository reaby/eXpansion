<?php

namespace ManiaLivePlugins\eXpansion\Players;

class Players extends \ManiaLive\PluginHandler\Plugin {

    public function onInit() {
        $this->setVersion("0.1.0");
    }

    public function onReady() {
        if ($this->isPluginLoaded('Standard\Menubar'))
            $this->buildMenu();    
    }
    
    public function buildMenu() {
        $this->callPublicMethod('Standard\Menubar', 'initMenu', \ManiaLib\Gui\Elements\Icons64x64_1::Buddy);
        $this->callPublicMethod('Standard\Menubar', 'addButton', 'Players', array($this, 'showPlayerList'), false);        
    }

    public function showPlayerList($login) {
        $window = Gui\Windows\Playerlist::Create($login);
        $window->setTitle('Players');    
        $window->setSize(120, 100);    
        $window->centerOnScreen();        
        $window->show();
   }

}

?>
