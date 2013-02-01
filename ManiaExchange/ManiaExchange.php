<?php

namespace ManiaLivePlugins\eXpansion\ManiaExchange;

class ManiaExchange extends \ManiaLive\PluginHandler\Plugin {

    public function onInit() {
        $this->setVersion("0.1.0");
    }

    public function onReady() {
        $this->registerChatCommand("mx", "chatMX", 2, true);
        $this->registerChatCommand("mx", "chatMX", 1, true);

        if ($this->isPluginLoaded('Standard\Menubar'))
            $this->buildMenu();
        $this->enableDedicatedEvents();
    }

    public function onPlayerDisconnect($login) {
        Gui\Windows\MxSearch::Erase($login);
    }

    public function buildMenu() {
        $this->callPublicMethod('Standard\Menubar', 'initMenu', \ManiaLib\Gui\Elements\Icons128x128_1::Download);
        $this->callPublicMethod('Standard\Menubar', 'addButton', 'Search', array($this, 'mxSearch'), true);
    }

    public function chatMX($login, $arg, $param = null) {
        switch ($arg) {
            case "add":
                $this->addTrack($login, $param);
                break;
            case "search":
                $this->mxSearch($login, $param);
                break;
            case "help":
            default:
                $this->connection->chatSendServerMessage('Usage /mx add [id] or /mx search "your search terms here"', $login);
                break;
        }
    }

    public function mxSearch($login, $search = "test") {
        $window = Gui\Windows\MxSearch::Create($login);
        $window->setTitle('ManiaExchange');
        $window->search($search);
        $window->centerOnScreen();
        $window->setSize(120, 100);
        $window->show();
    }

    public function addTrack($login, $mxId) {
        if (!is_numeric($mxId)) {
            $this->connection->chatSendServerMessage('"' . $mxId . '" is not a numeric value.', $login);
            return;
        }
        try {
            $query = 'http://tm.mania-exchange.com/tracks/download/'.$mxId;
            $ch = curl_init($query);
            curl_setopt($ch, CURLOPT_USERAGENT, "Manialive/eXpansion MXapi [getter] ver 0.1");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            if ($data === false) {
                $this->connection->chatSendServerMessage("No track found or error while getting data from MX", $login);
                return;
            }
            curl_close($ch);
            $file = $this->connection->getMapsDirectory() . "/Downloaded/" . $mxId . ".Map.Gbx";

            if (!touch($file)) {
                $this->connection->chatSendServerMessage("Couldn't create mapfile in maps folder, check folder permissions!", $login);
            }
            file_put_contents($file, $data);
            $this->connection->addMap($file);            
            
            $map = $this->connection->getMapInfo($file);            
            $this->connection->chatSendServerMessage("Map " . $map->name . '$z$s$fff added from MX Succesfully.', $login);
        } catch (\Exception $e) {
            $this->connection->chatSendServerMessage('$f00$oError! $z$s$fff' . $e->getMessage(), $login);
        }
    }

}
?>
