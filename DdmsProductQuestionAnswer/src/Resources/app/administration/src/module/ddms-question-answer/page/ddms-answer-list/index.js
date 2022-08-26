const { Component, Mixin } = Shopware;
const { Criteria } = Shopware.Data;
import template from './answer-list.html.twig';
import './qa-question-detail.scss';

Component.register('ddms-question-detail', {
    template,

    inject: [
        'repositoryFactory'
    ],

    mixins: [
        Mixin.getByName('notification'),
        Mixin.getByName('listing'),
        Mixin.getByName('placeholder')
    ],

    data() {
        return {
            isLoading: false,
            processSuccess: false,
            showDeleteModal: false,
            total: 0,
            answers: null,
            page: 1,
            limit: 5,
            answerBy: null,
            answer: null,
            saveAnswer: null,
            users: null,
            adminEmail: null,
            saveQuestion: null
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    computed: {
        answerRepository() {
            return this.repositoryFactory.create('ddms_qa_answer');
        },

        answerColumns() {
            return this.getAnswerColumns();
        },

        userRepository() {
            return this.repositoryFactory.create('user');
        },

        questionRepository() {
            return this.repositoryFactory.create('ddms_qa_question');
        }
    },

    created() {
        this.getList();
        this.getSaveAnswer();
        this.getUser();
        this.getQuestion();
    },

    methods: {
        getList() {
            this.isLoading = true;
            const criteria = new Criteria(this.page, this.limit);
            criteria.setTerm(this.term);
            criteria.addFilter(Criteria.equals('questionId', this.$route.params.id));
            criteria.addAssociation('question');
            criteria.addSorting(Criteria.sort('createdAt', 'DESC'));
            this.answerRepository.search(criteria, Shopware.Context.api).then((result) => {
                this.answers = result;
                this.total = result.total;
                this.isLoading = false;
            }).catch((ex) => {
               this.isLoading = false; 
            });
        },

        getQuestion() {
            this.isLoading = true;
            const criteria = new Criteria(this.page, this.limit);
            criteria.addFilter(Criteria.equals('id', this.$route.params.id));
            this.questionRepository.search(criteria, Shopware.Context.api).then((result) => {
                this.saveQuestion = result[0];
                this.updateQuestion();
                this.isLoading = false;
            });
        },

        updateQuestion() {
            console.log('tst')
            this.saveQuestion.viewStatus = false;
            if (this.saveQuestion) {
                this.questionRepository.save(this.saveQuestion, Shopware.Context.api);
            }
                
        },

        getUser() {
            this.isLoading = true;
            const criteria = new Criteria(this.page, this.limit);
            this.userRepository.search(criteria, Shopware.Context.api).then((result) => {
                this.users = result;
                this.adminEmail = this.users[0].email;
            });
        },
        
        getSaveAnswer() {
            this.saveAnswer = this.answerRepository.create(Shopware.Context.api);
        },

        getAnswerColumns() {
            return [{
                property: 'answer',
                dataIndex: 'answer',
                label: this.$tc('qa.question.detail.columnAnswer'),
                allowResize: true,
                primary: true,
                inlineEdit: 'string',
            },{
                property: 'answerBy',
                dataIndex: 'answerBy',
                label: this.$tc('qa.question.detail.columnAnswerBy'),
                allowResize: true
            },{
                property: 'createdAt',
                dataIndex: 'createdAt',
                label: this.$tc('qa.question.detail.columnDate'),
                allowResize: true
            },{
                property: 'status',
                dataIndex: 'status',
                inlineEdit: 'boolean',
                label: this.$tc('qa.question.detail.columnStatus'),
                allowResize: true
            }]
        },

        onPageChange({ page = 1, limit = 5 }) {
            this.page = page;
            this.limit = limit;
            this.getList();
        },

        onDelete(id) {
            this.showDeleteModal = id;
            
        },

        onCloseDeleteModal() {
            this.showDeleteModal = false;
        },

        onConfirmDelete(id) {
            this.showDeleteModal = false;
            
            return this.answerRepository.delete(id, Shopware.Context.api).then(() => {
                this.getList();
            });
        },

        saveFinish() {
            this.processSuccess = false;
        },

        onClickSave() {
            this.isLoading = true;
            this.processSuccess = true;
            this.saveAnswer.questionId = this.$route.params.id;
            this.saveAnswer.answerBy = this.answerBy;
            this.saveAnswer.email = this.adminEmail;
            this.saveAnswer.answer = this.answer;
            this.saveAnswer.status = true;

            if (this.saveAnswer.answerBy == null) {
                this.isLoading = false;
                this.createNotificationError({
                    title: this.$t('qa.question.detail.errorTitle'),
                    message: this.$t('qa.question.detail.errorAnswerByMessage')
                });
                return;
            } else if (this.saveAnswer.answer == null) {
                this.isLoading = false;
                this.createNotificationError({
                    title: this.$t('qa.question.detail.errorTitle'),
                    message: this.$t('qa.question.detail.errorAnswerMessage')
                });
                return;
            } else if (!(this.saveAnswer.answer.length >=1 && this.saveAnswer.answer.length <=185)) {
                this.isLoading = false;
                this.createNotificationError({
                    title: this.$t('qa.question.detail.errorTitle'),
                    message: this.$t('qa.question.detail.errorAnswerLimit')
                });
                return
            } else if (!(this.saveAnswer.answerBy.length >=1 && this.saveAnswer.answerBy.length <=185)) {
                this.isLoading = false;
                this.createNotificationError({
                    title: this.$t('qa.question.detail.errorTitle'),
                    message: this.$t('qa.question.detail.errorAnswerByLimit')
                });
                return
            } else {
                this.answerRepository.save(this.saveAnswer, Shopware.Context.api).then(() => {
                    this.isLoading = false;
                    this.processSuccess = false;
                    this.createNotificationSuccess({
                        title: this.$t('qa.question.detail.successTitle'),
                        message: this.$t('qa.question.detail.successMessage')
                    });
                    
                    setTimeout(function() {
                        location.reload(true);
                    }, 1000);
                }).catch(() => {
                    this.isLoading = false;
                    this.createNotificationError({
                        title: this.$t('qa.question.detail.errorTitle'),
                        message: this.$t('qa.question.detail.errorMessage')
                    });
                });               
            }
        }
    }
});