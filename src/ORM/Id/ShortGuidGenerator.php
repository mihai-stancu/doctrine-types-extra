<?php

/*
 * Copyright (c) 2015 Mihai Stancu <stancu.t.mihai@gmail.com>
 *
 * This source file is subject to the license that is bundled with this source
 * code in the LICENSE.md file.
 */

namespace MS\DoctrineTypes\ORM\Id;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\UuidGenerator;
use Doctrine\ORM\Mapping\Entity;

class ShortGuidGenerator extends UuidGenerator
{
    /**
     * @param EntityManager $manager
     * @param Entity        $entity
     *
     * @return string
     */
    public function generate(EntityManager $manager, $entity)
    {
        $connection = $manager->getConnection();
        $platform = $connection->getDatabasePlatform();

        if ($platform instanceof MySqlPlatform) {
            return $connection->query('SELECT UUID_SHORT()')->fetchColumn(0);
        } else {
            return parent::generate($manager, $entity);
        }
    }
}
