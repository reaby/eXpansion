<?php

namespace ManiaLivePlugins\eXpansion\ManiaExchange\Gui\Controls;

use ManiaLivePlugins\eXpansion\Gui\Elements\Button as myButton;
use \ManiaLib\Utils\Formatting;

class MxMap extends \ManiaLive\Gui\Control {

    private $bg;
    private $label;
    private $time;
    private $addAction;
    private $frame;

    function __construct($indexNumber, \ManiaLivePlugins\eXpansion\ManiaExchange\Structures\MxMap $map, $controller, $isAdmin) {
        $sizeX = 120;
        $sizeY = 4;
        $this->isAdmin = $isAdmin;
        $this->addAction = \ManiaLive\Gui\ActionHandler::getInstance()->createAction(array($controller, 'addMap'), $indexNumber);

        $this->frame = new \ManiaLive\Gui\Controls\Frame();
        $this->frame->setSize($sizeX, $sizeY);
        $this->frame->setLayout(new \ManiaLib\Gui\Layouts\Line());

        $spacer = new \ManiaLib\Gui\Elements\Quad();
        $spacer->setSize(4, 4);
        $spacer->setAlign("center", "center2");
        $spacer->setStyle("Icons128x128_1");
        $spacer->setSubStyle("United");
        $this->frame->addComponent($spacer);

        $spacer = new \ManiaLib\Gui\Elements\Quad();
        $spacer->setSize(4, 4);
        $spacer->setStyle(\ManiaLib\Gui\Elements\Icons64x64_1::EmptyIcon);
        //$this->frame->addComponent($spacer);

        $this->label = new \ManiaLib\Gui\Elements\Label(70, 4);
        $this->label->setAlign('left', 'center');
        $this->label->setText(Formatting::stripColors(Formatting::stripStyles($map->name)));
        $this->label->setScale(0.8);
        $this->frame->addComponent($this->label);

        $info = new \ManiaLib\Gui\Elements\Label(16, 4);
        $info->setAlign('left', 'center');
        $info->setScale(0.8);
        $info->setText($map->username);
        $this->frame->addComponent($info);

        $this->time = new \ManiaLib\Gui\Elements\Label(20, 4);
        $this->time->setAlign('left', 'center');
        $this->time->setScale(0.8);
        $this->time->setText($map->lengthName);
        $this->frame->addComponent($this->time);

        $info = new \ManiaLib\Gui\Elements\Label(4, 4);
        $info->setAlign('left', 'center');
        $info->setScale(0.8);
        $info->setText($map->awardCount);
        $this->frame->addComponent($info);

        $spacer = new \ManiaLib\Gui\Elements\Quad();
        $spacer->setSize(4, 4);
        $spacer->setStyle(\ManiaLib\Gui\Elements\Icons64x64_1::EmptyIcon);

        $this->frame->addComponent($spacer);

        if ($this->isAdmin) {
            $this->addButton = new MyButton(16, 6);
            $this->addButton->setText("Install");
            $this->addButton->setAction($this->addAction);
            $this->addButton->setScale(0.6);
            $this->frame->addComponent($this->addButton);
        }

        // disabled... todo: get remove button to refresh the tracklist
        /*
          $this->removeButton = new MyButton(16,6);
          $this->removeButton->setText("Remove");
          $this->removeButton->setAction($this->removeMap);
          $this->removeButton->setScale(0.6);
          $this->frame->addComponent($this->removeButton); */

        $this->addComponent($this->frame);

        $this->sizeX = $sizeX;
        $this->sizeY = $sizeY;
    }

    protected function onResize($oldX, $oldY) {
        //$this->bg->setSize($this->sizeX, $this->sizeY);
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

