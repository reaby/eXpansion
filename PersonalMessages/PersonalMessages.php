<?php

namespace ManiaLivePlugins\eXpansion\PersonalMessages;

class PersonalMessages extends \ManiaLive\PluginHandler\Plugin {

    public function onInit() {
        $this->setVersion("0.1.0");
    }

    public function onReady() {
        $this->registerChatCommand("pmx", "sendPersonalMessage", -1, true);        
    }
    
    public function sendPersonalMessage($login, $message) {
       $window = \ManiaLivePlugins\eXpansion\PersonalMessages\Gui\Windows\PmWindow::Create($login);
        $window->setTitle('Select Player to send message');    
        $window->setMessage($message);
        $window->setSize(120, 100);    
        $window->centerOnScreen();        
        $window->show();
        
    }
    
}

?>
