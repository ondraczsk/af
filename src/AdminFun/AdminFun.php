<?php

/*
 *Plugin by ---> H    H   HHHHHH  H    H  HHHHHH  H    H  H     H
 *               H    H   H    H   H  H     HH    HH   H  HH H HH
 *               HHHHHH   H    H    HH      HH    HHH  H  H H H H
 *               H    H   H    H    HH      HH    H  H H  H     H
 *               H    H   HHHHHH    HH    HHHHHH  H   HH  H     H
 * Do not copy the code!
 * (C) CyberCube
 * Website: http://www.cybercube-hk.com
 * Github: http://github.cybercube-hk.com
*/

namespace AdminFun;

use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender as Issuer;
use pocketmine\command\Command;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\level\Explosion;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\inventory\InventoryOpenEvent;

class AdminFun extends PluginBase implements Listener{
	public function onEnable(){
		$this->getLogger()->info(" Loading...");
		$this->frozen = array();
		$this->invlock = array();
	    $this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->info(TextFormat::GREEN." Loaded!");
	}
	public function onDisable(){
	}
	public function onCommand(Issuer $issuer,Command $cmd,$label,array $args){
		$permission = "Sorry, you have no permission for this!";
		if($issuer instanceof Player){
		switch($cmd->getName()){
			case"announce":
				if(count($args) < 1){
					if($issuer->hasPermission("adminfun.announce")){
						$issuer->sendMessage("Usage: /announce <message>");
						return true;
					}else{
						$issuer->sendMessage(TextFormat::RED."$permission");
						return true;
					}
				}else{
					$this->getServer()->broadcastMessage("$args");
					return true;
				}
			break;
			case"bgod":
				if(count($args) < 1){
					if($issuer->hasPermission("adminfun.bgod")){
						$issuer->sendMessage("Usage: /bgod <message>");
						return true;
					}else{
						$issuer->sendMessage(TextFormat::RED."$permission");
						return true;
					}
				}else{
					$this->getServer()->broadcastMessage("[GOD] $args");
					return true;
				}
			break;
			case"bherobrine":
				if(count($args) < 1){
					if($issuer->hasPermission("adminfun.bherobrine")){
						$issuer->sendMessage("Usage: /bherobrine <message>");
						return true;
					}else{
						$issuer->sendMessage(TextFormat::RED."$permission");
						return true;
					}
				}else{
					$this->getServer()->broadcastMessage("> Herobrine > $args");
					return true;
				}
			break;
			case"console":
			    if(count($args) < 1){
					if($issuer->hasPermission("adminfun.console")){
						$issuer->sendMessage("Usage: /console <message>");
						return true;
					}else{
						$issuer->sendMessage(TextFormat::RED."$permission");
						return true;
					}
				}else{
					$this->getServer()->broadcastMessage("[CONSOLE] $args");
					return true;
				}
			break;
			case"explode":
			    if(count($args) == 0){
			    	if($issuer->hasPermission("adminfun.fakejoin")){
						$issuer->sendMessage("Usage: /explode <player> <size>");
						return true;
					}else{
						$issuer->sendMessage(TextFormat::RED."$permission");
						return true;
					}
			    }
				if(count($args) == 1){
					$target = $this->getServer()->getPlayer($args[0]);
					if($issuer->hasPermission("adminfun.explode")){
					    $issuer->sendMessage("Usage: /explode <player> <size>");
					    return true;
					}else{
						$issuer->sendMessage(TextFormat::RED."$permission");
						return true;
					}
				}
				if(count($args) == 2){
					$size = $args[1];
					if($issuer->hasPermission("adminfun.explode")){
					    if($target != null){
					    	if(is_numeric($size)){
					    		$explode = Explosion($target->getPosition(), $size);
								$explode->explode();
								$issuer->sendMessage("You exploded $args[0]!");
					    	}else{
					    		$issuer->sendMessage("Size must be a number!");
								return true;
					    	}
					    }else{
					    	$issuer->sendMessage("Invalid player name!");
							return true;
					    }
					}else{
						$issuer->sendMessage(TextFormat::RED."$permission");
						return true;
					}
				}
			break;
            case"fakejoin":
			    if(count($args) == 0){
			    	if($issuer->hasPermission("adminfun.fakejoin")){
						$issuer->sendMessage("Usage: /fakejoin <player>");
						return true;
					}else{
						$issuer->sendMessage(TextFormat::RED."$permission");
						return true;
					}
			    }
				if(count($args) == 1){
					$target = $args[0];
					if($issuer->hasPermission("adminfun.fakejoin")){
					    $this->getServer()->broadcastMessage("$args[0] joined the game");
					}else{
						$issuer->sendMessage(TextFormat::RED."$permission");
					}
				}
				return true;
			break;
			case"fakeop":
				if(count($args) == 0){
					if($issuer->hasPermission("adminfun.fakeop")){
						$issuer->sendMessage("Usage: /fakeop <player>");
						return true;
					}else{
						$issuer->sendMessage(TextFormat::RED."$permission");
						return true;
					}
				}
				if(count($args) == 1){
					$target = $this->getServer()->getPlayer($args[0]);
					if($issuer->hasPermission("adminfun.fakeop")){
					    if($target != null){
					        if(!$target->isOp()){
						        $target->sendMessage("You are now op!");
						        $issuer->sendMessage("You fake opped $args[0]!");
					        }else{
						        $issuer->sendMessage("You can't fake op an opped player!");
					        }
						}else{
							$issuer->sendMessage("Invalid player name!");
						}
					}else{
						$issuer->sendMessage(TextFormat::RED."$permission");
					}
					return true;
				}
			break; 
            case"fakequit":
				if(count($args) == 0){
					if($issuer->hasPermission("adminfun.fakequit")){
						$issuer->sendMessage("Usage: /fakequit <player>");
						return true;
					}else{
						$issuer->sendMessage(TextFormat::RED."$permission");
						return true;
					}
				}
				if(count($args) == 1){
					$target = $this->getServer()->getPlayer($args[0]);
					if($issuer->hasPermission("adminfun.fakequit")){
					    if($target != null){
					        $this->getServer()->broadcastMessage("$args[0] has left the game");
					    }else{
						    $issuer->sendMessage("Invalid player name!");
						}
					}
					return true;
				}
			break;
			case"freeze":
				if(count($args) == 0){
                    if($issuer->hasPermission("adminfun.freeze")){
                    	$issuer->sendMessage("Usage: /freeze <player>");
						return true;
                    }else{
                    	$issuer->sendMessage(TextFormat::RED."$permission");
						return true;
                    }
                }
				if(count($args) == 1){
					$target = $this->getServer()->getPlayer();
					$p = $target->getName();
				    if($issuer->hasPermission("adminfun.freeze")){
						if($target != null){
							$this->frozen[$p];
							$issuer->sendMessage("You freezed $args[0]!");
							return true;
						}else{
							$issuer->sendMessage("Invalid player name!");
						    return true;
						}
					}else{
						$issuer->sendMessage(TextFormat::RED."$permission");
						return true;
					}
				}
			break;
			case"hide":
			    if(count($args) == 0){
					if($issuer->hasPermission("adminfun.hide")){
						foreach($issuer->getLevel()->getPlayers() as $p){
                            $p->hidePlayer($issuer);
                        }
						$issuer->sendMessage("You are hidden!");
						return true;
					}else{
						$issuer->sendMessage(TextFormat::RED."$permission");
						return true;
					}
				}
				if(count($args) == 1){
					$target = $this->getServer()->getPlayer($args[0]);
					if($issuer->hasPermission("adminfun.hide")){
					    if($target != null){
					        foreach($target->getLevel()->getPlayers() as $p){
                                $p->hidePlayer($target);
                            }
							$issuer->sendMessage("You hide $args[0]");
						    $target->sendMessage("You have been hidden!");
					    }else{
						    $issuer->sendMessage("Invalid player name!");
						}
					}
					return true;
				}
			break;
			case"unhide":
			    if(count($args) == 0){
					if($issuer->hasPermission("adminfun.unhide")){
						foreach($issuer->getLevel()->getPlayers() as $p){
                            $p->showPlayer($issuer);
                        }
						$issuer->sendMessage("You are now visible!");
						return true;
					}else{
						$issuer->sendMessage(TextFormat::RED."$permission");
						return true;
					}
				}
				if(count($args) == 1){
					$target = $this->getServer()->getPlayer($args[0]);
					if($issuer->hasPermission("adminfun.unhide")){
					    if($target != null){
					        foreach($target->getLevel()->getPlayers() as $p){
                                $p->showPlayer($target);
                            }
							$issuer->sendMessage("You show $args[0]!");
						    $target->sendMessage("You are now visible!");
					    }else{
						    $issuer->sendMessage("Invalid player name!");
						}
					}
					return true;
				}
			break;
			case"invlock":
				if(count($args) == 0){
                    if($issuer->hasPermission("adminfun.invlock")){
                    	$issuer->sendMessage("Usage: /invlock <player>");
						return true;
                    }else{
                    	$issuer->sendMessage(TextFormat::RED."$permission");
						return true;
                    }
                }
				if(count($args) == 1){
					$target = $this->getServer()->getPlayer();
					$p = $target->getName();
				    if($issuer->hasPermission("adminfun.invlock")){
						if($target != null){
							$this->invlock[$p];
							$issuer->sendMessage("You locked $args[0]'s inventory!");
							return true;
						}else{
							$issuer->sendMessage("Invalid player name!");
						    return true;
						}
					}else{
						$issuer->sendMessage(TextFormat::RED."$permission");
						return true;
					}
				}
			break;
			case"playerchat":
				if(count($args) == 0){
					if($issuer->hasPermission("adminfun.playerchat")){
						$issuer->sendMessage("Usage: /playerchat <player> <message>");
						return true;
					}else{
						$issuer->sendMessage(TextFormat::RED."$permission");
						return true;
					}
				}
				if(count($args) == 1){
					$target = $args[0];
					if($issuer->hasPermission("adminfun.playerchat")){
					    $issuer->sendMessage("Usage: /playerchat <player> <message>");
					    return true;
					}else{
						$issuer->sendMessage(TextFormat::RED."$permission");
						return true;
					}
				}
				if(count($args) == 2){
					$msg = $args[1];
					if($issuer->hasPermission("adminfun.playerchat")){
					    $this->getServer()->broadcastMessage("<$args[0]> $args[1]");
					}else{
						$issuer->sendMessage(TextFormat::RED."$permission");
						return true;
					}
				}
			break;
			case"void":
			    if(count($args) == 0){
					if($issuer->hasPermission("adminfun.void")){
						$issuer->sendMessage("Usage: /void <player>");
						return true;
					}else{
						$issuer->sendMessage(TextFormat::RED."$permission");
						return true;
					}
				}
				if(count($args) == 1){
					$target = $this->getServer()->getPlayer($args[0]);
                    $pos = new Position($x=76, $y=0, $z=152, $target->getLevel()); //$x and $z don't cause issues, I get a random number.
					if($target != null){
					    if($issuer->hasPermission("adminfun.void")){
					        $target->teleport(new Vector3($x,$y,$z));
					        return true;
					    }else{
						    $issuer->sendMessage(TextFormat::RED."$permission");
						    return true;
					    }
				    }else{
				    	$issuer->sendMessage("Invalid player name!");
				    }
				}
			break;
        }
	}
}
	public function onPlayerJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		if($this->frozen[$player->getName()]){
			!$this->frozen[$player->getName()];
		}
		if($this->invlovk[$player->getName()]){
			!$this->invlock[$player->getName()];
		}
	}
	public function onPlayerMove(PlayerMoveEvent $event){
		$player = $event->getPlayer();
		if($this->frozen[$player->getName()]){
			$player->sendMessage("You have been frozen!\nRe-Join server to escape from freeze!");
			$event->setCancelled(true);
		}
	}
	public function onInventoryOpen(InventoryOpenEvent $event){
		$player = $event->getPlayer();
		if($this->invlock[$player->getName()]){
			$player->sendMessage("Your inventory has been locked!\nRe-Join server to escape from inventory lock!");
			$event->setCancelled(true);
		}
	}
}
?>
