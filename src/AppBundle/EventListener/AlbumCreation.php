<?php
namespace AppBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Album;

class AlbumCreation
{
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // Comprobar si estamos guardando un album
        if (!$entity instanceof Album) {
            return;
        }else{
            $album = $entity;
        }
        
        //Guardamos en BBDD
        $entityManager = $args->getEntityManager();
        $album->setToken(uniqid("", true));
        $entityManager->persist($album);
        $entityManager->flush();
    }
}