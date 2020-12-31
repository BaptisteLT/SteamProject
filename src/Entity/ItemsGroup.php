<?php

namespace App\Entity;

use App\Repository\ItemsGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemsGroupRepository::class)
 */
class ItemsGroup
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @ORM\OneToMany(targetEntity=Items::class, mappedBy="itemsGroup")
     */
    private $Items;

    public function __construct()
    {
        $this->Items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Items[]
     */
    public function getItems(): Collection
    {
        return $this->Items;
    }

    public function addItem(Items $item): self
    {
        if (!$this->Items->contains($item)) {
            $this->Items[] = $item;
            $item->setItemsGroup($this);
        }

        return $this;
    }

    public function removeItem(Items $item): self
    {
        if ($this->Items->contains($item)) {
            $this->Items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getItemsGroup() === $this) {
                $item->setItemsGroup(null);
            }
        }

        return $this;
    }
}
