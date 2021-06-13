<?php

namespace AppBundle\Helper\Traits;

trait FixturesTrait
{
    /**
     * @param $manager
     * @param $entity
     */
    protected function enforceEntityId($manager, $entity)
    {
        // Enforce specified record ID
        $metadata = $manager->getClassMetaData(get_class($entity));
        $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
    }

}