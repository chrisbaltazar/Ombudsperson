<template>
    <table class="table table-striped">
        <thead>
            <th width = "50">Fecha</th>
            <th width = "50">Tipo</th>
            <th>Asunto</th>
            <th>Acuerdos</th>
            <th width = "30"></th>
        </thead>
        <tr v-for="item in history" :key="item.id">
            <td>{{ item.meeting_date | dateFormat  }}</td>
            <td>{{ item.type }}</td>
            <td>{{ item.subject }}</td>
            <td>{{ item.agreements | text }}</td>
            <td><button class="btn btn-primary" @click="openEvent(item)"> <i class="fa fa-search"></i> </button></td>
        </tr>
    </table>
</template>

<script>
import { eventBus } from '../../main'

export default {
    props: ['payload'], 
    data() {
        return {
            history: []
        }
    }, 
    methods: {
        openEvent(event){
            eventBus.$emit('EventOpen', event);
        }
    },
    created() {
        this.$http.get('tracings/search/' + this.payload).then(
            response => {
                this.history = response.data.tracings; 
            }, 
            error => {
                this.Err(error);
            }
        )
    }
}
</script>
