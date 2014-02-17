<?php

namespace Lolol\BattleBundle\BattleManager;

use Lolol\AppBundle\BasicEnum\BasicEnum as BasicEnum;

abstract class LogType extends BasicEnum {
    const STRONG = 'strong';
    const PRESENTATION = '';
    const CHAMPION = '';
    const ALIVE = 'text-success';
    const KO = 'text-danger';
    const CAN_PLAY = '';
    const TEAM = '';
    const ASK_CHAMPION = '';
    const CHAMPION_PLAYED = '';
    const NO_MORE_CHAMPIONS = '';
    const TRY_DEFAULT_ATTACK = '';
    const DEFAULT_ATTACK = 'text-info';
    const DEFAULT_ATTACK_COOLDOWN = '';
    const INJURY = '';
    const INJURED = 'text-danger';
    const ARMOR_ABSORPTION = 'text-warning';
    const HEALTH = 'text-success';
    const DEFEATED = 'text-danger';
}