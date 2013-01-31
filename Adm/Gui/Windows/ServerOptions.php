<?php

namespace ManiaLivePlugins\eXpansion\Adm\Gui\Windows;

use \ManiaLive\Gui\Controls\Pager;
use \ManiaLivePlugins\eXpansion\Gui\Elements\Button;
use \ManiaLivePlugins\eXpansion\Gui\Elements\Checkbox;
use \ManiaLivePlugins\eXpansion\Gui\Elements\Ratiobutton;

use ManiaLive\Gui\ActionHandler;
class ServerOptions extends \ManiaLivePlugins\eXpansion\Gui\Windows\Window {
    
    private $serverName, $serverComment, $maxPlayers, $minPlayers, $minLadder, $maxLadder, $serverPass, $serverSpectPass, $refereePass;
    private $cbPublicServer, $cbLadderServer, $cbAllowMapDl, $cbAllowp2pDown, $cbAllowp2pUp, $cbValidation, $cbReferee;
    private $frameCb;
    private $actionOK;
    private $actionCancel;
    private $connection;
    public static $adminPlugin;

    function onConstruct() {        
        parent::onConstruct();
        $config = \ManiaLive\DedicatedApi\Config::getInstance();
        $this->connection = \DedicatedApi\Connection::factory($config->host, $config->port);
        $server = \ManiaLive\Data\Storage::getInstance()->server;
        
        $this->setTitle('Server Options');        
        print_r($server);
        
        $this->checkboxes();    
        
        $this->mainFrame->addComponent($this->frameCb);
        
        $this->actionOK = ActionHandler::getInstance()->createAction(array($this, "Ok"));               
        $this->actionCancel = ActionHandler::getInstance()->createAction(array($this, "cancel"));               
        }
    
        // Generate all checkboxes
        private function checkboxes() {
        $server = \ManiaLive\Data\Storage::getInstance()->server;
        
        $this->frameCb = new \ManiaLive\Gui\Controls\Frame();        
        $this->frameCb->setAlign("left","top");
        $this->frameCb->setLayout(new \ManiaLib\Gui\Layouts\Column());
        
        // checkbox for public server 
        $publicServer = true; 
        if ($server->hideServer > 0) $publicServer = false;  // 0 = visible, 1 = hidden 2 = hidden from nations
        $this->cbPublicServer = new Checkbox(4,4,50);               
        $this->cbPublicServer->setStatus($publicServer);
        $this->cbPublicServer->setText("Show Server in public server list");
        $this->frameCb->addComponent($this->cbPublicServer);
        
        // checkbox for ladder server
        $this->cbLadderServer = new Checkbox();
        $this->cbLadderServer->setStatus($server->currentLadderMode);
        $this->cbLadderServer->setText("Ladder server");
        $this->frameCb->addComponent($this->cbLadderServer);
            
        // checkbox for allow map download
        $this->cbAllowMapDl = new Checkbox(4,4,50);            
        $this->cbAllowMapDl->setStatus($server->allowMapDownload);
        $this->cbAllowMapDl->setText("Allow map download using ingame menu");
        $this->frameCb->addComponent($this->cbAllowMapDl);    
        
        // checkbox for p2p download
        $this->cbAllowp2pDown = new Checkbox(4,4,50);            
        $this->cbAllowp2pDown->setStatus($server->isP2PDownload );
        $this->cbAllowp2pDown->setText("Allow Peer-2-Peer download");
        $this->frameCb->addComponent($this->cbAllowp2pDown);    

        // checkbox for p2p upload
        $this->cbAllowp2pUp =new Checkbox(4,4,50);            
        $this->cbAllowp2pUp->setStatus($server->isP2PUpload );
        $this->cbAllowp2pUp->setText("Allow Peer-2-Peer upload");
        $this->frameCb->addComponent($this->cbAllowp2pUp);    
        
        // checkbox for changing validation seed
        $this->cbValidation =new Checkbox(4,4,50);            
        $this->cbValidation->setStatus($server->useChangingValidationSeed );
        $this->cbValidation->setText("Allow changing validation seed");
        $this->frameCb->addComponent($this->cbValidation);    
        
        // checkbox for Enable referee mode
        $this->cbReferee =new Checkbox(4,4,50);            
        $this->cbReferee->setStatus($server->refereeMode );
        $this->cbReferee->setText("Enable Referee-mode");
        $this->frameCb->addComponent($this->cbReferee);    
        
        }
    function onDraw() {
        parent::onDraw();        
    }

    public function Ok($login) {        
        $this->redraw();
    }
    public function cancel($login) {        
        $this->redraw();
    }
    
    public function updateData() {
        $this->pager->clearItems();
        
        $this->redraw();
    }

    function destroy() {
        self::$adminPlugin = null;
        ActionHandler::getInstance()->removeAction($this->actionOK);  
        ActionHandler::getInstance()->removeAction($this->actionCancel);  
        parent::destroy();
    }

    function onResize($oldX, $oldY) {
        parent::onResize($oldX, $oldY);
     //   $this->pager->setSize($this->sizeX - 4, $this->sizeY -12);
        $this->frameCb->setPosition($this->sizeX/2+20 , -$this->sizeY/2);
    }

}