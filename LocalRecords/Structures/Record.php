<?php
namespace ManiaLivePlugins\eXpansion\LocalRecords\Structures;

class Record extends \DedicatedApi\Structures\AbstractStructure {
    
    public $login;
    public $time;
    public $place = -1;
     
     
    public function __construct($login, $time) {
        $this->login = $login;
        $this->time = $time;
    }
    
}
?>
