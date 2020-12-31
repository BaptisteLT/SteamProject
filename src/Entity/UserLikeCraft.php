<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserLikeCraftRepository")
 */
class UserLikeCraft
{
    /**
     * @ORM\Column(type="integer")
     */
    private $niveau_du_like;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userLikeCrafts")
     */
    private $user;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Craft", inversedBy="userLikeCrafts")
     */
    private $Craft;


    public function getNiveauDuLike(): ?int
    {
        return $this->niveau_du_like;
    }

    public function setNiveauDuLike(int $niveau_du_like): self
    {
        $this->niveau_du_like = $niveau_du_like;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCraft(): ?Craft
    {
        return $this->Craft;
    }

    public function setCraft(?Craft $Craft): self
    {
        $this->Craft = $Craft;

        return $this;
    }
}
