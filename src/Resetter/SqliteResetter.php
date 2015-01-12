<?php

namespace Driebit\Prepper\Orm\Resetter;

class SqliteResetter extends OrmResetter
{
    public function reset()
    {
        parent::reset();

        // Make database file writable by group so that ACL works properly.
        // This is needed for the database file to be writable by the web user
        // (www-data) during integration tests that go through a web server.
        $params = $this->entityManager->getConnection()->getParams();
        
        if (isset($params['path'])) {
            chmod($params['path'], 0664);
        }
    }
}
