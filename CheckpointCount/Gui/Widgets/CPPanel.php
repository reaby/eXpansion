<?php

namespace ManiaLivePlugins\eXpansion\CheckpointCount\Gui\Widgets;

use ManiaLivePlugins\eXpansion\Gui\Config;

class CPPanel extends \ManiaLive\Gui\Window {
    
    private $label;

    protected function onConstruct() {
        parent::onConstruct();
  
        
        $label = new \ManiaLib\Gui\Elements\Label();        
        $label->setText('$dddCheckpoints');
        $label->setAlign("center", "top");                
        $this->addComponent($label);
        
        $this->label = new \ManiaLib\Gui\Elements\Label(16,4);                
        $this->label->setAlign("center", "top");        
        $this->addComponent($this->label);
        
    }

    function onResize($oldX, $oldY) {
        parent::onResize($oldX, $oldY);
        $this->label->setPosition(0,-5);
    }

    function onShow() {
        
    }

    function setText($text) {
        $this->label->setText($text);    
    }
    function destroy() {
        parent::destroy();
    }

}

?>
