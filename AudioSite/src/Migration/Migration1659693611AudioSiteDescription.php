<?php

declare(strict_types=1);

namespace AudioSite\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;
// use Shopware\Core\Framework\Plugin\Context\UpdateContext;

class Migration1659693611AudioSiteDescription extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1659693611;
    }

    public function update(Connection $connection): void
    {
        // implement update
        $query = <<<SQL
        CREATE TABLE IF NOT EXISTS `audio_site` (
            `id`                INT             NOT NULL,
            `name`              VARCHAR(255)    NOT NULL,
            PRIMARY KEY (id)
        )
            ENGINE = InnoDB
            DEFAULT CHARSET = utf8mb4
            COLLATE = utf8mb4_unicode_ci;
        SQL;

        $connection->executeStatement($query);
        // $updateContext->setAutoMigrate(false); // disable auto migration execution

        // $migrationCollection = $updateContext->getMigrationCollection();

        // // execute all DESTRUCTIVE migrations until and including 2019-11-01T00:00:00+00:00
        // $migrationCollection->migrateDestructiveInPlace(1572566400);

        // // execute all UPDATE migrations until and including 2019-12-12T09:30:51+00:00
        // $migrationCollection->migrateInPlace(1576143014);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
