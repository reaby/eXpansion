<?php
namespace ManiaLivePlugins\eXpansion\Adm;

use \ManiaLivePlugins\eXpansion\Adm\Gui\Windows\ServerOptions;
use ManiaLive\Gui\ActionHandler;
class Adm extends \ManiaLive\PluginHandler\Plugin {

    function onInit() {
        $this->setVersion("0.0.1");
    }

    function onReady() {
    //    $methods = get_class_methods($this->connection);
        ServerOptions::$actionOK = ActionHandler::getInstance()->createAction(array($this, "serverOptionsOk"));
        ServerOptions::$actionCancel = ActionHandler::getInstance()->createAction(array($this, "serverOptionsCancel"));
    
          foreach ($this->storage->players as $player) {
                $info = ServerOptions::Create($player->login);		
                $info->setTitle('Server Options');
		$info->centerOnScreen();
                $info->setSize(160,100);
                $info->show();                
            }  
        }
    


        
    public function serverOptionsOk($login) {
        print "ok";
        
        $server = $this->storage->server;

        $serverOptions = Array(
            "Name" => $this->serverName->getText(),
            "Comment" => $this->serverComment->getText(),
            "Password" => $this->serverPass->getText(),
            "PasswordForSpectator" => $this->serverSpecPass->getText(),
            "NextCallVoteTimeOut" => $server->currentCallvoteTimeOut,
            "CallVoteRatio" => $server->callVoteRatio,
            "RefereePassword" => $this->refereePass->getText(),
            "IsP2PUpload" => $this->cbAllowp2pUp->getStatus(),
            "IsP2PDownload" => $this->cbAllowp2pDown->getStatus(),
            "AllowMapDownload" => $this->cbAllowMapDl->getStatus(),
            "NextMaxPlayer" => $this->maxPlayers->getText(),
            "NextMaxSpectator" => $this->maxSpec->getText(),
            "RefereeMode" => $this->cbReferee->getStatus()
        );


        $this->connection->setServerOptions($serverOptions);

        ServerOptions::Erase($login);
    }

    public function serverOptionsCancel($login) {
        print "cancel";
        ServerOptions::Erase($login);
    }
}

?>