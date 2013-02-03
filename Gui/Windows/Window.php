<?php

namespace ManiaLivePlugins\eXpansion\Gui\Windows;

use ManiaLivePlugins\eXpansion\Gui\Config;

class Window extends \ManiaLive\Gui\Window {

    public $_titlebar;
    public $_title;
    public $_mainWindow;
    public $mainFrame;
    public $_mainText;
    public $_closebutton;
    public $_minbutton;
    public $_windowFrame;

    protected function onConstruct() {
        parent::onConstruct();
        $config = Config::getInstance();
        $this->setId("theWindow");
        $this->setScale(0);
        $this->_windowFrame = new \ManiaLive\Gui\Controls\Frame($this->sizeX, $this->sizeY);
        $this->_windowFrame->setScriptEvents(true);
        $this->_windowFrame->setId("Window");


        $this->_mainWindow = new \ManiaLib\Gui\Elements\Quad($this->sizeX, $this->sizeY);
        $this->_mainWindow->setId("MainWindow");
        // $this->mainWindow->setScriptEvents(true);
        $this->_mainWindow->setStyle("Bgs1InRace");
        $this->_mainWindow->setSubStyle("BgWindow2");
        $this->_mainWindow->setScriptEvents(true);
        $this->_windowFrame->addComponent($this->_mainWindow);

        $this->_titlebar = new \ManiaLib\Gui\Elements\Quad($this->sizeX, $this->sizeY);
        $this->_titlebar->setId("Titlebar");
        $this->_titlebar->setImage($config->windowTitlebar);
        $this->_titlebar->setScriptEvents(true);        
        $this->_windowFrame->addComponent($this->_titlebar);

        $this->_title = new \ManiaLib\Gui\Elements\Label(60, 4);
        $this->_title->setStyle("TextCardInfoSmall");
        $this->_title->setScale(0.9);
        $this->_windowFrame->addComponent($this->_title);

        $this->_closebutton = new \ManiaLib\Gui\Elements\Quad(5, 5);
        $this->_closebutton->setScriptEvents(true);
        $this->_closebutton->setId("Close");
        $this->_closebutton->setHalign("right");
        $this->_closebutton->setImage($config->windowClosebutton);
        $this->_closebutton->setImageFocus($config->windowClosebuttonActive);
        $this->_closebutton->setPosZ($this->posZ - 1);
        $this->_windowFrame->addComponent($this->_closebutton);

        $this->_minbutton = new \ManiaLib\Gui\Elements\Quad(5, 5);
        $this->_minbutton->setScriptEvents(true);
        $this->_minbutton->setId("Minimize");
        $this->_minbutton->setHalign("right");
        $this->_minbutton->setImage($config->windowMinbutton);
        $this->_minbutton->setImageFocus($config->windowMinbuttonActive);
        $this->_minbutton->setPosZ($this->posZ - 1);

        $this->_windowFrame->addComponent($this->_minbutton);


        $this->_mainText = new \ManiaLib\Gui\Elements\Label($this->sizeX, 3);
        $this->_mainText->setPosition(4, -6);
        $this->_windowFrame->addComponent($this->_mainText);

        $this->mainFrame = new \ManiaLive\Gui\Controls\Frame($this->sizeX, $this->sizeY - 5);
        $this->mainFrame->setPosY(-3);
        $this->_windowFrame->addComponent($this->mainFrame);

        $this->addComponent($this->_windowFrame);

        $xml = new \ManiaLive\Gui\Elements\Xml();
        $xml->setContent('
        <timeout>0</timeout>            
        <script><!--
                       main () {     
                        declare Window <=> Page.GetFirstChild("' . $this->getId() . '");   
                        
                        declare MoveWindow = False;
                        declare CloseWindow = False;   
                        declare isMinimized = False;   
                        declare Real CloseCounter = 1.0;
                        declare Real OpenCounter = 0.0;                        
                        
                        while(True) {
                                
                                
                                if (MoveWindow) {
                                        Window.PosnX = MouseX;
                                        Window.PosnY = MouseY;
                                }
                
                                        
                                if (Window.Scale <= 1 && !CloseWindow && !isMinimized) {
                                   Window.Scale +=0.075;
                                }
                                

                                if (CloseWindow)
                                {                                                                       
                                        Window.Scale = CloseCounter;
                                        if (CloseCounter <= 0) {
                                                Window.Hide();
                                                CloseWindow = False;
                                        }                                
                                        CloseCounter -=0.075;
                                }
                                
                                if (MouseLeftButton == True) {
                                        foreach (Event in PendingEvents) {
                                                        if (Event.ControlId == "Titlebar") {
                                                                MoveWindow = True;
                                                                }                                                                 
                                                }
                                        }
                                else {
                                        MoveWindow = False;
                                }
                                
                                foreach (Event in PendingEvents) {                                                
                                    if (Event.Type == CMlEvent::Type::MouseClick && Event.ControlId == "Close") {
                                            CloseWindow = True;
                                    }   
                                    if (Event.Type == CMlEvent::Type::MouseClick && Event.ControlId == "Minimize") {
                                            Window.Scale = 0.25;
                                            isMinimized = True;
                                    }   
                                    if (Event.Type == CMlEvent::Type::MouseClick && Event.ControlId == "MainWindow") {                                            
                                            isMinimized = False;
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
        $this->_windowFrame->setSize($this->sizeX, $this->sizeY);
        $this->_mainWindow->setSize($this->sizeX+0.6, $this->sizeY);
        $this->_mainWindow->setPosX(-0.4);
        $this->_mainText->setSize($this->sizeX, $this->sizeY);
        $this->_title->setSize($this->sizeX, 4);
        $this->_title->setPosition(($this->_title->sizeX / 2), -1);
        $this->_title->setHalign("center");

        $this->_titlebar->setSize($this->sizeX, 4);
        $this->_closebutton->setSize(4, 4);
        $this->_closebutton->setPosition($this->sizeX - 1, 0);
        $this->_minbutton->setSize(4, 4);
        $this->_minbutton->setPosition($this->sizeX - 5, 0);
        $this->mainFrame->setSize($this->sizeX - 4, $this->sizeY - 8);
        $this->mainFrame->setPosY(-6);
    }

    function onShow() {
        
    }

    function setText($text) {
        $this->_mainText->setText($text);
    }

    function setTitle($text) {
        $this->_title->setText('$fff' . $text);
    }

    function destroy() {
        parent::destroy();
    }

}

?>
