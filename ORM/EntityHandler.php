<?php

namespace Draw\DoctrineExtra\ORM;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EntityHandler
{
    public function __construct(private ManagerRegistry $managerRegistry)
    {
    }

    public function getManagerForClass(string $class): ?EntityManagerInterface
    {
        $manager = $this->managerRegistry->getManagerForClass($class);

        \assert($manager instanceof EntityManagerInterface || null === $manager);

        return $manager;
    }

    public function getRepository(string $class): EntityRepository
    {
        $repository = $this->managerRegistry->getRepository($class);

        \assert($repository instanceof EntityRepository);

        return $repository;
    }

    public function persist(object $object): void
    {
        $manager = $this->getManagerForClass($object::class)
            ?? throw new \InvalidArgumentException(\sprintf('No entity manager found for class [%s].', $object::class));

        $manager->persist($object);
    }

    public function flush(?string $class = null): void
    {
        $manager = $class
            ? $this->getManagerForClass($class)
                ?? throw new \InvalidArgumentException(\sprintf('No entity manager found for class [%s].', $class))
            : $this->managerRegistry->getManager();

        $manager->flush();
    }

    public function find(string $class, $id)
    {
        return $this->getRepository($class)->find($id);
    }

    public function findAll(string $class): array
    {
        return $this->getRepository($class)->findAll();
    }

    public function findBy(string $class, array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        return $this->getRepository($class)->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function findOneBy(string $class, array $criteria)
    {
        return $this->getRepository($class)->findOneBy($criteria);
    }
}
