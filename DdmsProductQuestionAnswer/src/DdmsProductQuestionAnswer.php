<?php declare(strict_types=1);

namespace Ddms\ProductQuestionAnswer;

use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Doctrine\DBAL\Connection;

class DdmsProductQuestionAnswer extends Plugin
{
    public function uninstall(UninstallContext $uninstallContext): void
    {
        parent::uninstall($uninstallContext);
        if ($uninstallContext->keepUserData()) {
            return;
        }
        $connection = $this->container->get(Connection::class);
        $connection->executeQuery('DROP TABLE IF EXISTS `ddms_qa_answer`'); 
        $connection->executeQuery('DROP TABLE IF EXISTS `ddms_qa_question`'); 

    }

    public function install(InstallContext $installContext): void
    {
        
    }
}