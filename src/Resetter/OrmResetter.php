<?php

namespace Driebit\Prepper\Orm\Resetter;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Driebit\Prepper\Resetter\ResetterInterface;

class OrmResetter implements ResetterInterface
{
    protected $entityManager;
    
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function reset()
    {
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropDatabase();
        $schemaTool->createSchema(
            $this->entityManager->getMetadataFactory()->getAllMetadata()
        );
    }
}
