<?php

namespace App\Entity;

use App\Repository\ItemsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemsRepository::class)
 */
class Items
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $icon_url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $stickerbool;

    /**
     * @ORM\OneToMany(targetEntity=Craft::class, mappedBy="Slot1")
     */
    private $crafts;

    /**
     * @ORM\ManyToOne(targetEntity=ItemsGroup::class, inversedBy="Items")
     */
    private $itemsGroup;

    public function __construct()
    {
        $this->crafts = new ArrayCollection();
    }



    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIconUrl(): ?string
    {
        return $this->icon_url;
    }

    public function setIconUrl(?string $icon_url): self
    {
        $this->icon_url = $icon_url;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }


    public function getStickerBool(): ?bool
    {
        return $this->stickerbool;
    }

    public function setStickerBool(?bool $stickerbool): self
    {
        $this->stickerbool = $stickerbool;

        return $this;
    }

    /**
     * @return Collection|Craft[]
     */
    public function getCrafts(): Collection
    {
        return $this->crafts;
    }

    public function addCraft(Craft $craft): self
    {
        if (!$this->crafts->contains($craft)) {
            $this->crafts[] = $craft;
            $craft->setSlot1($this);
        }

        return $this;
    }

    public function removeCraft(Craft $craft): self
    {
        if ($this->crafts->contains($craft)) {
            $this->crafts->removeElement($craft);
            // set the owning side to null (unless already changed)
            if ($craft->getSlot1() === $this) {
                $craft->setSlot1(null);
            }
        }

        return $this;
    }

    public function getItemsGroup(): ?ItemsGroup
    {
        return $this->itemsGroup;
    }

    public function setItemsGroup(?ItemsGroup $itemsGroup): self
    {
        $this->itemsGroup = $itemsGroup;

        return $this;
    }

    public function __toString()
    {
        return $this->Name;
    }
}
