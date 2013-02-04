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
        
    }

    public function onPlayerDisconnect($login) {
        Gui\Windows\Maplist::Erase($login);
        Gui\Windows\AddMaps::Erase($login);
    }

    public function buildMenu() {
        $this->callPublicMethod('Standard\Menubar', 'initMenu', \ManiaLib\Gui\Elements\Icons128x128_1::Challenge);
        $this->callPublicMethod('Standard\Menubar', 'addButton', 'List all maps on server', array($this, 'showMapList'), false); 
        $this->callPublicMethod('Standard\Menubar', 'addButton', 'Add local map on server', array($this, 'addMaps'), true);
        
        // user call votes disabled since dedicated doesn't support them atm.
        //  $this->callPublicMethod('Standard\Menubar', 'addButton', 'Vote for skip map', array($this, 'voteSkip'), false);
        //  $this->callPublicMethod('Standard\Menubar', 'addButton', 'Vote for replay map', array($this, 'voteRestart'), false);
    }

    public function voteRestart($login) {
        $vote = new \DedicatedApi\Structures\Vote();
        $vote->callerLogin = $login;
        $vote->cmdName = "Cmd name";
        $vote->cmdParam = array("param");
        $this->connection->callVote($vote, 0.5, 0, 0);
        $this->connection->chatSendServerMessage($login." custom vote restart");
    }

    
    public function onVoteUpdated($stateName, $login, $cmdName, $cmdParam) {
         $message = $stateName . " -> ". $login . " -> ".$cmdName . " -> ".  $cmdParam . "\n";
         $this->connection->chatSendServerMessage($message);        
    }
    
    
    public function voteSkip($login) {
        $this->connection->callVoteNextMap();
        $this->connection->chatSendServerMessage($login." vote skip");
    }

    public function showMapList($login) {
        $window = Gui\Windows\Maplist::Create($login);
        $window->setTitle('Maps on server');
        $window->centerOnScreen();
        $window->setSize(120, 100);
        $window->show();
    }
    
    public function addMaps($login) {
        $window = Gui\Windows\addMaps::Create($login);
        $window->setTitle('Add Maps on server');
        $window->centerOnScreen();
        $window->setSize(120, 100);
        $window->show();
    }
}

?>

