<?php

namespace ManiaLivePlugins\eXpansion\Adm;

use \ManiaLivePlugins\eXpansion\Adm\Gui\Windows\ServerOptions;
use ManiaLive\Gui\ActionHandler;
use ManiaLivePlugins\eXpansion\Adm\Gui\Windows\AdminPanel;

class Adm extends \ManiaLive\PluginHandler\Plugin {

    function onInit() {
        $this->setVersion("0.0.1");
    }

    function onReady() {
        //    $methods = get_class_methods($this->connection);
        if ($this->isPluginLoaded('Standard\Menubar'))
            $this->buildStdMenu();

        if ($this->isPluginLoaded('eXpansion\Menu')) {
            $this->callPublicMethod('eXpansion\Menu', 'addSeparator', 'Server Management', true);
            $this->callPublicMethod('eXpansion\Menu', 'addItem', 'Server Options', null, array($this, 'serverOptions'), true);
            $this->callPublicMethod('eXpansion\Menu', 'addItem', 'Match Settings', null, array($this, 'matchSettings'), true);
        }


        $this->enableDedicatedEvents();

        foreach ($this->storage->players as $player)
            $this->onPlayerConnect($player->login, false);
        foreach ($this->storage->spectators as $player)
            $this->onPlayerConnect($player->login, true);
    }

    function onPlayerConnect($login, $isSpectator) {
        if (\ManiaLive\Features\Admin\AdminGroup::contains($login)) {
            $info = AdminPanel::Create($login);
            $info->setSize(50, 20);
            $info->setPosition(-160, -46);
            $info->show();
        }
    }

    public function onPlayerDisconnect($login) {
        AdminPanel::Erase($login);
    }

    public function buildStdMenu() {
        $this->callPublicMethod('Standard\Menubar', 'initMenu', \ManiaLib\Gui\Elements\Icons128x128_1::Options);
        $this->callPublicMethod('Standard\Menubar', 'addButton', 'Server Options', array($this, 'serverOptions'), true);
        $this->callPublicMethod('Standard\Menubar', 'addButton', 'Match Settings', array($this, 'matchSettings'), true);
    }

    public function serverOptions($login) {
        $window = ServerOptions::Create($login);
        $window->setTitle('Server Options');
        $window->centerOnScreen();
        $window->setSize(160, 100);
        $window->show();
    }

    public function matchSettings($login) {
        $window = Gui\Windows\MatchSettings::Create($login);
        $window->setTitle('Match Settings');
        $window->centerOnScreen();
        $window->setSize(120, 100);
        $window->show();
    }

}

?>