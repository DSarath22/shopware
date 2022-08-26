<?php

declare(strict_types=1);

namespace Ddms\ProductQuestionAnswer\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1657976467QuestionAnswer extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1657976467;
    }

    public function update(Connection $connection): void
    {
        // implement update

        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `ddms_qa_question`(
                `id` BINARY(16) NOT NULL,
                `customer_id`  BINARY(16) DEFAULT NULL,
                `product_id` BINARY(16) NOT NULL,
                `question` VARCHAR(1000) NOT NULL,
                `status` TINYINT(1) DEFAULT 0,
                `view_status` TINYINT(1) DEFAULT 0,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) DEFAULT NULL,
                PRIMARY KEY (`id`),
                CONSTRAINT `fk.ddms_qa_question.customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.ddms_qa_question.product_id` FOREIGN KEY`id` BINARYicode_ci;
        ');

        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `ddms_qa_answer`(
                `id` BINARY(16) NOT NULL,
                `question_id`  BINARY(16) NOT NULL,
                `answer_by` VARCHAR(255) NOT NULL,
                `answer` VARCHAR(1000) NOT NULL,
                `status` TINYINT(1) NOT NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) DEFAULT NULL,
                PRIMARY KEY(`id`),
                CONSTRAINT `fk.ddms_qa_answer.question_id` FOREIGN KEY (`question_id`) REFERENCES `ddms_qa_question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;             
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
