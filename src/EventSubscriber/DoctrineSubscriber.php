<?php

// src/EventListener/DatabaseActivitySubscriber.php
namespace App\EventSubscriber;

use App\Entity\Craft;
use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class DoctrineSubscriber implements EventSubscriber
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    // this method can only return the event names; you cannot define a
    // custom method name to execute when each event triggers
    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postRemove,
            Events::postUpdate,
        ];
    }

    // callback methods must be called exactly like the events they listen to;
    // they receive an argument of type LifecycleEventArgs, which gives you access
    // to both the entity object of the event and the entity manager itself
    public function postPersist(LifecycleEventArgs $args): void
    {
        //dd($args);
        $this->logActivity('persist', $args);
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        //dd($args);
        $this->logActivity('remove', $args);
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        //dd($args);
        $this->logActivity('update', $args);
    }

    private function logActivity(string $action, LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        // if this subscriber only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if ($entity instanceof Craft) {
            
            
            
           /* 
            //Créer l'objet history hein
            $craftHistory=new CraftHistory();

            $craftHistory->setCraft($entity->getId());
            $craftHistory->setMessage($entity->getCraft());
            $craftHistory->setModifiedAt($entity->getOldDescription());
            $craftHistory->setModifiedAt($entity->getOldTitle());
            $craftHistory->setModifiedBy($entity->getOldSlot1());
            $craftHistory->setModifiedBy($entity->getOldSlot2());
            $craftHistory->setModifiedBy($entity->getOldSlot3());
            $craftHistory->setModifiedBy($entity->getOldSlot4());
            $craftHistory->setModifiedBy($entity->getOldCost());
            $craftHistory->setModifiedBy($entity->getOldVerified());
            $craftHistory->setModifiedBy($entity->getOldDateAjout());
            $craftHistory->setModifiedAt($entity->getOldUser());
            $craftHistory->setModifiedAt($entity->getEditedBy());

            $this->entityManager->getDoctrine()->getManager();
            $this->entityManager->persist($craftHistory);
            $this->entityManager->flush();
            
            
            
            
            
            
            
            
            dd($entity);
            */
        }

        // ... get the entity information and log it somehow
    }
}