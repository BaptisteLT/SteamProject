<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Knojector\SteamAuthenticationBundle\User\AbstractSteamUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\Role;

/**
 * @author Knojector <dev@knojector.xyz>
 *
 * @ORM\Entity()
 */
class User extends AbstractSteamUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Tchat::class, mappedBy="User")
     */
    private $tchats;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $tchat_ban;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $site_ban;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tchat_ban_counter;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $site_ban_counter;


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function __construct()
    {
        /*Suppresion de role[] dans le constructeur*/
        $this->roles = ['ROLE_USER'];
        $this->tchats = new ArrayCollection();
    }
    
    /**
     * @return array
     */
    public function getRoles(): array /*Méthode reprise du projet a2k*/
    {
        $dateNow = new \DateTime('now'); //Get current date
        $roles = $this->roles;

        if($this->site_ban < $dateNow) //If the date of the ban is over current date (ex: 2022 < 2020), then we don't set the role user by default. So the user don't have access anymore to the site.
        {
            // guarantee every user at least has ROLE_USER if the user is not banned
            $roles[] = 'ROLE_USER';
        }
        else{
            $roles = [];
        }
        
        return array_unique($roles);
    }

    public function setRoles(array $roles): self/*Méthode ajoutée*/
    {
        $this->roles = $roles;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getProfileName();
    }

    /**
     * @return Collection|Tchat[]
     */
    public function getTchats(): Collection
    {
        return $this->tchats;
    }

    public function addTchat(Tchat $tchat): self
    {
        if (!$this->tchats->contains($tchat)) {
            $this->tchats[] = $tchat;
            $tchat->setUser($this);
        }

        return $this;
    }

    public function removeTchat(Tchat $tchat): self
    {
        if ($this->tchats->contains($tchat)) {
            $this->tchats->removeElement($tchat);
            // set the owning side to null (unless already changed)
            if ($tchat->getUser() === $this) {
                $tchat->setUser(null);
            }
        }

        return $this;
    }

    public function getTchatBan(): ?\DateTimeInterface
    {
        return $this->tchat_ban;
    }

    public function setTchatBan(?\DateTimeInterface $tchat_ban): self
    {
        $this->tchat_ban = $tchat_ban;

        return $this;
    }

    public function getSiteBan(): ?\DateTimeInterface
    {
        return $this->site_ban;
    }

    public function setSiteBan(?\DateTimeInterface $site_ban): self
    {
        $this->site_ban = $site_ban;

        return $this;
    }

    public function getTchatBanCounter(): ?int
    {
        return $this->tchat_ban_counter;
    }

    public function setTchatBanCounter(?int $tchat_ban_counter): self
    {
        $this->tchat_ban_counter = $tchat_ban_counter;

        return $this;
    }

    public function getSiteBanCounter(): ?int
    {
        return $this->site_ban_counter;
    }

    public function setSiteBanCounter(?int $site_ban_counter): self
    {
        $this->site_ban_counter = $site_ban_counter;

        return $this;
    }


}