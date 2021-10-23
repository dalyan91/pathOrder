<?php

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractManager
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $repository;

    protected $container;


    /**
     * PatientManager constructor.
     * Set the default repository from entity name
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, ContainerInterface $container=null)
    {
        $this->em = $em;
        $this->repository = $this->em->getRepository($this->getEntityName());
        $this->container = $container;

    }

    /**
     * Get default repository or spesific repository
     *
     * @param null $entityName
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     */
    public function getRepository($entityName = null)
    {
        if (! empty($entityName)) {
            return $this->em->getRepository($entityName);
        }

        return $this->repository;

    }

    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Get the default manager entity name
     *
     * @return string
     */
    abstract public function getEntityName();

}