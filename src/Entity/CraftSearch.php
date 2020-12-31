<?php
namespace App\Entity;

class CraftSearch{



    //ENTITEE NON RELIE A LA BASE DE DONNEES ET QUI PERMET DE CREER LE FORMULAIRE POUR LA RECHERCHE DE TICKETS

    /**
     * @var string|null
     */
    private $title;

    /**
     * @var string|null
     */
    private $cost_min;

    /**
     * @var string|null
     */
    private $cost_max;


    /**
     * @var string|null
     */
    private $date_min;

    /**
     * @var string|null
     */
    private $date_max;
    
    /**
     * @var string|null
     */
    private $slot1;
    
    /**
     * @var string|null
     */
    private $slot2;
    
    /**
     * @var string|null
     */
    private $slot3;
    
    /**
     * @var string|null
     */
    private $slot4;
    
    /**
     * @var string|null
     */
    private $itemsGroup;





    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     * @return CraftSearch
     */
    public function setTitle(string $title): CraftSearch
    {
        $this->title = $title;
        return $this;
    }







    /**
     * @return string|null
     */
    public function getcostMin(): ?string
    {
        return $this->cost_min;
    }

    /**
     * @param string|null $cost_min
     * @return CraftSearch
     */
    public function setcostMin(string $cost_min): CraftSearch
    {
        $this->cost_min = $cost_min;
        return $this;
    }

    




    /**
     * @return string|null
     */
    public function getcostMax(): ?string
    {
        return $this->cost_max;
    }

    /**
     * @param string|null $cost_max
     * @return CraftSearch
     */
    public function setcostMax(string $cost_max): CraftSearch
    {
        $this->cost_max = $cost_max;
        return $this;
    }





    /**
     * @return string|null
     */
    public function getDateMin(): ?string
    {
        return $this->date_min;
    }

    /**
     * @param string|null $date_min
     * @return CraftSearch
     */
    public function setDateMin(string $date_min): CraftSearch
    {
        $this->date_min = $date_min;
        return $this;
    }



    
    /**
     * @return string|null
     */
    public function getDateMax(): ?string
    {
        return $this->date_max;
    }

    /**
     * @param string|null $date_max
     * @return CraftSearch
     */
    public function setDateMax(string $date_max): CraftSearch
    {
        $this->date_max = $date_max;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSlot1(): ?string
    {
        return $this->slot1;
    }

    /**
     * @param string|null $slot1
     * @return CraftSearch
     */
    public function setSlot1(string $slot1): CraftSearch
    {
        $this->slot1 = $slot1;
        return $this;
    }




    /**
     * @return string|null
     */
    public function getSlot2(): ?string
    {
        return $this->slot2;
    }

    /**
     * @param string|null $slot2
     * @return CraftSearch
     */
    public function setSlot2(string $slot2): CraftSearch
    {
        $this->slot2 = $slot2;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSlot3(): ?string
    {
        return $this->slot3;
    }

    /**
     * @param string|null $slot3
     * @return CraftSearch
     */
    public function setSlot3(string $slot3): CraftSearch
    {
        $this->slot3 = $slot3;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSlot4(): ?string
    {
        return $this->slot4;
    }

    /**
     * @param string|null $slot4
     * @return CraftSearch
     */
    public function setSlot4(string $slot4): CraftSearch
    {
        $this->slot4 = $slot4;
        return $this;
    }


    /**
     * @return string|null
     */
    public function getItemsGroup(): ?string
    {
        return $this->itemsGroup;
    }

    /**
     * @param string|null $itemGroup
     * @return CraftSearch
     */
    public function setItemsGroup(string $itemsGroup): CraftSearch
    {
        $this->itemsGroup = $itemsGroup;
        return $this;
    }
}