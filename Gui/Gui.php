<?php
namespace ManiaLivePlugins\eXpansion\Gui;

use ManiaLive\Utilities\Console;
use ManiaLivePlugins\eXpansion\Gui\Windows\Window as Win;
 
class Gui extends \ManiaLive\PluginHandler\Plugin {

	private $players = array();

	public function onInit() {
		$this->setVersion('0.1.0');		
	}

	function onLoad() {
		$this->enableDedicatedEvents();		
	}
	
	function onReady() {
            foreach ($this->storage->players as $player) {
                $info = Win::Create($player->login);		
                $info->setTitle('Test Window');
		$info->setText("This should generate text for my newly created window.");		
                $info->setPosition(0,50);
                $info->setSize(100,20);
                $info->show();                
            }
        }
	
	
	
}

?>