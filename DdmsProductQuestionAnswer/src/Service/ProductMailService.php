<?php declare(strict_types=1);

namespace Ddms\ProductQuestionAnswer\Service;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Validation\DataBag\DataBag;
use Shopware\Core\Content\Mail\Service\MailService;
use Shopware\Core\System\User\UserEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;

class ProductMailService
{   
    /**
     * @var MailService
     */
    protected $mailService;

    /**
     * @var EntityRepositoryInterface
     */
    protected $userRepository;

    /**
     * @var EntityRepositoryInterface
     */
    protected $mailTemplateTypeRepository;

    /**
     * @var EntityRepositoryInterface
     */
    protected $mailTemplateRepository;

    public function __construct(
        MailService $mailService,
        EntityRepositoryInterface $userRepository,
        EntityRepositoryInterface $mailTemplateTypeRepository,
        EntityRepositoryInterface $mailTemplateRepository
    )
    {
        $this->mailService = $mailService;
        $this->userRepository = $userRepository;
        $this->mailTemplateTypeRepository = $mailTemplateTypeRepository;
        $this->mailTemplateRepository = $mailTemplateRepository;
    }

    public function questionMail($question, $context) {
        $questionMailTemplateTypeId = $this->mailTemplateTypeRepository->search(
            (new Criteria())
            ->addFilter(new EqualsFilter('technicalName', 'question_mail')),
            Context::createDefaultContext()
        )->first()->getId();

        if ($questionMailTemplateTypeId) {
            $questionMailTemplate = $this->mailTemplateRepository->search(
                (new Criteria())
                ->addFilter(new EqualsFilter('mailTemplateTypeId', $questionMailTemplateTypeId)),
                Context::createDefaultContext()
            )->first();

            $admin = $this->getAdmin();
            $subject = $questionMailTemplate->getTranslated()['subject'];
            $contentHtml = $questionMailTemplate->getTranslated()['contentHtml'];
            $contentPlain = $questionMailTemplate->getTranslated()['contentPlain'];

            $find = [
                '{question}',
                '{askedBy}',
                '{askedEmail}',
                '{productName}'
            ];

            $replace = [
                $question->getQuestion(),
                $question->getCustomer()->getFirstName().' '. $question->getCustomer()->getLastName(),
                $question->getCustomer()->getEmail(),
                $question->getProduct()->getTranslated()['name']
            ];
            
            $updateContentHtml = str_replace($find, $replace, $contentHtml);
            $updateContentPlain = str_replace($find, $replace, $contentPlain);

            $dataBag = new DataBag();
                   
            $dataBag->set('recipients', [
                $question->getCustomer()->getEmail() => $question->getCustomer()->getFirstName().' '.$question->getCustomer()->getLastName()               
            ]);
    
            $dataBag->set('senderName', $admin->getEmail());
            $dataBag->set('salesChannelId', $context->getSalesChannel()->getId());
            $dataBag->set('subject', $subject);
            $dataBag->set('contentHtml', '<div class="mail-template">' . $updateContentHtml . '</div>');
            $dataBag->set('contentPlain',  '<div class="mail-template">' . $updateContentPlain . '</div>');
            
            $this->mailService->send($dataBag->all(), Context::createDefaultContext(), ['salesChannel' => $context->getSalesChannel()]); 
        }
    }

    public function answerMail($answer, $context) {
        $answerMailTemplateTypeId = $this->mailTemplateTypeRepository->search(
            (new Criteria())
            ->addFilter(new EqualsFilter('technicalName', 'answer_mail')),
            Context::createDefaultContext()
        )->first()->getId();

        if ($answerMailTemplateTypeId) {
            $answerMailTemplate = $this->mailTemplateRepository->search(
                (new Criteria())
                ->addFilter(new EqualsFilter('mailTemplateTypeId', $answerMailTemplateTypeId)),
                Context::createDefaultContext()
            )->first();

            $admin = $this->getAdmin();
            $subject = $answerMailTemplate->getTranslated()['subject'];
            $contentHtml = $answerMailTemplate->getTranslated()['contentHtml'];
            $contentPlain = $answerMailTemplate->getTranslated()['contentPlain'];

            $find = [
                '{question}',
                '{answer}',
                '{answeredBy}',
                '{productName}'
            ];
           
            $replace = [
                $answer->getQuestion()->getQuestion(),
                $answer->getAnswer(),
                $answer->getAnswerBy(),
                $answer->getQuestion()->getProduct()->getTranslated()['name']
            ];

            $updateContentHtml = str_replace($find, $replace, $contentHtml);
            $updateContentPlain = str_replace($find, $replace, $contentPlain);

            $dataBag = new DataBag();
                   
            $dataBag->set('recipients', [
                $context->getCustomer()->getEmail() => $context->getCustomer()->getFirstName().' '.$context->getCustomer()->getLastName()               
            ]);
    
            $dataBag->set('senderName', $admin->getEmail());
            $dataBag->set('salesChannelId', $context->getSalesChannel()->getId());
            $dataBag->set('subject', $subject);
            $dataBag->set('contentHtml', '<div class="mail-template">' . $updateContentHtml . '</div>');
            $dataBag->set('contentPlain',  '<div class="mail-template">' . $updateContentPlain . '</div>');
            
            $this->mailService->send($dataBag->all(), Context::createDefaultContext(), ['salesChannel' => $context->getSalesChannel()]);
            
        }
    }


    private function getAdmin():? UserEntity
    {
        $userEntities = $this->userRepository->search(
            (new Criteria())->addFilter(new EqualsFilter('active', true)),
            Context::createDefaultContext()
        );

        if ($userEntities->getTotal() > 0) {
            return $userEntities->first();
        }
        
        return null;
    }
}