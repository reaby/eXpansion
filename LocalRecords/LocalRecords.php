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
        $this->registerChatCommand("reset", "resetRecords", 0, true, \ManiaLive\Features\Admin\AdminGroup::get());

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
    public function resetRecords() {
        $this->records = array();
        $this->reArrage();                        
        
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
        $info->setSize(50, 60);
        $info->setPosition(-160, 30);
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

        // if no records, make entry
        if (count($this->records) == 0) {
            $this->records[$login] = new Structures\Record($login, $time);
            $this->reArrage(true);
            $this->announce($login);
        }

        // so if the time is better than the last entry or the count of records is less than 20...
        if ($this->lastRecord->time > $time || count($this->records) < 20) {
            // if player exists on the list... see if he got better time
            if (array_key_exists($login, $this->records)) {
                if ($this->records[$login]->time > $time) {
                    $oldRecord = $this->records[$login];
                    $this->records[$login] = new Structures\Record($login, $time);
                    $this->reArrage(true);
                    $this->announce($login, $oldRecord);
                    return;
                }
                // if not then just do a update for the time
            } else {
                $this->records[$login] = new Structures\Record($login, $time);
                $this->reArrage(true);
                $this->announce($login);
                return;
            }
        }
    }

    function announce($login, $oldRecord = null) {
        try {
            $player = $this->storage->getPlayerObject($login);
            $color = '$fff';
            $actionColor = '$6ad';

            if ($this->records[$login]->place == 1)
                $actionColor = '$39f';
            
            // todo: possible add different message if player enhances own record... 
            if ($oldRecord !== null) {
                $this->connection->chatSendServerMessage($color . 'a new local record ' . $actionColor . \ManiaLib\Utils\Formatting::stripCodes($player->nickName, "wos") . '$z$s' . $color . " took " . $actionColor . '$o' . $this->records[$login]->place . $color . '$o place with time $o' . $actionColor . \ManiaLive\Utilities\Time::fromTM($this->records[$login]->time));
                return;
            }

            $this->connection->chatSendServerMessage($color . 'a new local record ' . $actionColor . \ManiaLib\Utils\Formatting::stripCodes($player->nickName, "wos") . '$z$s' . $color . " took " . $actionColor . '$o' . $this->records[$login]->place . $color . '$o place with time $o' . $actionColor . \ManiaLive\Utilities\Time::fromTM($this->records[$login]->time));
        } catch (\Exception $e) {
            \ManiaLive\Utilities\Console::println("Error: couldn't show localrecords message" . $e->getMessage());
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

    public
            function onPlayerDisconnect($login) {
        
    }

}
?>