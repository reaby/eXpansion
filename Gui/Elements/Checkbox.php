<?php
namespace ManiaLivePlugins\eXpansion\Gui\Elements;

use ManiaLivePlugins\eXpansion\Gui\Config;
use ManiaLive\Gui\ActionHandler;

class Checkbox extends \ManiaLive\Gui\Control
{
	private $label;
	private $button;
        private $active = false;
        private $textWidth;
        private $action;
        
        function __construct($sizeX=4, $sizeY=4, $textWidth=25)
	{
                $this->textWidth = $textWidth;
                $this->action = ActionHandler::getInstance()->CreateAction(array($this, 'toggleActive'));
                $config = Config::getInstance();
		$this->button= new \ManiaLib\Gui\Elements\Quad($sizeX, $sizeY);
                $this->button->setAlign('left', 'center');
                $this->button->setImage($config->checkbox);
                $this->button->setAction($this->action);
                $this->button->setScriptEvents(true);
                $this->addComponent($this->button);
                
                $this->label = new \ManiaLib\Gui\Elements\Label($textWidth, 4);
		$this->label->setAlign('left', 'center');
		$this->label->setStyle("TextCardSmallScores2");		                
		$this->addComponent($this->label);
                
		$this->setSize($sizeX+$textWidth, $sizeY);
	}
	
	protected function onResize($oldX, $oldY)
	{            
                $this->button->setSize($this->sizeX - $this->textWidth, $this->sizeY);
                $this->button->setPosition(0,-0.5);
		$this->label->setSize($this->textWidth, $this->sizeY);
                $this->label->setPosition($this->sizeX-$this->textWidth+1, 0);
	}
	
	function onDraw()
	{
            $config = Config::getInstance();
        
            if ($this->active) {
                $this->button->setImage($config->checkboxActive);                
            } else {
                $this->button->setImage($config->checkbox);                
            }
	}
	
        function setStatus($boolean) {
            $this->active = $boolean;            
        }
        
        function getStatus() {
            return $this->active;
        }
        
	function getText()
	{
		return $this->label->getText();
	}
	
	function setText($text)
	{
		$this->label->setText('$aaa'.$text);
	}
	
        function toggleActive($login) {
            $this->active = !$this->active;            
            $this->redraw();
        }
        
	function setAction($action)
	{
		$this->button->setAction($action);           
	}
        function __destruct() {
            ActionHandler::getInstance()->removeAction($this->action);
        }
}

?>