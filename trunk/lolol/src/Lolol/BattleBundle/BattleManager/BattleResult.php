<?php

namespace Lolol\BattleBundle\BattleManager;

use Lolol\AppBundle\BasicEnum\BasicEnum as BasicEnum;

abstract class BattleResult extends BasicEnum {
    const DRAW = 0;
    const VICTORY = 1;
    const DEFEAT = 2;
}