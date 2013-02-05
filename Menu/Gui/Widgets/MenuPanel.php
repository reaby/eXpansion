<?php

namespace ManiaLivePlugins\eXpansion\Menu\Gui\Widgets;

use ManiaLivePlugins\eXpansion\Menu\Structures\PanelItem;

class MenuPanel extends \ManiaLive\Gui\Window {

    private $connection;
    private $storage;
    private $_windowFrame;
    private $_mainWindow;
    private $_minButton;
    private $frame;
    public static $menuItems;

    protected function onConstruct() {
        parent::onConstruct();
        /*    $config = Config::getInstance();

          $dedicatedConfig = \ManiaLive\DedicatedApi\Config::getInstance();
          $this->connection = \DedicatedApi\Connection::factory($dedicatedConfig->host, $dedicatedConfig->port);
          $this->storage = \ManiaLive\Data\Storage::getInstance(); */

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
                        mainWindow.PosnX = 150.0;                        
                        
                        while(True) {
                                if (isMinimized && mainWindow.PosnX <= 150) {                                        
                                        mainWindow.PosnX += 4; 
                                        
                                } 
                                
                                if (!isMinimized && mainWindow.PosnX >= 160) {                                     
                                      mainWindow.PosnX -= 4;                                                                            
                                }                          
                                
                                foreach (Event in PendingEvents) {                                                
                                    if (Event.Type == CMlEvent::Type::MouseClick && ( Event.ControlId == "myWindow" || Event.ControlId == "minimizeButton" )) {
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

    function setMenuItems(array $menuItems) {
        $this->frame = new \ManiaLive\Gui\Controls\Frame();
        $this->frame->setAlign("left", "top");
        $this->frame->setPosition(3, -4);
        $this->frame->setLayout(new \ManiaLib\Gui\Layouts\Column(-1));

        foreach ($menuItems as $menuItem) {
            $item = new PanelItem($menuItem);
            $this->frame->addComponent($item);
        }
        $this->_windowFrame->addComponent($this->frame);        
    }

    function destroy() {
        parent::destroy();
    }

}

?>
