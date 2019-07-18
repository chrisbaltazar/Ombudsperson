<template>
    <div class="row">
        <app-title>Registro de denuncias</app-title>
        <app-modal :load = "modalComponent" :payload = "modalData" :title = "modalTitle" :size = "modalClass"></app-modal>

        <div class="col-6">
            <button class="btn btn-primary mb-3" @click="newComplaint()"><i class="fa fa-plus"></i> Registrar nueva denuncia</button>
        </div>
        <div class="col-6" v-if="session('role') == 1">
            <button class="float-right btn btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Mostrar: {{currentView}}</button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#" v-for="(v, i) in viewList" :key="i" @click.prevent="viewMode = i">{{v}}</a>
            </div>
        </div>
        
        <complaint-table :complaint_list = "list" 
                            @edit = "editComplaint" 
                            @delete="deleteComplaint"
                            @continue = "openComplaint">
        </complaint-table>
        
    </div>
</template>

<script>
import {eventBus} from '../../main'
import ComplaintTable from './ComplaintTable.component.vue'

export default {
    components: {ComplaintTable},
    data() {
        return {
            viewList: [
                'Todas las denuncias',
                'Nuevas denuncias',
                'Denuncias cerradas',
            ],
            viewMode: 0,
            complaints: []
        }
    }, 
    computed: {
        currentView() {
            return this.viewList[this.viewMode];
        }, 
        list() {
            if(this.complaints){
                if(this.session('role') == 1){
                    switch(this.viewMode){
                        case 0:
                            return this.complaints;
                        case 1: 
                            return this.complaints.filter(item => item.status.id == 1);
                        case 2: 
                            return this.complaints.filter(item => item.status.id == 5);
                    }
                }else{
                    return this.complaints.filter(item => item.author.id == this.session('id'));
                }
            }else{
                return [];
            }
            
        }
    },
    methods: {
        loadComplaints() {
            this.$http.get('complaints').then(
                response => {
                    this.complaints = response.data.complaints;
                }, 
                error => {
                    this.Err(error);
                }
            )
        }, 
        newComplaint() {
            this.modalComponent = "complaint-form";
            this.modalTitle = "Apertura de denuncia"; 
            this.modalData = ""; 
            this.modalClass = "large";
            this.Modal();
        }, 
        editComplaint(comp){
            this.modalComponent = "complaint-form";
            this.modalTitle = "Apertura de denuncia"; 
            this.modalData = comp.id; 
            this.modalClass = "large";
            this.Modal();
        }, 
        deleteComplaint(comp){
            this.Ask("¿Desea eliminar esta denuncia?", () => {
                this.$http.delete('complaints/' + comp.id).then(
                    response => {
                        let index = this.complaints.map(c => {return c.id}).indexOf(comp.id);
                        this.complaints.splice(index, 1);
                    }, 
                    error => {
                        this.Err(error);
                    }
                )
            })
        }, 
        openComplaint(){

        }
    }, 
    created() {
        this.loadComplaints();
        eventBus.$on('ComplaintLoad', () => { this.loadComplaints() });
    }
}
</script>

