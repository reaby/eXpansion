<?php
namespace ManiaLivePlugins\eXpansion\ManiaExchange;

class ManiaExchange extends \ManiaLive\PluginHandler\Plugin {

    public function onInit() {
        $this->setVersion("0.1.0");
    }

    public function onReady() {
        if ($this->isPluginLoaded('Standard\Menubar'))
            $this->buildMenu();
        $this->enableDedicatedEvents();
    }

    public function onPlayerDisconnect($login) {
        Gui\Windows\Maplist::Erase($login);
    }

    public function buildMenu() {
        $this->callPublicMethod('Standard\Menubar', 'initMenu', \ManiaLib\Gui\Elements\Icons128x128_1::Download);        
        $this->callPublicMethod('Standard\Menubar', 'addButton', 'Search', array($this, 'mxSearch'), true);
    }

    public function mxSearch($login) {
        $window = Gui\Windows\Maplist::Create($login);
        $window->setTitle('ManiaExchange');
        $window->centerOnScreen();
        $window->setSize(120, 100);
        $window->show();
    }

}

?>
