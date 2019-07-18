<template>
    <form @submit.prevent="save()">
        <div class="form-group">
            <label for="">Tipo de denuncia</label>
            <input type="text" class="form-control" v-model = "newType.name" required>
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
            newType: {}
        }
    },
    methods: {
        save() {
            if(this.newType){
                this.loadButton(this.$refs.btnSave);
                if(this.payload){
                    this.$http.put('types/' + this.newType.id, this.newType).then(
                        response => {
                            this.OK("Guardado");
                            $('.modal').modal('hide');
                            eventBus.$emit('TypeSaved');
                        }, 
                        error => {
                            this.Err(error);
                        }
                    )
                }else{
                    this.$http.post('types', this.newType).then(
                        response => {
                            this.OK("Guardado");
                            $('.modal').modal('hide');
                            eventBus.$emit('TypeSaved');
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
            this.$http.get('types/' + this.payload).then(
                response => {
                    this.newType = response.data.type;
                }
            )
        }
    }
}
</script>
