<?php

namespace ManiaLivePlugins\eXpansion\Emotes;

use \ManiaLivePlugins\eXpansion\Emotes\Gui\Windows\EmotePanel;

class Emotes extends \ManiaLive\PluginHandler\Plugin {

    function onInit() {
        $this->setVersion("0.0.1");
    }

    function onReady() {
        $this->enableDedicatedEvents();

        foreach ($this->storage->players as $player)
            $this->onPlayerConnect($player->login, false);
        foreach ($this->storage->spectators as $player)
            $this->onPlayerConnect($player->login, true);
    }

    function onPlayerConnect($login, $isSpectator) {
        $info = EmotePanel::Create($login);
        $info->setSize(60, 20);
        $info->setPosition(-160, -52);
        $info->show();
    }

    public function onPlayerDisconnect($login) {
        EmotePanel::Erase($login);
    }

}

?>