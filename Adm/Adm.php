<?php
namespace ManiaLivePlugins\eXpansion\Adm;

use \ManiaLivePlugins\eXpansion\Adm\Gui\Windows\ServerOptions;

class Adm extends \ManiaLive\PluginHandler\Plugin {

    function onInit() {
        $this->setVersion("0.0.1");
    }

    function onReady() {
    //    $methods = get_class_methods($this->connection);
          foreach ($this->storage->players as $player) {
                $info = ServerOptions::Create($player->login);		
                $info->setTitle('Server Options');
		$info->centerOnScreen();
                $info->setSize(160,80);
                $info->show();                
            }  
        }
    

}

?>