<?php
namespace ManiaLivePlugins\eXpansion\Gui\Windows;

use ManiaLivePlugins\eXpansion\Gui\Config;

class Window extends \ManiaLive\Gui\Window {

    public $titlebar;
    public $title;
    public $mainWindow;
    public $mainFrame;
    public $mainText;
    public $closebutton;
    public $frame;

    protected function onConstruct() {
        parent::onConstruct();  
        $config = Config::getInstance();
        
        $this->frame = new \ManiaLive\Gui\Controls\Frame($this->sizeX, $this->sizeY);                   
        $this->frame->setScriptEvents(true);
        $this->frame->setId("Window");


        $this->mainWindow = new \ManiaLib\Gui\Elements\Quad($this->sizeX, $this->sizeY);                
        $this->mainWindow->setId("MainWindow");
        // $this->mainWindow->setScriptEvents(true);
        $this->mainWindow->setStyle("Bgs1InRace");
        $this->mainWindow->setSubStyle("BgCardOnline");
        $this->frame->addComponent($this->mainWindow);
           
        $this->titlebar = new \ManiaLib\Gui\Elements\Quad($this->sizeX, $this->sizeY);         
        $this->titlebar->setId("Titlebar");
        $this->titlebar->setImage($config->windowTitlebar);
        $this->titlebar->setScriptEvents(true);
                $this->titlebar->setPosition(0.5,0);
        $this->frame->addComponent($this->titlebar);

        $this->title = new \ManiaLib\Gui\Elements\Label(60, 4);
        $this->title->setStyle("TextCardInfoSmall");        
        $this->title->setScale(0.9);
        $this->frame->addComponent($this->title);

        $this->closebutton = new \ManiaLib\Gui\Elements\Quad(5, 5);
        $this->closebutton->setScriptEvents(true);
        $this->closebutton->setId("Close");
        $this->closebutton->setHalign("right");
        $this->closebutton->setImage($config->windowClosebutton);
        $this->closebutton->setImageFocus($config->windowClosebuttonActive);
        $this->closebutton->setPosZ($this->posZ-1);
        
        $this->frame->addComponent($this->closebutton);

        $this->mainText = new \ManiaLib\Gui\Elements\Label($this->sizeX, 3);
        $this->mainText->setPosition(4,-6);        
        $this->frame->addComponent($this->mainText);
   
        $this->mainFrame = new \ManiaLive\Gui\Controls\Frame($this->sizeX, $this->sizeY-5);       
        $this->mainFrame->setPosY(-3);    
        $this->frame->addComponent($this->mainFrame);

        $this->addComponent($this->frame);
        
        $xml = new \ManiaLive\Gui\Elements\Xml();
        $xml->setContent('<script><!--
                       main () {                                                                          
                        declare Window <=> Page.GetFirstChild("'.$this->frame->getId().'");   
                        declare MoveWindow = False;
                        declare CloseWindow = False;                        
                        declare Real CloseCounter = 1.0;
                        declare Real OpenCounter = 0.0;
                        log("hello world");
                        
                        while(True) {
                                
                                
                                if (MoveWindow) {
                                        Window.PosnX = MouseX - 50;
                                        Window.PosnY = MouseY;                         
                                }
                
                                        
                                if (Window.Scale <= 1 && !CloseWindow) {
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
                                                }
                                yield;                        
                        }
                  
                  
                } 
                --></script>');
        $this->addComponent($xml);
    }

    function onResize($oldX, $oldY) {
        parent::onResize($oldX, $oldY);
        $this->frame->setSize($this->sizeX, $this->sizeY);
        $this->mainWindow->setSize($this->sizeX+1, $this->sizeY);
        $this->mainText->setSize($this->sizeX, $this->sizeY);
        $this->title->setSize($this->sizeX, 4);
        $this->title->setPosition(($this->title->sizeX/2),-1);                  
        $this->title->setHalign("center");
                
        $this->titlebar->setSize($this->sizeX, 4);        
        $this->closebutton->setSize(4, 4);
        $this->closebutton->setPosition($this->sizeX-1, 0);
        $this->mainFrame->setSize($this->sizeX-4, $this->sizeY-8);
        $this->mainFrame->setPosY(-6);        
    }

    function onShow() {

    }

    function setText($text) {
        $this->mainText->setText($text);
    }

    function setTitle($text) {
        $this->title->setText('$fff'.$text);
                
     }

    function destroy() {
        parent::destroy();
    }

}

?>
