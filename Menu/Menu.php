<?php

namespace ManiaLivePlugins\eXpansion\Menu;

use \ManiaLivePlugins\eXpansion\Menu\Gui\Widgets\MenuPanel;
use \ManiaLivePlugins\eXpansion\Menu\Structures\Menuitem;

class Menu extends \ManiaLive\PluginHandler\Plugin {

    private $menuItems = array();

    function onInit() {
        $this->setVersion("0.0.1");
        $this->setPublicMethod("addItem");
        $this->setPublicMethod("addSeparator");
    }

    function onReady() {
        $this->enableDedicatedEvents();
    }

    function addSeparator($title, $isAdmin, $pluginId = null) {
        $item = new Structures\Menuitem($title, null, null, $isAdmin, true);
        $hash = spl_object_hash($item);
        $this->menuItems[$hash] = $item;
    }

    function addItem($title, $icon, array $callback, $isAdmin, $pluginid = null) {
        if (is_callable($callback)) {
            $item = new Structures\Menuitem($title, $icon, $callback, $isAdmin);
            $hash = spl_object_hash($item);
            $this->menuItems[$hash] = $item;
            $this->reDraw();
        } else {
            \ManiaLive\Utilities\Console::println("Adding a button failed from plugin:" . $pluginid . " button callback is not valid.");
        }
    }

    function reDraw() {
        foreach ($this->storage->players as $player)
            $this->onPlayerConnect($player->login, false);
        foreach ($this->storage->spectators as $player)
            $this->onPlayerConnect($player->login, true);
    }

    function onPlayerConnect($login, $isSpectator) {
        MenuPanel::Erase($login);
        $info = MenuPanel::Create($login);
        $info->setSize(60, 20);
        $info->setPosition(150, 50);
        $info->setItems($this->menuItems);
        $info->show();
    }

    public function onPlayerDisconnect($login) {
        MenuPanel::Erase($login);       
    }

}

?>