<?php

namespace Lolol\BattleBundle\BattleManager;

use Lolol\AppBundle\BasicEnum\BasicEnum as BasicEnum;

abstract class BattleResult extends BasicEnum {
    const DRAW = 0;
    const WIN = 1;
    const LOOSE = 2;
}