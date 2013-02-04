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
        $this->registerChatCommand("save", "saveRecords", 0, true, \ManiaLive\Features\Admin\AdminGroup::get());
        $this->registerChatCommand("load", "loadRecords", 0, true, \ManiaLive\Features\Admin\AdminGroup::get());

        if (!$this->db->tableExists("exp_players")) {
            $this->db->execute('CREATE TABLE IF NOT EXISTS `exp_players` (  
  `login` varchar(255) NOT NULL,
  `nickname` text NOT NULL,
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
        }

        if (!$this->db->tableExists("exp_records")) {
            $this->db->execute('CREATE TABLE IF NOT EXISTS `exp_records` (
  `uid` varchar(50) NOT NULL,
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
        $this->loadRecords($this->storage->currentMap->uId);
        $this->reArrage();

        // $this->readRecords($this->storage->currentMap->uId);
    }

    public function saveRecords() {
        $uid = $this->db->quote($this->storage->currentMap->uId);
        $mapname = $this->db->quote($this->storage->currentMap->name);
        $author = $this->db->quote($this->storage->currentMap->author);
        $json = $this->db->quote(json_encode($this->records));
        $query = "INSERT INTO exp_records (`uid`, `mapname`, `mapauthor`, `records` ) VALUES (" . $uid . "," . $mapname . "," . $author . "," . $json . ") ON DUPLICATE KEY UPDATE `records`=" . $json . ";";
        $this->db->execute($query);
    }

    public function loadRecords($uid) {
        $json = $this->db->query("SELECT `records` from exp_records where `uid`=" . $this->db->quote($uid) . ";")->fetchArray();
        $records = json_decode($json['records']);
        $outRecords = array();
        if (count($records) == 0) {
            $this->records = array();
            return;
        }
        foreach ($records as $login => $record)
            $outRecords[$login] = new Structures\Record($login, $record->time, $record->place);

        $this->records = $outRecords;
    }

    function reArrage($save = false) {
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

        if ($save)
            $this->saveRecords();

        LRPanel::$records = $this->records;
        LRPanel::EraseAll();


        $info = LRPanel::Create();
        $info->setSize(50, 20);
        $info->setPosition(-160, -0);
        $info->show();
    }

    function onBeginMap($map, $warmUp, $matchContinuation) {
        $this->loadRecords($this->storage->currentMap->uId);
        $this->reArrage();
    }

    function onEndMap($rankings, $map, $wasWarmUp, $matchContinuesOnNextMap, $restartMap) {
        $this->saveRecords();
    }

    function onPlayerFinish($playerUid, $login, $time) {
        if ($time == 0)
            return;


        $x = 0;  
        
        if (count($this->records) == 0) {
            $this->records[$login] = new Structures\Record($login, $time);
            $this->reArrage(true);
            //   $this->connection->chatSendServerMessage($login . " took " . $this->records[$login]->place . " place with time:" . \ManiaLive\Utilities\Time::fromTM($time));
            return;
        }

        if ($this->lastRecord->time > $time || count($this->records) < 20) {
            $this->records[$login] = new Structures\Record($login, $time);
            $this->reArrage(true);
            //  $this->connection->chatSendServerMessage($login . " gained " . $this->records[$login]->place . " with time:" . \ManiaLive\Utilities\Time::fromTM($time));
            return;
        }

        if ($this->records[$login]->time > $time) {
            $oldRecord = $this->records[$login];
            $this->records[$login] = new Structures\Record($login, $time);
            $this->reArrage(true);
            //  $this->connection->chatSendServerMessage($login . " took " . $this->records[$login]->place . " place with time:" . \ManiaLive\Utilities\Time::fromTM($time));
            return;
        }
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