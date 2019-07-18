<template>
    <form @submit.prevent="save()">
        <div class="form-group">
            <label for="">Seleccione</label>
            <v-select v-model = "newUser" :options = "list" :get-option-label = "display" label = "name"></v-select>
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
            list: [], 
            newUser: ''
        }
    },
    methods: {
        display(option){
            return `${option.name}, ${option.area}`;
        }, 
        save() {
            if(this.newUser){
                this.loadButton(this.$refs.btnSave);
                this.$http.post('users', this.newUser).then(
                    response => {
                        this.OK("Guardado");
                        $('.modal').modal('hide');
                        eventBus.$emit('UserSaved');
                    }, 
                    error => {
                        this.Err(error);
                    }
                )
            }
        }
    },
    created() {
        this.$http.get('users/search').then(
            response => {
                this.list = response.data.users;
            },
            error => {
                this.Err(error);
            }
        )
    }
}
</script>
