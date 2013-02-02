<?php

namespace ManiaLivePlugins\eXpansion\ManiaExchange\Gui\Windows;

use \ManiaLivePlugins\eXpansion\Gui\Elements\Button as OkButton;
use \ManiaLivePlugins\eXpansion\Gui\Elements\Inputbox;
use \ManiaLivePlugins\eXpansion\Gui\Elements\Checkbox;
use \ManiaLivePlugins\eXpansion\Gui\Elements\Ratiobutton;
use ManiaLivePlugins\eXpansion\ManiaExchange\Structures\MxMap as Map;
use ManiaLivePlugins\eXpansion\ManiaExchange\Gui\Controls\MxMap;
use ManiaLive\Gui\ActionHandler;

class MxSearch extends \ManiaLivePlugins\eXpansion\Gui\Windows\Window {

    private $pager;
    private $connection;
    private $storage;
    private $maps;
    private $frame;
    private $header;
    public static $mxPlugin;
    
    protected function onConstruct() {
        parent::onConstruct();

        $config = \ManiaLive\DedicatedApi\Config::getInstance();
        $this->connection = \DedicatedApi\Connection::factory($config->host, $config->port);
        $this->storage = \ManiaLive\Data\Storage::getInstance();

        $this->frame = new \ManiaLive\Gui\Controls\Frame();
        $this->frame->setLayout(new \ManiaLib\Gui\Layouts\Column());

        $this->header = new \ManiaLivePlugins\eXpansion\ManiaExchange\Gui\Controls\Header();       
        $this->frame->addComponent($this->header);

        $this->pager = new \ManiaLive\Gui\Controls\Pager();

        $this->frame->addComponent($this->pager);

        $this->mainFrame->addComponent($this->frame);
    }

    function onResize($oldX, $oldY) {
        parent::onResize($oldX, $oldY);
        $this->frame->setSizeX($this->sizeX);
        $this->header->setSize($this->sizeX, 5);
        $this->pager->setSize($this->sizeX - 2, $this->sizeY - 14);
        $this->pager->setStretchContentX($this->sizeX);
        $this->frame->setPosition(8, -10);
    }

    function onShow() {
        
    }

    public function search($trackname) {

        $query = 'http://tm.mania-exchange.com/tracksearch?mode=0&vm=0&trackname=' . rawurlencode($trackname) . '&mtype=All&tpack=All&priord=3&limit=40&environments=1&tracksearch&api=on&format=json';
        $ch = curl_init($query);
        curl_setopt($ch, CURLOPT_USERAGENT, "Manialive/eXpansion MXapi [search] ver 0.1");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($ch);
        if ($json === false) {
            $this->connection->chatSendServerMessage("no tracks found or error while getting data from MX", $this->getRecipient());
            return;
        }
        curl_close($ch);

        $this->maps = Map::fromArrayOfArray(json_decode($json, true));

        $this->pager->clearItems();

        $x = 0;
        $login = $this->getRecipient();
        foreach ($this->maps as $map) {
            $item = new MxMap($x++, $map, $this, \ManiaLive\Features\Admin\AdminGroup::contains($login));            
            $this->pager->addItem($item);
        }
    }

    function addMap($login, $mapId) {
        self::$mxPlugin->addMap($login, $mapId);        
    }

    function destroy() {
        parent::destroy();
    }

}

?>
