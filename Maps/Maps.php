<?php

namespace ManiaLivePlugins\eXpansion\Maps;

class Maps extends \ManiaLive\PluginHandler\Plugin {

    public function onInit() {
        $this->setVersion("0.1.0");
    }

    public function onReady() {
        if ($this->isPluginLoaded('Standard\Menubar'))
            $this->buildMenu();
        $this->enableDedicatedEvents();
        $this->showMapList('reaby');
                
    }
    public function onPlayerDisconnect($login) {
        Gui\Windows\Maplist::Erase($login);
    }
    public function buildMenu() {
        $this->callPublicMethod('Standard\Menubar', 'initMenu', \ManiaLib\Gui\Elements\Icons128x128_1::Challenge);
        $this->callPublicMethod('Standard\Menubar', 'addButton', 'Show Server Maps', array($this, 'showMapList'), false);
        $this->callPublicMethod('Standard\Menubar', 'addButton', 'Skip map', array($this, 'admSkip'), true);
        $this->callPublicMethod('Standard\Menubar', 'addButton', 'Replay map', array($this, 'admRestart'), true);
    }

    public function admRestart() {
        $this->connection->restartMap();
    }

    public function admSkip() {
        $this->connection->nextMap();
    }

    public function showMapList($login) {
        $window = Gui\Windows\Maplist::Create($login);
        $window->setTitle('Maps on server');
        $window->centerOnScreen();
        $window->setSize(160, 100);
        $window->show();
   }

}

?>
