<?php

namespace ManiaLivePlugins\eXpansion\Menu;

use \ManiaLivePlugins\eXpansion\Menu\Gui\Widgets\MenuPanel;
use \ManiaLivePlugins\eXpansion\Menu\Structures\Menuitem;

class Emotes extends \ManiaLive\PluginHandler\Plugin {

    private $menuItems;

    function onInit() {
        $this->setVersion("0.0.1");
    }

    function onReady() {
        $this->enableDedicatedEvents();
        $this->setPublicMethod("addItem");

        MenuPanel::$menuPlugin = $this;
        $this->reDraw();
    }

    function addItem(Menuitem $item, $pluginid = null) {
        $hash = spl_object_hash($item);
        $this->menuItems[$hash] = $item;                               
        $this->reDraw();
    }

    function reDraw() {
        foreach ($this->storage->players as $player)
            $this->onPlayerConnect($player->login, false);
        foreach ($this->storage->spectators as $player)
            $this->onPlayerConnect($player->login, true);
    }

    function onPlayerConnect($login, $isSpectator) {
        $info = MenuPanel::Create($login);
        $info->setSize(60, 20);
        $info->setPosition(160, 50);
        $info->setItems($this->menuItems);
        
        $info->show();
    }

    public function onPlayerDisconnect($login) {
        EmotePanel::Erase($login);
        if (isset($this->timeStamps[$login]))
            unset($this->timeStamps[$login]);
    }
    
}

?>