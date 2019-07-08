<?php

namespace tungst_boostX;

use pocketmine\plugin\PluginBase;
use pocketmine\Player; 
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Event;
use pocketmine\event\player\PlayerJoinEvent;

use pocketmine\item\Item;
use pocketmine\block\Block;



use tungst_boostX\CheckTask;
use pocketmine\scheduler\TaskScheduler;
use pocketmine\entity\EffectInstance;


use FormAPI_byJoeoe\FormAPI;
class Main extends PluginBase implements Listener {

    public $task;
	public function onEnable(){
		$this->getLogger()->info("BoostX enable");
		
		$this->task = new CheckTask($this);
		$this->getScheduler()->scheduleRepeatingTask($this->task, 10);
		$this->getServer()->getPluginManager()->registerEvents($this->task, $this);
	}
	
	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
		if($sender instanceof Player){	  
		   switch(strtolower($command->getName())){
			case "buyb":
			  $item = Item::get(373,19,1);			
			  $sender->getInventory()->addItem($item);
			break;   			   
		    case "boostinfo":	
			  if(in_array($sender->getName(),$this->task->name)){
               $a = new FormAPI();
		       $form = $a->createSimpleForm(function (Player $player, int $data = null){
			      $result = $data;
			      if($result === null){
			  	    return true;
			      }
			      switch($result){				
					case "0";                      		   
					break;
                    default:                 
                    break;									
				  }
			   });			
			   $form->setTitle("Your boost information:");
			   $form->setContent("Your name: ".$sender->getName()."\n".
			                     "Boost Info: x2 Money and x2 Exp\n".
								 "Time left: look at your effect time\n"						 
			   );
			   $form->addButton("Okay");		
			   $form->sendToPlayer($this->builder);
			   return $form;	  	  
			  }else{
			   $sender->sendMessage("You dont have any boost");
			  }			  
			  break;
			
		   }
		}

	return true;
	}
	
}