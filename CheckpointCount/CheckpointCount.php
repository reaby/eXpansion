<?php

namespace ManiaLivePlugins\eXpansion\CheckpointCount;

use \ManiaLivePlugins\eXpansion\CheckpointCount\Gui\Widgets\CPPanel;

class CheckpointCount extends \ManiaLive\PluginHandler\Plugin {

    function onInit() {
        $this->setVersion("0.0.1");
    }

    function onLoad() {
        $this->enableDedicatedEvents();
    }

    public function onReady() {

        foreach ($this->storage->players as $player)
            $this->onPlayerConnect($player->login, false);
        foreach ($this->storage->spectators as $player)
            $this->onPlayerConnect($player->login, true);
    }

    function displayWidget($login = null) {
        if ($login == null)
            CPPanel::EraseAll();
        else
            CPPanel::Erase($login);

        $info = CPPanel::Create($login);
        $info->setSize(30, 6);
        $text = "-  / " . $this->storage->currentMap->nbCheckpoints;
        $info->setText('$fff' . $text);
        $info->setPosition(0, -68.5);
        $info->show();
    }

    public function onPlayerCheckpoint($playerUid, $login, $timeOrScore, $curLap, $checkpointIndex) {
        CPPanel::Erase($login);
        
        $info = CPPanel::Create($login);
        $info->setSize(30, 6);
        $text = ($checkpointIndex+1) . " / " . $this->storage->currentMap->nbCheckpoints;
        $info->setText('$fff'. $text);
        $info->setPosition(0, -68.5);
        $info->show();
    }
    
    public function onPlayerFinish($playerUid, $login, $timeOrScore) {
        $this->displayWidget($login);
    }
    
    public function onBeginMap($map, $warmUp, $matchContinuation) {
        $this->displayWidget();
    }
    public function onEndMap($rankings, $map, $wasWarmUp, $matchContinuesOnNextMap, $restartMap) {
        CPPanel::EraseAll();
    }
    function onPlayerConnect($login, $isSpectator) {
        $this->displayWidget($login);
    }

    function onPlayerDisconnect($login) {
        CPPanel::Erase($login);
    }

}
?>

