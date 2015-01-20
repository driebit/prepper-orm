<?php

namespace Driebit\Prepper\Orm\Cache;

use Doctrine\ORM\EntityManager;
use Driebit\DbBackup\SqliteCopyBackup;
use Driebit\Prepper\Cache\AbstractDoctrineCache;
use Driebit\Prepper\Cache\Store\StoreInterface;
use Driebit\Prepper\Exception\BackupNotFoundException;
use Driebit\Prepper\Exception\BackupOutOfDateException;
use Driebit\Prepper\Fixture\FixtureSetInterface;

class SqliteCopyCache extends AbstractDoctrineCache
{
    public function __construct(
        EntityManager $objectManager,
        StoreInterface $store
    ) {
        parent::__construct($objectManager, $store);
    }

    public function store(FixtureSetInterface $fixtures)
    {
        $key = $this->getCacheKey($fixtures);
        $filename = $this->store->getPath($key);
        $this->getSqliteBackup()->backup($this->getDatabasePath(), $filename);
    }

    public function restore(FixtureSetInterface $fixtures)
    {
        $key = $this->getCacheKey($fixtures);
        if (!$this->store->has($key)) {
            throw new BackupNotFoundException($key);
        }

        $backup = $this->store->get($key);
        if ($backup->getCreated() < $fixtures->getLastModified()) {
            throw new BackupOutOfDateException($key);
        }

        $this->getSqliteBackup()->restore(
            $this->getDatabasePath(),
            $backup->getFilename()
        );
    }

    protected function getCacheKey(FixtureSetInterface $fixtures)
    {
        return parent::getCacheKey($fixtures) . '.db';
    }

    private function getSqliteBackup()
    {
        return new SqliteCopyBackup();
    }

    private function getDatabasePath()
    {
        $params = $this->objectManager->getConnection()->getParams();

        return $params['path'];
    }
}
