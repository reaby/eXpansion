<?php

namespace ManiaLivePlugins\eXpansion\LocalRecords\Gui\Widgets;

use ManiaLivePlugins\eXpansion\Gui\Config;
use ManiaLivePlugins\eXpansion\LocalRecords\Gui\Controls\Recorditem;

class LRPanel extends \ManiaLive\Gui\Window {

    private $connection;
    private $storage;
    private $_windowFrame;
    private $_mainWindow;
    private $_minButton;
    public static $records;

    protected function onConstruct() {
        parent::onConstruct();
        $config = Config::getInstance();

        $dedicatedConfig = \ManiaLive\DedicatedApi\Config::getInstance();
        $this->connection = \DedicatedApi\Connection::factory($dedicatedConfig->host, $dedicatedConfig->port);
        $this->storage = \ManiaLive\Data\Storage::getInstance();
        $this->setScriptEvents(true);
        $this->setAlign("left", "top");

        $this->_windowFrame = new \ManiaLive\Gui\Controls\Frame();
        $this->_windowFrame->setPosY(0);
        $this->_windowFrame->setAlign("left", "top");
        $this->_windowFrame->setId("Frame");
        $this->_windowFrame->setScriptEvents(true);

        $this->_mainWindow = new \ManiaLib\Gui\Elements\Quad(60, 10);
        $this->_mainWindow->setId("myWindow");
        $this->_mainWindow->setStyle("BgsPlayerCard");
        $this->_mainWindow->setSubStyle("BgPlayerCardBig");
        $this->_mainWindow->setAlign("left", "top");     
        $this->_mainWindow->setScriptEvents(true);
        $this->_windowFrame->addComponent($this->_mainWindow);

        $frame = new \ManiaLive\Gui\Controls\Frame();
        $frame->setAlign("left", "top");
        $frame->setPosition(6,-4);
        $frame->setLayout(new \ManiaLib\Gui\Layouts\Column(-1));
        $index = 1;
        foreach (self::$records as $record)
            $frame->addComponent(new recordItem($index++, $record));

        $this->_windowFrame->addComponent($frame);

        $this->_minButton = new \ManiaLib\Gui\Elements\Quad(5, 5);
        $this->_minButton->setId("minimizeButton");
        $this->_minButton->setStyle("Icons128x32_1");
        $this->_minButton->setSubStyle("RT_Cup");
        $this->_minButton->setScriptEvents(true);
        $this->_minButton->setAlign("left", "center");

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
                                if (isMinimized && mainWindow.PosnX <= -4) {                                        
                                        mainWindow.PosnX += 4; 
                                        
                                } 
                                
                                if (!isMinimized && mainWindow.PosnX >= -50) {                                     
                                      mainWindow.PosnX -= 4;                                                                            
                                }                          
                                
                                foreach (Event in PendingEvents) {                                                
                                    if (Event.Type == CMlEvent::Type::MouseClick && Event.ControlId == "myWindow") {
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
        $this->_mainWindow->setSize(60, 60);
        $this->_minButton->setPosition(60 - 6, -30);
    }

    function onShow() {
        
    }

    function destroy() {
        parent::destroy();
    }

}

?>
