<?php

namespace ManiaLivePlugins\eXpansion\Adm\Gui\Windows;

use \ManiaLive\Gui\Controls\Pager;
use \ManiaLivePlugins\eXpansion\Gui\Elements\Button as myButton;
use ManiaLivePlugins\eXpansion\Gui\Elements\Inputbox;
use \ManiaLivePlugins\eXpansion\Gui\Elements\Checkbox;
use \ManiaLivePlugins\eXpansion\Gui\Elements\Ratiobutton;
use ManiaLive\Gui\ActionHandler;
use \DedicatedApi\Structures\GameInfos;

class GameOptions extends \ManiaLivePlugins\eXpansion\Gui\Windows\Window {

    private $frameCb;
    private $frameInputbox, $frameLadder;
    private $buttonOK, $buttonCancel;
    private $connection;
    private $actionOK, $actionCancel;

    function onConstruct() {
        parent::onConstruct();
        $config = \ManiaLive\DedicatedApi\Config::getInstance();
        $this->connection = \DedicatedApi\Connection::factory($config->host, $config->port);
        $this->storage = \ManiaLive\Data\Storage::getInstance();

        $this->actionOK = ActionHandler::getInstance()->createAction(array($this, "Ok"));
        $this->actionCancel = ActionHandler::getInstance()->createAction(array($this, "Cancel"));
        $this->actionTA = ActionHandler::getInstance()->createAction(array($this, "setGamemode"), GameInfos::GAMEMODE_TIMEATTACK);
        $this->actionRounds = ActionHandler::getInstance()->createAction(array($this, "setGamemode"), GameInfos::GAMEMODE_ROUNDS);
        $this->actionLaps = ActionHandler::getInstance()->createAction(array($this, "setGamemode"), GameInfos::GAMEMODE_LAPS);
        $this->actionCup = ActionHandler::getInstance()->createAction(array($this, "setGamemode"), GameInfos::GAMEMODE_CUP);
        $this->actionTeam = ActionHandler::getInstance()->createAction(array($this, "setGamemode"), GameInfos::GAMEMODE_TEAM);

        $this->setTitle('Game Options');
        $this->genGameModes();
    }

    // Generate all inputboxes
    private function genGameModes() {
        
        $this->frameGameMode = new \ManiaLive\Gui\Controls\Frame();
        $this->frameGameMode->setAlign("left", "top");
        $this->frameGameMode->setLayout(new \ManiaLib\Gui\Layouts\Line());
        $this->frameGameMode->setSize(100, 11);

        $nextGameInfo = $this->connection->getNextGameInfo();

        $button = new myButton();
        $button->setText("TimeAttack");
        $button->setValue(GameInfos::GAMEMODE_TIMEATTACK);
        $button->setAction($this->actionTA);

        if ($nextGameInfo->gameMode == GameInfos::GAMEMODE_TIMEATTACK)
            $button->setActive();
        $this->frameGameMode->addComponent($button);

        $button = new myButton();
        $button->setText("Rounds");
        $button->setAction($this->actionRounds);
        $button->setValue(GameInfos::GAMEMODE_ROUNDS);
        if ($nextGameInfo->gameMode == GameInfos::GAMEMODE_ROUNDS)
            $button->setActive();
        $this->frameGameMode->addComponent($button);

        $button = new myButton();
        $button->setText("Cup");
        $button->setAction($this->actionCup);
        $button->setValue(GameInfos::GAMEMODE_CUP);
        if ($nextGameInfo->gameMode == GameInfos::GAMEMODE_CUP)
            $button->setActive();
        $this->frameGameMode->addComponent($button);

        $button = new myButton();
        $button->setText("Laps");
        $button->setAction($this->actionLaps);
        $button->setValue(GameInfos::GAMEMODE_LAPS);
        if ($nextGameInfo->gameMode == GameInfos::GAMEMODE_LAPS)
            $button->setActive();
        $this->frameGameMode->addComponent($button);

        $button = new myButton();
        $button->setText("Team");
        $button->setAction($this->actionTeam);
        $button->setValue(GameInfos::GAMEMODE_TEAM);
        if ($nextGameInfo->gameMode == GameInfos::GAMEMODE_TEAM)
            $button->setActive();
        $this->frameGameMode->addComponent($button);      
        // end of players
        $this->mainFrame->addComponent($this->frameGameMode);
    }

    function onDraw() {
        parent::onDraw();
    }

    function destroy() {
        parent::destroy();
    }

    function onResize($oldX, $oldY) {
        parent::onResize($oldX, $oldY);        
        $this->frameGameMode->setPosition(4,-10);
        
    }

    function setGameMode($login, $gameMode) {
        try {            
            switch ($gameMode) {
                case GameInfos::GAMEMODE_TIMEATTACK:
                    $mode = "Time Attack";
                    break;
                case GameInfos::GAMEMODE_CUP:
                    $mode = "Cup";
                    break;
                case GameInfos::GAMEMODE_LAPS:
                    $mode = "Laps";
                    break;
                case GameInfos::GAMEMODE_ROUNDS:
                    $mode = "Rounds";
                    break;
                case GameInfos::GAMEMODE_TEAM:
                    $mode = "Team";
                    break;                
                default:
                    $mode = $gameMode;
            }
            var_dump($gameMode);
            $this->connection->setGameMode($gameMode);
            $this->connection->chatSendServerMessage('$fff Next Gamemode is now set to $o'. $mode);
        } catch (\Exception $e) {
            $this->connection->chatSendServerMessage('$f00$oError! $o$fff'. $e->getMessage(), $this->getRecipient());
        }
    }

    public function Ok($login) {

        $this->hide();
    }

    public function Cancel($login) {
        $this->hide();
    }

}
