<?php declare(strict_types=1);

namespace Ddms\ProductQuestionAnswer\Core\Content\Question;

use Shopware\Core\Checkout\Customer\CustomerDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Ddms\ProductQuestionAnswer\Core\Content\Answer\AnswerDefinition;

class QuestionDefinition extends EntityDefinition
{
    public const  ENTITY_NAME = 'ddms_qa_question';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return QuestionEntity::class;
    }

    public function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new FkField('customer_id', 'customerId', CustomerDefinition::class))->addFlags(new Required()),
            (new FkField('product_id', 'productId', ProductDefinition::class))->addFlags(new Required()),
            (new StringField('question', 'question'))->addFlags(new Required()),
            (new BoolField('status', 'status')),
            (new BoolField('view_status','viewStatus')),
            (new ManyToOneAssociationField('customer', 'customer_id', CustomerDefinition::class)),
            (new ManyToOneAssociationField('product', 'product_id', ProductDefinition::class)),
            (new OneToManyAssociationField('answer', AnswerDefinition::class, 'question_id', 'id'))
        ]);
    }
}