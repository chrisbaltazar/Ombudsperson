<template>
    <form @submit.prevent="save()">
        <div class="form-group">
            <label for="">Pregunta</label>
            <textarea class="form-control" placeholder="Cuerpo de la pregunta" v-model = "newQuestion.body" required></textarea>
        </div>
        <div class="form-group">
            <label for="">Tipo de pregunta</label>
            <select class="form-control" v-model = "newQuestion.type" required>
                <option value="CLOSED">CERRADA</option>
                <option value="OPEN">ABIERTA</option>
            </select>
        </div>
        <p><button ref="btnSave" class="btn btn-success btn-lg" ><i class="fa fa-save"></i> Guardar</button></p>
    </form>
</template>

<script>

import {eventBus} from '../../main';

export default {
    props: ['payload'], 
    data() {
        return {
            newQuestion: {}
        }
    },
    methods: {
        save() {
            if(this.newQuestion){
                this.loadButton(this.$refs.btnSave);
                if(this.payload){
                    this.$http.put('questions/' + this.newQuestion.id, this.newQuestion).then(
                        response => {
                            this.OK("Guardado");
                            $('.modal').modal('hide');
                            eventBus.$emit('QuestionSaved');
                        }, 
                        error => {
                            this.Err(error);
                        }
                    )
                }else{
                    this.$http.post('questions', this.newQuestion).then(
                        response => {
                            this.OK("Guardado");
                            $('.modal').modal('hide');
                            eventBus.$emit('QuestionSaved');
                        }, 
                        error => {
                            this.Err(error);
                        }
                    )
                }
            }
        }
    },
    created() {
        if(this.payload){
            this.$http.get('questions/' + this.payload).then(
                response => {
                    this.newQuestion = response.data.question;
                }
            )
        }
    }
}
</script>
