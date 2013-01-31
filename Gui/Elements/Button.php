<?php
namespace ManiaLivePlugins\eXpansion\Gui\Elements;

use ManiaLivePlugins\eXpansion\Gui\Config;

class Button extends \ManiaLive\Gui\Control
{
	private $label;
	private $button;
                
	function __construct($sizeX=25, $sizeY=7)
	{
                
                $config = Config::getInstance();
		$this->button= new \ManiaLib\Gui\Elements\Quad($sizeX, $sizeY);
                $this->button->setAlign('center', 'center');
                $this->button->setImage($config->button);
                $this->button->setImageFocus($config->buttonActive);
                $this->button->setScriptEvents(true);                
                $this->addComponent($this->button);
                
                $this->label = new \ManiaLib\Gui\Elements\Label($sizeX, $sizeY);
		$this->label->setAlign('center', 'center');
		$this->label->setStyle("TextCardSmallScores2");
		$this->label->setFocusAreaColor1('555');
		$this->label->setFocusAreaColor2('555');
                $this->label->setScriptEvents(true);
		$this->addComponent($this->label);
                
		$this->setSize($sizeX, $sizeY);
	}
	
	protected function onResize($oldX, $oldY)
	{            
                $this->button->setSize($this->sizeX, $this->sizeY);
                $this->button->setPosition($this->sizeX / 2, $this->sizeY / 2);
		$this->label->setSize($this->sizeX, $this->sizeY);
                $this->label->setPosition($this->sizeX / 2, $this->sizeY / 2);
	}
	
	function onDraw()
	{
		
	}
	
	function getText()
	{
		return $this->label->getText();
	}
	
	function setText($text)
	{
		$this->label->setText('$fff'.$text);
	}
	
	function setAction($action)
	{
		$this->button->setAction($action);
                $this->label->setAction($action);
	}
        
}

?>