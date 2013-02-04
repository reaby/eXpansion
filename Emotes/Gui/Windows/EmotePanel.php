<?php

namespace ManiaLivePlugins\eXpansion\Emotes\Gui\Windows;

use ManiaLivePlugins\eXpansion\Emotes\Config;

class EmotePanel extends \ManiaLive\Gui\Window {

    private $connection;
    private $storage;
    private $_windowFrame;
    private $_mainWindow;
    private $_minButton;
    private $servername;
    
    private $btnBG;
    private $btnGG;
    private $btnLOL;
    private $btnAfk;    
    private $actionGG;
    private $actionBG;
    private $actionLOL;
    private $actionAfk;
    

    protected function onConstruct() {
        parent::onConstruct();
        $config = Config::getInstance();

        $dedicatedConfig = \ManiaLive\DedicatedApi\Config::getInstance();
        $this->connection = \DedicatedApi\Connection::factory($dedicatedConfig->host, $dedicatedConfig->port);
        $this->storage = \ManiaLive\Data\Storage::getInstance();

        $this->actionGG = \ManiaLive\Gui\ActionHandler::getInstance()->createAction(array($this, 'actions'), "GG");
        $this->actionBG = \ManiaLive\Gui\ActionHandler::getInstance()->createAction(array($this, 'actions'), "BG");
        $this->actionAfk = \ManiaLive\Gui\ActionHandler::getInstance()->createAction(array($this, 'actions'), "Afk");
        $this->actionLol = \ManiaLive\Gui\ActionHandler::getInstance()->createAction(array($this, 'actions'), "Lol");
        

        $this->setScriptEvents(true);
        $this->setAlign("left", "top");

        $this->_windowFrame = new \ManiaLive\Gui\Controls\Frame();
        $this->_windowFrame->setAlign("left", "top");
        $this->_windowFrame->setId("Frame");
        $this->_windowFrame->setScriptEvents(true);

        $this->_mainWindow = new \ManiaLib\Gui\Elements\Quad(60, 10);
        $this->_mainWindow->setId("MainWindow");
        $this->_mainWindow->setStyle("BgsPlayerCard");
        $this->_mainWindow->setSubStyle("BgPlayerCardBig");
        $this->_mainWindow->setAlign("left", "center");
        $this->_windowFrame->addComponent($this->_mainWindow);
        
        $frame = new \ManiaLive\Gui\Controls\Frame();
        $frame->setAlign("left", "top");
        $frame->setLayout(new \ManiaLib\Gui\Layouts\Line());
        $frame->setPosition(6,4);
        
        $this->btnLol = new \ManiaLib\Gui\Elements\Quad(7,7);
        $this->btnLol->setImage($config->iconLol);
        $this->btnLol->setAction($this->actionLol);
        $frame->addComponent($this->btnLol);

        $this->btnBG = new \ManiaLib\Gui\Elements\Quad(7,7);
        $this->btnBG->setImage($config->iconBG);
        $this->btnBG->setAction($this->actionBG);
        $frame->addComponent($this->btnBG);
        
        $this->btnGG = new \ManiaLib\Gui\Elements\Quad(7,7);
        $this->btnGG->setImage($config->iconGG);
        $this->btnGG->setAction($this->actionGG);
        $frame->addComponent($this->btnGG);
        
        $this->btnAfk = new \ManiaLib\Gui\Elements\Quad(7,7);
        $this->btnAfk->setImage($config->iconAfk);
        $this->btnAfk->setAction($this->actionAfk);
        $frame->addComponent($this->btnAfk);
       
        $this->_windowFrame->addComponent($frame);
        
        $this->_minButton = new \ManiaLib\Gui\Elements\Quad(5, 5);
        $this->_minButton->setId("minimizeButton");
        //$this->_minButton->setStyle("Icons128x128_1");
        //$this->_minButton->setSubStyle("ProfileAdvanced");
        $this->_minButton->setImage($config->iconMenu);
        $this->_minButton->setScriptEvents(true);
        $this->_minButton->setAlign("left", "bottom");

        $this->_windowFrame->addComponent($this->_minButton);

        $this->addComponent($this->_windowFrame);

        $xml = new \ManiaLive\Gui\Elements\Xml();
        $xml->setContent('
        <timeout>0</timeout>            
        <script><!--
                       main () {
                       
                        declare Window <=> Page.GetFirstChild("' . $this->getId() . '");
                        declare mainWindow <=> Page.GetFirstChild("Frame");
                        declare isMinimized = False;                                          
                        mainWindow.PosnX = -50.0;

                        while(True) {
                                if (isMinimized && mainWindow.PosnX <= 0) {                                        
                                        mainWindow.PosnX += 4; 
                                        
                                } 
                                
                                if (!isMinimized && mainWindow.PosnX >= -50) {                                     
                                      mainWindow.PosnX -= 4;                                                                            
                                }                          
                                
                                foreach (Event in PendingEvents) {                                                
                                    if (Event.Type == CMlEvent::Type::MouseClick && Event.ControlId == "minimizeButton") {
                                           isMinimized = !isMinimized;                                          
                                    }                                       
                                }
                                yield;                        
                        }
                  
                  
                } 
                --></script>');
        $this->addComponent($xml);
    }

    function onResize($oldX, $oldY) {
        parent::onResize($oldX, $oldY);
        $this->_windowFrame->setSize(60, 12);
        $this->_mainWindow->setSize(60, 6);        
        $this->_minButton->setPosition(60 - 6, -2.5);
        
    }

    function actions($login, $action) {
        try {
            $player = $this->storage->getPlayerObject($login);
            switch ($action) {
                case "GG": 
                    $this->connection->chatSendServerMessage($player->nickName.'$z$s$i$o$f90 Good Game, everybody!');
                    break;                                
                case "BG": 
                    $this->connection->chatSendServerMessage($player->nickName.'$z$s$i$o$f90 I had a bad game :(');
                    break;
                
                case "Afk": 
                    $this->connection->chatSendServerMessage($player->nickName.'$z$s$i$o$f90 is away from the keyboard!');
                    break;
                
                 case "Lol": 
                    $this->connection->chatSendServerMessage($player->nickName.'  $z$s$i$fff is laughing out loud: $o$FF0L$FE1o$FD1o$FB2o$FA2o$F93o$F93o$F72o$F52o$F41o$F21o$F00L');
                    break;
                
                
            }            
        } catch (\Exception $e) {
            $this->connection->chatSendServerMessage('$f00$bError! $z$s$fff'.$e->getMessage(),$login);
        }
    }

    function onShow() {
        
    }

    function destroy() {
        parent::destroy();
    }

}
?>