<?php

declare(strict_types=1);

namespace Cgs\AudioSite\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1661230716cgs_audio_site extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1661230716;
    }

    public function update(Connection $connection): void
    {
        // implement update
        $connection->executeUpdate('
CREATE TABLE IF NOT EXISTs `cgs_audio_site`(
    `id`             BINARY(16)      NOT NULL,
    `customer_id`    BINARY(16)      NOT NULL,
    `status`         TINYINT(1)      DEFAULT 0,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) DEFAULT NULL,
     PRIMARY KEY(`id`),
     CONSTRAINT `fk.cgs_audio_site.customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
