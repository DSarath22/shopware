<?php declare(strict_types=1);

namespace Ddms\ProductQuestionAnswer\Core\Content\Answer;

use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Ddms\ProductQuestionAnswer\Core\Content\Question\QuestionDefinition;

class AnswerDefinition extends EntityDefinition
{
    public const  ENTITY_NAME = 'ddms_qa_answer';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return AnswerEntity::class;
    }

    public function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new FkField('question_id', 'questionId', QuestionDefinition::class)),
            (new StringField('answer_by', 'answerBy'))->addFlags(new Required()),
            (new StringField('answer', 'answer'))->addFlags(new Required()),
            (new BoolField('status', 'status'))->addFlags(new Required()),
            (new ManyToOneAssociationField('question', 'question_id', QuestionDefinition::class))
        ]);
    }
}