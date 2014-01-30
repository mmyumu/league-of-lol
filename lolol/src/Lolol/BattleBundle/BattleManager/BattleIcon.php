<?php

namespace Lolol\BattleBundle\BattleManager;

use Lolol\AppBundle\BasicEnum\BasicEnum as BasicEnum;

abstract class BattleIcon extends BasicEnum {
    const ATTACKER = 'fa-bolt';
    const DEFENDER = 'fa-shield';
    const DEFAULT_ICON = 'fa-caret-right';
    const CLOCK = 'fa-clock-o';
}