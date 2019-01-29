<?php

namespace App\Repository;

use Doctrine\ORM\EntityManager;

use Doctrine\Common\Collections\Collection;

class AbstractRepository
{
    /** @var EntityManager */
    protected $em;

    /**
     * @param object $entity
     */
    protected function saveEntity($entity): void
    {
        if (!$entity->getId()) {
            $this->em->persist($entity);
        }
        $this->em->flush($entity);
    }

    /**
     * @param object $entity
     */
    protected function deleteEntity($entity): void
    {
        $this->em->remove($entity);
        $this->em->flush($entity);
    }

    protected function saveRelatedEntities(iterable $relatedEntities): void
    {
        foreach ($relatedEntities as $entity) {
            if ($entity instanceof Collection) {
                $this->saveRelatedEntities($entity);
            } else {
                if (!$entity->getId()) {
                    $this->em->persist($entity);
                }
                if ($this->em->getUnitOfWork()->isEntityScheduled($entity)) {
                    $this->em->flush($entity);
                }
            }
        }
    }
}
