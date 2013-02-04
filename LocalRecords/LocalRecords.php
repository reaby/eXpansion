<?php

namespace ManiaLivePlugins\eXpansion\LocalRecords;

use ManiaLivePlugins\eXpansion\LocalRecords\Gui\Widgets\LRPanel;

class LocalRecords extends \ManiaLive\PluginHandler\Plugin {

    public static $players;
    private $records = array();
    private $lastRecord = null;

    function onInit() {
        $this->setVersion("0.0.1");
    }

    function onLoad() {
        $this->enableDatabase();
        $this->enableDedicatedEvents();
        $this->enablePluginEvents();
        $this->registerChatCommand("test", "saveRecords", 0, true, \ManiaLive\Features\Admin\AdminGroup::get());

        if (!$this->db->tableExists("exp_players")) {
            $this->db->execute('CREATE TABLE IF NOT EXISTS `exp_players` (  
  `login` varchar(255) NOT NULL,
  `nickname` text NOT NULL,
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
        }

        if (!$this->db->tableExists("exp_records")) {
            $this->db->execute('CREATE TABLE IF NOT EXISTS `exp_records` (
  `uid` int(11) NOT NULL,
  `mapname` text NOT NULL,
  `mapauthor` text NOT NULL,
  `records` text NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
        }

        $player = new Structures\DbPLayer();
        $player->fromPlayerObj($this->storage->getPlayerObject('reaby'));
        $this->db->execute($player->exportToDb());
        // echo $this->db->affectedRows();     
    }

    public function onReady() {
        foreach ($this->storage->players as $player)
            $this->onPlayerConnect($player->login, false);
        foreach ($this->storage->spectators as $player)
            $this->onPlayerConnect($player->login, true);

        $this->syncPlayers();
        // $this->readRecords($this->storage->currentMap->uId);
    }

    public function saveRecords($login) {
        $data = Array();
        $data['uid'] = $this->storage->currentMap->uId;
        $data['mapname'] = $this->storage->currentMap->name;
        $data['mapauthor'] = $this->storage->currentMap->author;
        $data['records'] = json_encode($this->records);

        print_r($data);
    }

    function reArrage() {
        \ManiaLivePlugins\eXpansion\Helpers\ArrayOfObj::sortAsc($this->records, "time");
        $i = 0;
        $newrecords = array();
        foreach ($this->records as $record) {
            if (array_key_exists($record->login, $newrecords))
                continue;
            $record->place = ++$i;
            $newrecords[$record->login] = $record;
        }
        $this->records = array_slice($newrecords, 0, 20);
        $this->lastRecord = end($this->records);
        
        LRPanel::$records = $this->records;
        LRPanel::EraseAll();
        
        
        $info = LRPanel::Create();
        
        $info->setSize(50, 20);
        $info->setPosition(-160, -0);
        $info->show();

        print_r($this->records);
    }

    function onPlayerCheckpoint($playerUid, $login, $time, $curLap, $checkpointIndex) {
        $x = 0;
        if ($checkpointIndex != 0)
            return;

        if (!array_key_exists($login, $this->records)) {
            
        }

        if (count($this->records) == 0) {
            $this->records[$login] = new Structures\Record($login, $time);
            $this->reArrage();
         //   $this->connection->chatSendServerMessage($login . " took " . $this->records[$login]->place . " place with time:" . \ManiaLive\Utilities\Time::fromTM($time));
            return;
        }

        if ($this->lastRecord->time > $time || count($this->records) < 20) {
            $this->records[$login] = new Structures\Record($login, $time);
            $this->reArrage();
          //  $this->connection->chatSendServerMessage($login . " gained " . $this->records[$login]->place . " with time:" . \ManiaLive\Utilities\Time::fromTM($time));
            return;
        }

        if ($this->records[$login]->time < $time) {
            print "$login had bad time\n";
            return;
        }

        if ($this->records[$login]->time > $time) {
            $oldRecord = $this->records[$login];
            $this->records[$login] = new Structures\Record($login, $time);
            $this->reArrage();
          //  $this->connection->chatSendServerMessage($login . " took " . $this->records[$login]->place . " place with time:" . \ManiaLive\Utilities\Time::fromTM($time));
            return;
        }
    }

    function onPlayerFinish($playerUid, $login, $timeOrScore) {
        
    }

    function syncPlayers() {
        $db = $this->db->query("Select * FROM exp_players")->fetchArrayOfAssoc();
        foreach ($db as $array)
            self::$players[$array['login']] = Structures\DbPLayer::fromArray($array);
    }

    function onPlayerConnect($login, $isSpectator) {
        $player = new Structures\DbPLayer();
        $player->fromPlayerObj($this->storage->getPlayerObject($login));
        $this->db->execute($player->exportToDb());
        self::$players[$login] = $player;
    }

    public function onPlayerDisconnect($login) {
        
    }

}

?>