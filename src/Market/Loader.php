<?php

namespace Market;

use Market\commands\MarketCommand;
use Market\forms\FormManager;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Loader extends PluginBase
{

    public array $markets = [];
    public function onEnable(): void
    {
        $this->markets = (new Config($this->getDataFolder() . "markets.json", Config::JSON, []))->getAll();
        $this->getLogger()->notice("Loaded " . count($this->markets) . " listings");

        $this->getServer()->getCommandMap()->register("Market", new MarketCommand($this), "market");
    }

    public function onDisable(): void
    {
        ($config = new Config($this->getDataFolder() . "markets.json", Config::JSON, []))->setAll($this->markets);
        $config->save();
    }

    public function registerMarket(Player $seller, array $data)
    {
        array_push($this->markets, $data);
        return;
    }

    public function getFormManager(): FormManager
    {
        return new FormManager($this);
    }
}