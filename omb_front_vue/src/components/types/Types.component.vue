
<template>
    <div class="row">
        <app-title>Tipos de Denuncia</app-title>
        <app-modal :load = "modalComponent" :payload = "modalData" :title = "modalTitle" :size = "modalClass"></app-modal>

        <div class="col-9 offset-3">
            <button class="btn btn-primary mb-3 " @click="newType()"><i class="fa fa-plus"></i> Nuevo tipo</button>
        </div>
        <div class="col-6 offset-3">
            <types-table :types = "types"  @TypeDelete = "deleteType($event)"></types-table>
        </div>    
        
    </div>
</template>

<script>

import {eventBus} from '../../main'
import TypesTable from './TypesTable.component.vue'


export default {
    components: {TypesTable},
    data() {
        return {
            types: []
        }
    },
    methods: {
        loadTypes() {
            this.$http.get('types').then(
                response => {
                    this.types = response.data.types;
                }, 
                error => {
                    this.Err(error);
                }
            )
        }, 
        newType() {
            this.modalComponent = "type-form";
            this.modalTitle = "Nuevo tipo";
            this.modalData = "";
            this.Modal();
        }, 
        editType(type) {
            this.modalComponent = "type-form";
            this.modalTitle = "Editar tipo";
            this.modalData = type.id;
            this.Modal();
        },
        deleteType(id){
            this.Ask("¿Desea eliminar este tipo?", () => {
                this.$http.delete('types/' + id).then(
                    response => {
                        let index = this.types.map(t => { return t.id }).indexOf(id);
                        this.types.splice(index, 1);
                    }, 
                    error => {
                        this.Err(error);
                    }
                )
            });
        }
    },
    created() {
        this.loadTypes(); 
        eventBus.$on('TypeSaved', () => { this.loadTypes() });
        eventBus.$on('TypeEdit', (type) => { this.editType(type) });
    }
}
</script>

