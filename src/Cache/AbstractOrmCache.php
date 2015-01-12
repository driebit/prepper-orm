<?php

namespace Driebit\Prepper\Orm\Cache;

use Doctrine\ORM\EntityManager;
use Driebit\Prepper\Cache\CacheInterface;
use Driebit\Prepper\Cache\Store\StoreInterface;
use Driebit\Prepper\Fixture\FixtureSet;

abstract class AbstractOrmCache implements CacheInterface
{
    protected $entityManager;
    protected $store;
    
    public function __construct(
        EntityManager $entityManager,
        StoreInterface $store
    ) {
        $this->entityManager = $entityManager;
        $this->store = $store;
    }
    
    protected function getCacheKey(FixtureSet $fixtures)
    {
        return $fixtures->getHash() . '-'
            . md5(serialize($this->getMetadata()));
    }
    
    protected function getMetadata()
    {
        return $this->entityManager->getMetadataFactory()->getAllMetadata();
    }
}
