<?php

declare(strict_types=1);

namespace MineManager;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\world\Position;

class Main extends PluginBase {

    public function onEnable(): void {
        $this->saveDefaultConfig();
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {

        if (!$sender instanceof Player) {
            $sender->sendMessage("Use this command in-game.");
            return true;
        }

        if ($command->getName() === "mine") {

            if (count($args) < 1) {
                $sender->sendMessage("§eUsage: /mine (A-Z) or /mine VIP");
                return true;
            }

            $mine = strtolower($args[0]);

            $config = $this->getConfig()->get("mines");

            if (!isset($config[$mine])) {
                $sender->sendMessage("§cMine does not exist.");
                return true;
            }

            $permission = "mine." . $mine;

            if (!$sender->hasPermission($permission)) {
                $sender->sendMessage($config[$mine]["no-permission-message"]);
                return true;
            }

            $worldName = $config[$mine]["world"];
            $world = $this->getServer()->getWorldManager()->getWorldByName($worldName);

            if ($world === null) {
                $sender->sendMessage("§cWorld not loaded.");
                return true;
            }

            $x = (float)$config[$mine]["x"];
            $y = (float)$config[$mine]["y"];
            $z = (float)$config[$mine]["z"];

            $sender->teleport(new Position($x, $y, $z, $world));
            $sender->sendMessage($config[$mine]["success-message"]);

            return true;
        }

        return false;
    }
}
