<template>
 <!-- <div class="col-6 offset-3"> -->

 
    <table class="table table-striped table-hover" id ="tbl-types">
        <thead class="thead-dark">
            <tr>
                <th width = "30">#</th>
                <th>Tipo</th>
                <th width = "100"></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(type, index) in types" :key = "type.id">
                <td>{{index+1}}</td>
                <td>{{type.name}}</td>
                <td>
                    <button class="btn btn-warning btn-sm"><i class="fa fa-edit" @click="edit(type)"></i> </button>
                    <button class="btn btn-danger btn-sm"><i class="fa fa-trash" @click="$emit('TypeDelete', type.id)"></i> </button>
                </td>
            </tr>
        </tbody>
    </table>

<!-- </div> -->
</template>

<script>
import {eventBus} from '../../main'

export default {
    props: ['types'], 
    methods: {
        edit(type) {
            eventBus.$emit('TypeEdit', type);
            // this.$emit('TypeEdit', type);
        }
    },
    updated() {
        this.makeDataTable('#tbl-types');
    }
}
</script>
