<?php

namespace Lolol\BattleBundle\BattleManager;

use Lolol\AppBundle\BasicEnum\BasicEnum as BasicEnum;

abstract class LogTypes extends BasicEnum {
    const STRONG = 'STRONG';
    const PRESENTATION = 'PRESENTATION';
    const CHAMPION = 'CHAMPION';
    const ALIVE = 'ALIVE';
    const KO = 'KO';
    const CAN_PLAY = 'CAN_PLAY';
    const TEAM = 'TEAM';
    const ASK_CHAMPION = 'ASK_CHAMPION';
    const CHAMPION_PLAYED = 'CHAMPION_PLAYED';
    const NO_MORE_CHAMPIONS = 'NO_MORE_CHAMPIONS';
    const TRY_DEFAULT_ATTACK = 'TRY_DEFAULT_ATTACK';
    const DEFAULT_ATTACK = 'DEFAULT_ATTACK';
    const DEFAULT_ATTACK_COOLDOWN = 'DEFAULT_ATTACK_COOLDOWN';
    const INJURY = 'INJURY';
    const INJURED = 'INJURED';
    const ARMOR_ABSORPTION = 'ARMOR_ABSORPTION';
    const HEALTH = 'HEALTH';
}