<?php
namespace ManiaLivePlugins\eXpansion\Maps\Gui\Controls;

use ManiaLivePlugins\eXpansion\Gui\Elements\Button as myButton;
use \ManiaLib\Utils\Formatting;

class Mapitem extends \ManiaLive\Gui\Control {
    
    private $bg;
    private $button;
    private $label;
    private $time;
    private $chooseNextMap;
    private $frame;
    
    function __construct($indexNumber, \DedicatedApi\Structures\Map $map, $controller, $sizeX = 120, $sizeY = 4) {
        $this->chooseNextMap = \ManiaLive\Gui\ActionHandler::getInstance()->createAction(array($controller, 'chooseNextMap'), $indexNumber);
        
        $this->bg = new \ManiaLib\Gui\Elements\Quad($sizeX, $sizeY);
        $this->bg->setAlign('left', 'center');
        if ($indexNumber % 2 == 0) {
            $this->bg->setBgcolor('fff4');
        } else {
            $this->bg->setBgcolor('77f4');
        }
        $this->bg->setScriptEvents(true);
       // $this->addComponent($this->bg);

        
        $this->frame = new \ManiaLive\Gui\Controls\Frame();
        $this->frame->setSize($sizeX, $sizeY);
        $this->frame->setLayout(new \ManiaLib\Gui\Layouts\Line());
        
        $spacer = new \ManiaLib\Gui\Elements\Quad();
        $spacer->setSize(4,4); 
        $spacer->setAlign("center", "center2");
        $spacer->setStyle("Icons128x128_1");
        $spacer->setSubStyle("Challenge");
        $this->frame->addComponent($spacer);
        
        $spacer = new \ManiaLib\Gui\Elements\Quad();
        $spacer->setSize(4,4);
        $spacer->setStyle(\ManiaLib\Gui\Elements\Icons64x64_1::EmptyIcon);
        //$this->frame->addComponent($spacer);
        
        $this->label = new \ManiaLib\Gui\Elements\Label(90, 4);
        $this->label->setAlign('left', 'center');
        $this->label->setText(Formatting::stripColors(Formatting::stripStyles($map->name)));
        $this->label->setScale(0.8);
        $this->frame->addComponent($this->label);
        
        $this->time = new \ManiaLib\Gui\Elements\Label(16, 4);
        $this->time->setAlign('left', 'center');
        $this->time->setScale(0.8);
        $this->time->setText(\ManiaLive\Utilities\Time::fromTM($map->goldTime));
        $this->frame->addComponent($this->time);
        
        $spacer = new \ManiaLib\Gui\Elements\Quad();
        $spacer->setSize(4,4);
        $spacer->setStyle(\ManiaLib\Gui\Elements\Icons64x64_1::EmptyIcon);
        
        $this->frame->addComponent($spacer);
        
        $this->button = new MyButton(16,6);
        $this->button->setText("Go!");        
        $this->button->setAction($this->chooseNextMap);
        $this->button->setScale(0.6);
        $this->frame->addComponent($this->button);
        
        
        $this->addComponent($this->frame);
        
        $this->sizeX = $sizeX;
        $this->sizeY = $sizeY;
    }

    protected function onResize($oldX, $oldY) {
        $this->bg->setSize($this->sizeX, $this->sizeY);
        $this->frame->setSize($this->sizeX, $this->sizeY);
      //  $this->button->setPosx($this->sizeX - $this->button->sizeX);
    }

    function onDraw() {
        
    }

    function __destruct() {

        //       \ManiaLive\Gui\ActionHandler::getInstance()->removeAction($this->chooseNextMap);
    }

}
?>

