<?php

namespace ManiaLivePlugins\eXpansion\Maps\Gui\Windows;

use \ManiaLivePlugins\eXpansion\Gui\Elements\Button as OkButton;
use \ManiaLivePlugins\eXpansion\Gui\Elements\Inputbox;
use \ManiaLivePlugins\eXpansion\Gui\Elements\Checkbox;
use \ManiaLivePlugins\eXpansion\Gui\Elements\Ratiobutton;
use \ManiaLivePlugins\eXpansion\Maps\Gui\Controls\Mapitem;
use ManiaLive\Gui\ActionHandler;

class Maplist extends \ManiaLivePlugins\eXpansion\Gui\Windows\Window {

    private $pager;
    private $connection;
    private $storage;

    protected function onConstruct() {
        parent::onConstruct();
        $config = \ManiaLive\DedicatedApi\Config::getInstance();
        $this->connection = \DedicatedApi\Connection::factory($config->host, $config->port);
        $this->storage = \ManiaLive\Data\Storage::getInstance();

        $this->pager = new \ManiaLive\Gui\Controls\Pager();
        $this->pager->clearItems();
        
        $x = 0;
        foreach ($this->storage->maps as $map)
            $this->pager->addItem(new Mapitem($x++, $map, $this));

        $this->mainFrame->addComponent($this->pager);
    }

    function chooseNextMap($login, $mapNumber) {
        try {
            $this->hide();
            $this->connection->setNextMapIndex($mapNumber);
            $map = $this->connection->getNextMapInfo();
            $player = $this->storage->players[$login];

            $this->connection->chatSendServerMessage("The next map will be " . $map->name . '$z$s$fff by ' . $map->author);
        } catch (\Exception $e) {
            $this->connection->chatSendServerMessage('$f00$oError $z$s$fff$o' . $e->getMessage());
        }
    }

    function onResize($oldX, $oldY) {
        parent::onResize($oldX, $oldY);
        $this->pager->setSize($this->sizeX - 2, $this->sizeY - 14);
        $this->pager->setStretchContentX($this->sizeX);
        $this->pager->setPosition(8, -10);
    }

    function onShow() {
        
    }

    function destroy() {
        parent::destroy();
    }

}

?>
