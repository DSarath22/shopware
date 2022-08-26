const { Component, Mixin } = Shopware;
const { Criteria } = Shopware.Data;
import template from './question-list.html.twig';
import './qa-question-list.scss';

Component.register('ddms-question-list', {
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
            showDeleteModal: false,
            processSuccess: false,
            total: 0,
            questions: null,
            page: 1,
            limit: 25,
            term: null,
            question: null
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    computed: {
        questionRepository() {
            return this.repositoryFactory.create('ddms_qa_question');
        },

        questionColumns() {
            return this.getQuestionColumns();
        },
        
        productRepository() {
            return this.repositoryFactory.create('product');
        }
    },

    created() {
        this.getList();
    },

    methods: {
        getList() {
            this.isLoading = true;
            const criteria = new Criteria(this.page, this.limit);
            criteria.setTerm(this.term);
            criteria.addAssociation('customer');
            criteria.addAssociation('product');
            criteria.addAssociation('product.cover');
            criteria.addSorting(Criteria.sort('createdAt', 'DESC'));
            this.questionRepository.search(criteria, Shopware.Context.api).then((result) => {
                this.questions = result;
                // this.questions.forEach((question, iteration) => {
                //     if(!question.product.name) {
                        
                //         this.productRepository.get(question.product.parentId, Shopware.Context.api).then(result => {
                //             this.questions[iteration].product.name = result.name;
                //         })
                //     }
                // });
                this.total = result.total;
                this.isLoading = false;
            }).catch((ex) => {
                this.isLoading = false;
            });
        },

        onSearch(term) {
            this.page = 1
            this.limit = 25
            this.term = term;
            this.getList();
        },

        getQuestionColumns() {
            return [{
                property: 'question',
                dataIndex: 'question',
                label: this.$tc('qa.question.list.columnQuestion'),
                allowResize: true,
                primary: true,
            },{
                property: 'product.name',
                dataIndex: 'product.name',
                label: this.$tc('qa.question.list.columnProductName'),
                allowResize: true,
                primary: true
            },{
                property: 'customer.firstName',
                dataIndex: 'customer.firstName',
                label: this.$tc('qa.question.list.columnCustomerName'),
                allowResize: true
            },{
                property: 'customer.email',
                dataIndex: 'customer.email',
                label: this.$tc('qa.question.list.columnCustomerEmail'),
                allowResize: true
            },{
                property: 'createdAt',
                dataIndex: 'createdAt',
                label: this.$tc('qa.question.list.columnDate'),
                allowResize: true
            },{
                property: 'status',
                dataIndex: 'status',
                inlineEdit: 'boolean',
                label: this.$tc('qa.question.list.columnStatus'),
                allowResize: true
            }]
        },

        onPageChange({ page = 1, limit = 25 }) {
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
           
            return this.questionRepository.delete(id, Shopware.Context.api).then(() => {
                this.getList();
            });
        },

        openModal(question) {
            this.question = question;
        },

        cancel() {
            this.question = '';
        },
    }
})

