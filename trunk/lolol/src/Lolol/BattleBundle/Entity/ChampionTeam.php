<?php

namespace Lolol\BattleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ChampionTeam
 */
class ChampionTeam
{
    /**
     * @var \Lolol\TeamBundle\Entity\Team
     */
    private $attackerTeam;

    /**
     * @var \Lolol\TeamBundle\Entity\Team
     */
    private $opponentTeam;


    /**
     * Set attackerTeam
     *
     * @param \Lolol\TeamBundle\Entity\Team $attackerTeam
     * @return ChampionTeam
     */
    public function setAttackerTeam(\Lolol\TeamBundle\Entity\Team $attackerTeam)
    {
        $this->attackerTeam = $attackerTeam;

        return $this;
    }

    /**
     * Get attackerTeam
     *
     * @return \Lolol\TeamBundle\Entity\Team 
     */
    public function getAttackerTeam()
    {
        return $this->attackerTeam;
    }

    /**
     * Set opponentTeam
     *
     * @param \Lolol\TeamBundle\Entity\Team $opponentTeam
     * @return ChampionTeam
     */
    public function setOpponentTeam(\Lolol\TeamBundle\Entity\Team $opponentTeam)
    {
        $this->opponentTeam = $opponentTeam;

        return $this;
    }

    /**
     * Get opponentTeam
     *
     * @return \Lolol\TeamBundle\Entity\Team 
     */
    public function getOpponentTeam()
    {
        return $this->opponentTeam;
    }
}
