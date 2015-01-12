<?php

namespace Driebit\Prepper\Orm\Cache;

use Driebit\DbBackup\SqliteCopyBackup;
use Driebit\Prepper\Exception\BackupNotFoundException;
use Driebit\Prepper\Exception\BackupOutOfDateException;
use Driebit\Prepper\Fixture\FixtureSet;

class SqliteCopyCache extends AbstractOrmCache
{
    public function store(FixtureSet $fixtures)
    {
        $key = $this->getCacheKey($fixtures);
        $filename = $this->store->getPath($key);
        $this->getSqliteBackup()->backup($this->getDatabasePath(), $filename);
    }
    
    public function restore(FixtureSet $fixtures)
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
    
    protected function getCacheKey(FixtureSet $fixtures)
    {
        return parent::getCacheKey($fixtures) . '.db';
    }

    private function getSqliteBackup()
    {
        return new SqliteCopyBackup();
    }
    
    private function getDatabasePath()
    {
        $params = $this->entityManager->getConnection()->getParams();

        return $params['path'];
    }
}
