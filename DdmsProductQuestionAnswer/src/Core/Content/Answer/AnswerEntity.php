<?php declare(strict_types=1);

namespace Ddms\ProductQuestionAnswer\Core\Content\Answer;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Ddms\ProductQuestionAnswer\Core\Content\Question\QuestionEntity;

class AnswerEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $questionId;

    /**
     * @var QuestionEntity
     */
    protected $question;

    /**
     * @var string
     */
    protected $answerBy;

    /**
     * @var string
     */
    protected $answer;

    /**
     * @var bool
     */
    protected $status;


    /**
     * Get the value of questionId
     *
     * @return  string
     */ 
    public function getQuestionId()
    {
        return $this->questionId;
    }

    /**
     * Set the value of questionId
     *
     * @param  string  $questionId
     *
     * @return  self
     */ 
    public function setQuestionId(string $questionId)
    {
        $this->questionId = $questionId;

        return $this;
    }

    /**
     * Get the value of question
     *
     * @return  QuestionEntity
     */ 
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set the value of question
     *
     * @param  QuestionEntity  $question
     *
     * @return  self
     */ 
    public function setQuestion(QuestionEntity $question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get the value of answerBy
     *
     * @return  string
     */ 
    public function getAnswerBy()
    {
        return $this->answerBy;
    }

    /**
     * Set the value of answerBy
     *
     * @param  string  $answerBy
     *
     * @return  self
     */ 
    public function setAnswerBy(string $answerBy)
    {
        $this->answerBy = $answerBy;

        return $this;
    }

    /**
     * Get the value of answer
     *
     * @return  string
     */ 
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set the value of answer
     *
     * @param  string  $answer
     *
     * @return  self
     */ 
    public function setAnswer(string $answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get the value of status
     *
     * @return  bool
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @param  bool  $status
     *
     * @return  self
     */ 
    public function setStatus(bool $status)
    {
        $this->status = $status;

        return $this;
    }
}