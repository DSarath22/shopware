<?php declare(strict_types=1);

namespace Ddms\ProductQuestionAnswer\Migration;

use DateTime;
use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

class Migration1658227045QuestionAnswerMail extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1658227045;
    }

    public function update(Connection $connection): void
    {
        // implement update
        [$questionMailTemplateTypeId, $answerMailTemplateTypeId] = $this->createMailTemplateType($connection);

        $this->createMailTemplate($connection, $questionMailTemplateTypeId, $answerMailTemplateTypeId);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }

    private function createMailTemplateType(Connection $connection)
    {
        $questionMailTemplateTypeId = Uuid::randomHex();
        $answerMailTemplateTypeId = Uuid::randomHex();
        $enGbLangId = $this->getLanguageIdByLocale($connection, 'en-GB');
        $deDeLangId = $this->getLanguageIdByLocale($connection, 'de-DE');

        $connection->executeStatement("
            INSERT IGNORE INTO `mail_template_type`
                (id, technical_name, available_entities, created_at)
            VALUES
                (:id, :technicalName, :availableEntities, :createdAt)
        ",[
            'id' => Uuid::fromHexToBytes($questionMailTemplateTypeId),
            'technicalName' => 'question_mail',
            'availableEntities' => json_encode(['ddms_qa_question' => 'ddms_qa_question']),
            'createdAt' => (new DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);

        $connection->executeStatement("
            INSERT IGNORE INTO `mail_template_type`
                (id, technical_name, available_entities, created_at)
            VALUES
                (:id, :technicalName, :availableEntities, :createdAt)
        ",[
            'id' => Uuid::fromHexToBytes($answerMailTemplateTypeId),
            'technicalName' => 'answer_mail',
            'availableEntities' => json_encode(['ddms_qa_answer' => 'ddms_qa_answer']),
            'createdAt' => (new DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);

        if (!empty($enGbLangId)) {
            $connection->executeStatement("
            INSERT IGNORE INTO `mail_template_type_translation`
                (mail_template_type_id, language_id, name, created_at)
            VALUES
                (:mailTemplateTypeId, :languageId, :name, :createdAt)
            ",[
                'mailTemplateTypeId' => Uuid::fromHexToBytes($questionMailTemplateTypeId),
                'languageId' => $enGbLangId,
                'name' => 'Questions E-mail',
                'createdAt' => (new DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
            ]);

            $connection->executeStatement("
            INSERT IGNORE INTO `mail_template_type_translation`
                (mail_template_type_id, language_id, name, created_at)
            VALUES
                (:mailTemplateTypeId, :languageId, :name, :createdAt)
            ",[
                'mailTemplateTypeId' => Uuid::fromHexToBytes($answerMailTemplateTypeId),
                'languageId' => $enGbLangId,
                'name' => 'Answers E-mail',
                'createdAt' => (new DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
            ]);
        }

        if (!empty($deDeLangId)) {
            $connection->executeStatement("
            INSERT IGNORE INTO `mail_template_type_translation`
                (mail_template_type_id, language_id, name, created_at)
            VALUES
                (:mailTemplateTypeId, :languageId, :name, :createdAt)
            ",[
                'mailTemplateTypeId' => Uuid::fromHexToBytes($questionMailTemplateTypeId),
                'languageId' => $deDeLangId,
                'name' => 'Fragen per E-Mail',
                'createdAt' => (new DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
            ]);

            $connection->executeStatement("
            INSERT IGNORE INTO `mail_template_type_translation`
                (mail_template_type_id, language_id, name, created_at)
            VALUES
                (:mailTemplateTypeId, :languageId, :name, :createdAt)
            ",[
                'mailTemplateTypeId' => Uuid::fromHexToBytes($answerMailTemplateTypeId),
                'languageId' => $deDeLangId,
                'name' => 'Antworten per E-Mail',
                'createdAt' => (new DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
            ]);
        }

        return [$questionMailTemplateTypeId, $answerMailTemplateTypeId];
    }

    private function getLanguageIdByLocale(Connection $connection, string $locale): ?string
    {
        $sql = <<<SQL
        SELECT `language`.`id`
        FROM `language`
        INNER JOIN `locale` ON `locale`.`id` = `language`.`locale_id`
        WHERE `locale`.`code` = :code
        SQL;

        $languageId = $connection->executeQuery($sql, ['code' => $locale])->fetchOne();

        if (empty($languageId)) {
            return null;
        }

        return $languageId;
    }

    private function createMailTemplate(Connection $connection, string $questionMailTemplateTypeId, $answerMailTemplateTypeId): void
    {
        $questionMailTemplateId = Uuid::randomHex();
        $answerMailTemplateId = Uuid::randomHex();
        $enGbLangId = $this->getLanguageIdByLocale($connection, 'en-GB');
        $deDeLangId = $this->getLanguageIdByLocale($connection, 'de-DE');

        $connection->executeStatement("
        INSERT IGNORE INTO `mail_template`
            (id, mail_template_type_id, system_default, created_at)
        VALUES
            (:id, :mailTemplateTypeId, :systemDefault, :createdAt)
        ",[
            'id' => Uuid::fromHexToBytes($questionMailTemplateId),
            'mailTemplateTypeId' => Uuid::fromHexToBytes($questionMailTemplateTypeId),
            'systemDefault' => 0,
            'createdAt' => (new DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);

        $connection->executeStatement("
        INSERT IGNORE INTO `mail_template`
            (id, mail_template_type_id, system_default, created_at)
        VALUES
            (:id, :mailTemplateTypeId, :systemDefault, :createdAt)
        ",[
            'id' => Uuid::fromHexToBytes($answerMailTemplateId),
            'mailTemplateTypeId' => Uuid::fromHexToBytes($answerMailTemplateTypeId),
            'systemDefault' => 0,
            'createdAt' => (new DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);

        if (!empty($enGbLangId)) {
            $connection->executeStatement("
            INSERT IGNORE INTO `mail_template_translation`
                (mail_template_id, language_id, sender_name, subject, description, content_html, content_plain, created_at)
            VALUES
                (:mailTemplateId, :languageId, :senderName, :subject, :description, :contentHtml, :contentPlain, :createdAt)
            ",[
                'mailTemplateId' => Uuid::fromHexToBytes($questionMailTemplateId),
                'languageId' => $enGbLangId,
                'senderName' => '',
                'subject' => 'A new question has been put on your product',
                'description' => 'When anyone put question on product, email will send ',
                'contentHtml' => $this->getQuestionContentHtmlEn(),
                'contentPlain' => $this->getQuestionContentPlainEn(),
                'createdAt' => (new DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
            ]);

            $connection->executeStatement("
            INSERT IGNORE INTO `mail_template_translation`
                (mail_template_id, language_id, sender_name, subject, description, content_html, content_plain, created_at)
            VALUES
                (:mailTemplateId, :languageId, :senderName, :subject, :description, :contentHtml, :contentPlain, :createdAt)
            ",[
                'mailTemplateId' => Uuid::fromHexToBytes($answerMailTemplateId),
                'languageId' => $enGbLangId,
                'senderName' => '',
                'subject' => 'A new answer has been put on your product question',
                'description' => 'When anyone put answer on product question, email will send ',
                'contentHtml' => $this->getAnswerContentHtmlEn(),
                'contentPlain' => $this->getAnswerContentPlainEn(),
                'createdAt' => (new DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
            ]);
        }

        if (!empty($deDeLangId)) {            
            $connection->executeStatement("
            INSERT IGNORE INTO `mail_template_translation`
                (mail_template_id, language_id, sender_name, subject, description, content_html, content_plain, created_at)
            VALUES
                (:mailTemplateId, :languageId, :senderName, :subject, :description, :contentHtml, :contentPlain, :createdAt)
            ",[
                'mailTemplateId' => Uuid::fromHexToBytes($questionMailTemplateId),
                'languageId' => $deDeLangId,
                'senderName' => '',
                'subject' => 'Zu Ihrem Produkt wurde eine neue Frage gestellt',
                'description' => 'Wenn jemand eine Frage zum Produkt stellt, wird eine E-Mail gesendet',
                'contentHtml' => $this->getQuestionContentHtmlDe(),
                'contentPlain' => $this->getQuestionContentPlainDe(),
                'createdAt' => (new DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
            ]);

            $connection->executeStatement("
            INSERT IGNORE INTO `mail_template_translation`
                (mail_template_id, language_id, sender_name, subject, description, content_html, content_plain, created_at)
            VALUES
                (:mailTemplateId, :languageId, :senderName, :subject, :description, :contentHtml, :contentPlain, :createdAt)
            ",[
                'mailTemplateId' => Uuid::fromHexToBytes($answerMailTemplateId),
                'languageId' => $deDeLangId,
                'senderName' => '',
                'subject' => 'Zu Ihrem Produkt wurde eine neue Frage gestellt',
                'description' => 'Wenn jemand eine Frage zum Produkt stellt, wird eine E-Mail gesendet',
                'contentHtml' => $this->getAnswerContentHtmlDe(),
                'contentPlain' => $this->getAnswerContentPlainDe(),
                'createdAt' => (new DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
            ]);
        }
    }

    private function getQuestionContentHtmlEn(): string
    {
        return <<<MAIL
            <div style="font-family:arial; font-size:12px;">
                <p>
                    Hello<br/>
                    <br/>
                    <b>Below is the question details :</b>
                    <br/>
                    Question: {question} <br/>
                    Asked by: {askedBy} ( {askedEmail} ) 
                    <br/>
                    Product Name: {productName} <br/><br/>
                    <b>Regards <br/>
                    Your Store<b>
                </p>
            </div>
        MAIL;
    }

    private function getQuestionContentPlainEn(): string
    {
        return <<<MAIL
            Hello
            
            Below is the question details :
            
            Question: {question} 
            Asked by: {askedBy} ( {askedEmail} ) 
            Product Name: {productName} 

            Regards 
            Your Store
        MAIL;
    }

    private function getQuestionContentHtmlDe(): string
    {
        return <<<MAIL
            <div style="font-family:arial; font-size:12px;">
                <p>
                    Hallo<br/>
                    <br/>
                    <b> Nachfolgend finden Sie die Fragendetails :</b>
                    <br/>
                    Frage: {question} <br/>
                    Gefragt von: {askedBy} ( {askedEmail} ) 
                    <br/>
                    Produktname: {productName} <br/><br/>
                    <b>Regards <br/>
                    Your Store<b>
                </p>
            </div>
        MAIL;
    }

    private function getQuestionContentPlainDe(): string
    {
        return <<<MAIL
            Hallo
                
            Nachfolgend finden Sie die Fragendetails :
            
            Frage: {question} 
            Gefragt von: {askedBy} ( {askedEmail} ) 
            Produktname: {productName} 

            Regards 
            Your Store
        
        MAIL;
    }

    private function getAnswerContentHtmlEn(): string
    {
        return <<<MAIL
            <div style="font-family:arial; font-size:12px;">
                <p>
                    Hello<br/>
                    <br/>
                    <b>Below is the answer details :</b>
                    <br/>
                    Question: {question} <br/>
                    Answer: {answer} <br/>
                   Answered by: {answeredBy} <br/>
                    <br/>
                    Product Name: {productName} <br/><br/>
                    <b>Regards <br/>
                    Your Store<b>
                </p>
            </div>
        MAIL;
    }

    private function getAnswerContentPlainEn(): string
    {
        return <<<MAIL
            Hello
            
            Below is the answer details :
            
            Question: {question} 
            Answer: {answer} 
            Answered by: {answeredBy}
            Product Name: {productName} 

            Regards 
            Your Store
        MAIL;
    }

    private function getAnswerContentHtmlDe(): string
    {
        return <<<MAIL
            <div style="font-family:arial; font-size:12px;">
                <p>
                    Hallo<br/>
                    <br/>
                    <b> Unten finden Sie die Antwortdetails :</b>
                    <br/>
                    Frage: {question} <br/>
                    Antwort: {answer} <br/>
                    Beantwortet von: {answeredBy} 
                    <br/>
                    Produktname: {productName} <br/><br/>
                    <b>Regards <br/>
                    Your Store<b>
                </p>
            </div>
        MAIL;
    }

    private function getAnswerContentPlainDe(): string
    {
        return <<<MAIL
            Hallo
                
            Unten finden Sie die Antwortdetails :
            
            Frage: {question} 
            Antwort: {answer}
            Beantwortet von: {answeredBy}
            Produktname: {productName} 

            Regards 
            Your Store
        
        MAIL;
    }
}
