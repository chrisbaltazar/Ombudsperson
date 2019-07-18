
<template>
    <div class="row">
        <app-title>Preguntas de encuesta</app-title>
        <app-modal :load = "modalComponent" :payload = "modalData" :title = "modalTitle" :size = "modalClass"></app-modal>

        <button class="btn btn-primary mb-3 float-right" @click="newQuestion()"><i class="fa fa-plus"></i> Nueva pregunta</button>

        <questions-table :questions = "questions" @QuestionEdit = "editQuestion($event)" @QuestionDelete = "deleteQuestion($event)"></questions-table>
        
    </div>
</template>

<script>

import {eventBus} from '../../main'
import QuestionsTable from './QuestionsTable.component.vue'


export default {
    components: {QuestionsTable},
    data() {
        return {
            questions: []
        }
    },
    methods: {
        loadQuestions() {
            this.$http.get('questions').then(
                response => {
                    this.questions = response.data.questions;
                }, 
                error => {
                    this.Err(error);
                }
            )
        }, 
        newQuestion() {
            this.modalComponent = "question-form";
            this.modalTitle = "Nueva pregunta";
            this.modalData = "";
            this.Modal();
        }, 
        editQuestion(question) {
            this.modalComponent = "question-form";
            this.modalTitle = "Editar pregunta";
            this.modalData = question.id;
            this.Modal();
        },
        deleteQuestion(question){
            this.Ask("¿Desea eliminar esta pregunta?", () => {
                this.$http.delete('questions/' + question.id).then(
                    response => {
                        let index = this.questions.map(q => { return q.id }).indexOf(question.id);
                        this.questions.splice(index, 1);
                    }, 
                    error => {
                        this.Err(error);
                    }
                )
            });
        }
    },
    created() {
        this.loadQuestions(); 
        eventBus.$on('QuestionSaved', () => { this.loadQuestions() });
    }
}
</script>

