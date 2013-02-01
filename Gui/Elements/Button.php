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
                $this->button->setAlign('left', 'center');
                $this->button->setImage($config->button);
                $this->button->setImageFocus($config->buttonActive);
                $this->button->setScriptEvents(true);                
                $this->addComponent($this->button);
                
                $this->label = new \ManiaLib\Gui\Elements\Label($sizeX, $sizeY);
		$this->label->setAlign('center', 'center');
		//$this->label->setStyle("TextCardInfoSmall");
		$this->label->setFocusAreaColor1('000');
		$this->label->setFocusAreaColor2('000');
                $this->label->setScriptEvents(true);
		$this->addComponent($this->label);
                $this->sizeX = $sizeX;
                $this->sizeY = $sizeY;
		$this->setSize($sizeX, $sizeY);
	}
	
	protected function onResize($oldX, $oldY)
	{            
                $this->button->setSize($this->sizeX, $this->sizeY);
                
		$this->label->setSize($this->sizeX, $this->sizeY);
                $this->label->setPosX($this->sizeX/2);
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
		$this->label->setText('$000'.$text);
	}
	
	function setAction($action)
	{
		$this->button->setAction($action);
                $this->label->setAction($action);
	}
        
}

?>