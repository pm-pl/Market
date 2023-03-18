<?php

namespace dhnnz\Market\economy\types;

use dhnnz\Market\economy\Economy;
use dhnnz\Market\Loader;
use onebone\economyapi\EconomyAPI as EconomyAPIPL;
use pocketmine\player\Player;
use pocketmine\Server;

class EconomyAPI extends Economy
{
    /** @var EconomyAPIPL */
    private $economyAPI;

    public function __construct()
    {
        $this->economyAPI = EconomyAPIPL::getInstance();
    }

    public function buy(Player $player, string $seller, int $amount, callable $callable): void
    {
        if ($this->economyAPI->myMoney($player) >= $amount) {
            $seller = Server::getInstance()->getPlayerExact($seller);
            if ($seller instanceof Player) {
                $this->economyAPI->addMoney($seller, $amount);
            }else{
                Loader::getInstance()->historys[$seller][] = array(
                    $player->getName(),
                    $amount
                );
            }
            $this->economyAPI->reduceMoney($player, $amount);
            $callable(Loader::STATUS_SUCCESS);
        } else {
            $callable(Loader::STATUS_ENOUGH);
        }
    }
}