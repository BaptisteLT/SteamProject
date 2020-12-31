<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CraftRepository")
 * @Vich\Uploadable
 */
class Craft
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=90)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cost;

    /**
     * @ORM\Column(type="boolean")
     */
    private $verified;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_ajout;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="crafts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;


    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="crafts", fileNameProperty="nom_image")
     * 
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom_image;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserLikeCraft", mappedBy="Craft", cascade={"remove"})
     */
    private $userLikeCrafts;//cascade remove au dessus supprime les likes également, lorsque que le craft est supprimé.

    /**
     * @ORM\ManyToOne(targetEntity=Items::class, inversedBy="crafts")
     */
    private $Slot1;

    /**
     * @ORM\ManyToOne(targetEntity=Items::class, inversedBy="crafts")
     */
    private $Slot2;

    /**
     * @ORM\ManyToOne(targetEntity=Items::class, inversedBy="crafts")
     */
    private $Slot3;

    /**
     * @ORM\ManyToOne(targetEntity=Items::class, inversedBy="crafts")
     */
    private $Slot4;


    public function __construct()
    {
        $this->updated_at = new \DateTime();
        $this->userLikeCrafts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCost(): ?float
    {
        return $this->cost;
    }

    public function setCost(?float $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getVerified(): ?bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): self
    {
        $this->verified = $verified;

        return $this;
    }

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->date_ajout;
    }

    public function setDateAjout(\DateTimeInterface $date_ajout): self
    {
        $this->date_ajout = $date_ajout;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    

    public function getNomImage(): ?string
    {
        return $this->nom_image;
    }

    public function setNomImage(?string $nom_image): self
    {
        $this->nom_image = $nom_image;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }




    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updated_at = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->nom_image = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->nom_image;
    }


    /**
     * @return Collection|UserLikeCraft[]
     */
    public function getUserLikeCrafts(): Collection
    {
        return $this->userLikeCrafts;
    }

    public function addUserLikeCraft(UserLikeCraft $userLikeCraft): self
    {
        if (!$this->userLikeCrafts->contains($userLikeCraft)) {
            $this->userLikeCrafts[] = $userLikeCraft;
            $userLikeCraft->setCraft($this);
        }

        return $this;
    }

    public function removeUserLikeCraft(UserLikeCraft $userLikeCraft): self
    {
        if ($this->userLikeCrafts->contains($userLikeCraft)) {
            $this->userLikeCrafts->removeElement($userLikeCraft);
            // set the owning side to null (unless already changed)
            if ($userLikeCraft->getCraft() === $this) {
                $userLikeCraft->setCraft(null);
            }
        }

        return $this;
    }

    public function getSlot1(): ?Items
    {
        return $this->Slot1;
    }

    public function setSlot1(?Items $Slot1): self
    {
        $this->Slot1 = $Slot1;

        return $this;
    }

    public function getSlot2(): ?Items
    {
        return $this->Slot2;
    }

    public function setSlot2(?Items $Slot2): self
    {
        $this->Slot2 = $Slot2;

        return $this;
    }

    public function getSlot3(): ?Items
    {
        return $this->Slot3;
    }

    public function setSlot3(?Items $Slot3): self
    {
        $this->Slot3 = $Slot3;

        return $this;
    }

    public function getSlot4(): ?Items
    {
        return $this->Slot4;
    }

    public function setSlot4(?Items $Slot4): self
    {
        $this->Slot4 = $Slot4;

        return $this;
    }



}
