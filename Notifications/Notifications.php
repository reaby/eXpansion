<?php

namespace ManiaLivePlugins\eXpansion\Notifications;

use \ManiaLivePlugins\eXpansion\Notifications\Gui\Widgets\Panel as NotificationPanel;
use \ManiaLivePlugins\eXpansion\Notifications\Structures\Item;

class Notifications extends \ManiaLive\PluginHandler\Plugin {

    private $messages = array();

    function onInit() {
        $this->setVersion("0.0.1");
        $this->setPublicMethod("send");    
    }

    function onReady() {
        $this->enableDedicatedEvents();
        $this->send("test1");
        $this->send("test2");
        $this->send("test3");
        $this->send("test4");
        $this->reDraw();
    }

    function send($message, $icon = null, $callback = null, $pluginid = null) {
        if (is_callable($callback) || $callback === null) {
            $item = new Item($icon, $message, $callback);
            $hash = spl_object_hash($item);
            $this->messages[$hash] = $item;
            $array = array_reverse($this->messages, true);
            $array = array_slice($array, 0, 7, true);
            $this->messages = array_reverse($array, true);
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
        NotificationPanel::Erase($login);
        $info = NotificationPanel::Create($login);
        $info->setSize(100, 40);
        $info->setItems($this->messages);
        $info->setPosition(0, 38);
        
        $info->show();
    }

    public function onPlayerDisconnect($login) {
        NotificationPanel::Erase($login);       
    }

}

?>