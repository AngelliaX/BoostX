<?php

namespace tungst_boostX;

use pocketmine\plugin\Plugin;
use pocketmine\scheduler\Task;
use pocketmine\event\Listener;

use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerExperienceChangeEvent;
use pocketmine\event\entity\EntityEffectEvent;
//use pocketmine\event\player\PlayerInteractEvent;

use pocketmine\item\Item;
class CheckTask extends Task implements Listener{

    public $effect = 13; //Water_breathing

	public $owner;
	public $name = []; //layer entity
	public $money = [];
	public $exp = [];
	
	public function __construct(Plugin $owner){	
	    $this->owner = $owner;
		$this->eco = $this->owner->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        if (!$this->eco) {
            $this->getLogger()->info("\n\n           §aAdd §eEconomyAPI \n");
            $this->owner->onDisable();
		}	
	} 
	public function onDrink(EntityEffectEvent $e){
		if($e->getEffect()->getId() == $this->effect){
		  if(!in_array($e->getEntity(),$this->name)){	
			array_push($this->name,$e->getEntity());	
		
		  }
	
		}
	}
	public function onJoin(PlayerJoinEvent $e){
		if($e->getPlayer()->hasEffect($this->effect)){
		  if(!in_array($e->getPlayer(),$this->name)){		  
			  array_push($this->name,$e->getPlayer());
		  }
		}
	}
	public function onLeft(PlayerQuitEvent $e){
		if($e->getPlayer()->hasEffect($this->effect)){
		  if(in_array($e->getPlayer(),$this->name)){
			unset($this->name[array_search($e->getPlayer(),$this->name)]);  
		  }
		}
	}
	public function onRun($Tick){
		
		foreach($this->name as $p){
		
			 if($p->hasEffect($this->effect)){
		  	 
				 
			  if(isset($this->money[$p->getName()])){
				
			     if($this->eco->myMoney($p) > $this->money[$p->getName()]){
			        $this->eco->addMoney($p,$this->eco->myMoney($p) - $this->money[$p->getName()]);
					$this->money[$p->getName()] = $this->eco->myMoney($p);					
				 }else{
					$this->money[$p->getName()] = $this->eco->myMoney($p);				
				 }
			  }else{
				  $this->money[$p->getName()] = $this->eco->myMoney($p);			
			  }
			  
			  
			  if(isset($this->exp[$p->getName()])){
				 
			     if($p->getCurrentTotalXp() > $this->exp[$p->getName()]){
			        $p->addXp($p->getCurrentTotalXp() - $this->exp[$p->getName()],true);
					$this->exp[$p->getName()] = $p->getCurrentTotalXp();
					
				 }else{
					 $this->exp[$p->getName()] = $p->getCurrentTotalXp();
					 
				 }
			  }else{
				  $this->exp[$p->getName()] = $p->getCurrentTotalXp();
				  
			  }
			 }else{			 
				 unset($this->name[array_search($p,$this->name)]);
                 unset($this->money[array_search($p->getName(),$this->name)]);
                 unset($this->exp[array_search($p->getName(),$this->exp)]);  				 
			 }  	
		}
	}
}