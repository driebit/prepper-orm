<?php

namespace Driebit\Prepper\Orm\FixtureLoader;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\Persistence\ObjectManager;
use Driebit\Prepper\FixtureLoader\AbstractFixtureLoader;

class OrmFixtureLoader extends AbstractFixtureLoader
{
    protected function getExecutor(ObjectManager $objectManager)
    {
        return new ORMExecutor($objectManager);
    }
}
