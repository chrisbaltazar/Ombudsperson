<template>
    <div class="col-12">

        <app-title>Denuncia: <span class="badge badge-dark">{{ complaint.folio }} / {{complaint.created_at | year}}: <small>{{complaint.author.name}}</small></span> </app-title>
        <app-modal :load = "modalComponent" :payload = "modalData" :title = "modalTitle" :size = "modalClass"></app-modal>

        <template v-if="loading">
            <h3>Abriendo información.... <i class="fa fa-cog fa-spin"></i></h3> 
        </template>

        <div class="alert alert-danger" v-if="complaint.status_id == 2 && complaint.author.id == session('id')">
            <h4>Por favor proceda a completar la información correspondiente de su DENUNCIA en el apartado señalado debajo</h4>
        </div>

        <template v-if="!loading && complaint.id">
            <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#info" role="tab" aria-controls="pills-home" aria-selected="true">Datos generales</a>
                </li>
                <li class="nav-item" >
                    <a class="nav-link" id="pills-home-tab" data-toggle="pill" href="#details" role="tab" aria-controls="pills-home" aria-selected="true">Detalles de denuncia <span class="badge badge-pill badge-danger" v-if="complaint.status_id == 2"> <i class="fa fa-exclamation-triangle"></i> </span></a>
                </li>
                <li class="nav-item" >
                    <a class="nav-link" id="pills-home-tab" data-toggle="pill" href="#tracing" role="tab" aria-controls="pills-home" aria-selected="true">{{session('role') == 0 && complaint.status.id == 2 ? 'Entrevista programada': 'Seguimiento'}} </a>
                </li>
                <template v-if="complaint.status.id > 2">
                    <li class="nav-item" >
                        <a class="nav-link" id="pills-home-tab" data-toggle="pill" href="#files" role="tab" aria-controls="pills-home" aria-selected="true">Expediente</a>
                    </li>
                    <li class="nav-item" >
                        <a class="nav-link" id="pills-home-tab" data-toggle="pill" href="#closing" role="tab" aria-controls="pills-home" aria-selected="true">Resolución</a>
                    </li>
                </template>
                <li class="nav-item" v-if="complaint.status.id == 5 && session('id') == complaint.author.id">
                    <a class="nav-link" id="pills-home-tab" data-toggle="pill" href="#survey" role="tab" aria-controls="pills-home" aria-selected="true">Encuesta</a>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="pills-home-tab">
                    <view-info :data = "complaint"></view-info>
                </div>
                <div class="tab-pane fade show " id="details" role="tabpanel" aria-labelledby="pills-home-tab">
                    <view-details :data = "complaint" @update = "complaint = $event"></view-details>
                </div>
                <div class="tab-pane fade show " id="tracing" role="tabpanel" aria-labelledby="pills-home-tab">
                    <view-tracing :data = "complaint" :tracing = "events" @newEvent = "newEvent" @viewEvent = "viewEvent" @viewList="viewTracing"></view-tracing>
                </div>
                <template v-if="complaint.status.id > 2"> 
                    <div class="tab-pane fade show " id="files" role="tabpanel" aria-labelledby="pills-home-tab">
                        <view-files :data = "complaint"></view-files>
                    </div>
                    <div class="tab-pane fade show " id="closing" role="tabpanel" aria-labelledby="pills-home-tab">
                        <view-closing :data = "complaint" @close = "complaint.status = $event.status"></view-closing>
                    </div>
                </template>
                <div v-if="complaint.status.id == 5 && session('id') == complaint.author.id" class="tab-pane fade show " id="survey" role="tabpanel" aria-labelledby="pills-home-tab">
                    <view-survey :data = "complaint" ></view-survey>
                </div>
            </div>

        </template>
        <template v-if="!loading && !complaint.id">
            <div class="alert alert-danger">
                <h3>No cuenta con permisos para ver esta información</h3>
            </div>
        </template>
    </div>
</template>

<script>
import ViewInfo from './ComplaintInfo.component.vue'
import ViewDetails from './ComplaintDetails.component.vue'
import ViewTracing from './ComplaintTracing.component.vue'
import ViewClosing from './ComplaintClosing.component.vue'
import ViewFiles from './ComplaintFiles.component.vue'
import ViewSurvey from './ComplaintSurvey.component.vue'
import { eventBus } from '../../main'

export default {
    props: ['id'], 
    components: {ViewInfo, ViewDetails, ViewTracing, ViewFiles, ViewClosing, ViewSurvey},
    data() {
        return {
            loading: true, 
            complaint: {
                author: {},
                status: {}
            }
        }
    }, 
    computed: {
        events() {
            return this.complaint.tracings;
        }
    },
    methods:  {
        newEvent(date) {
            this.modalComponent = "complaint-event";
            this.modalTitle = "Nuevo evento";
            this.modalData = {data: this.complaint, date: date }
            this.modalClass = "";
            this.Modal();
        }, 
        viewEvent(event) {
            this.modalComponent = "complaint-event";
            this.modalTitle = "Ver evento";
            this.modalData = {data: this.complaint, event: event }
            this.modalClass = "";
            this.Modal();
        }, 
        viewTracing() {
            this.modalComponent = "complaint-history";
            this.modalTitle = "Historial de seguimiento";
            this.modalData = this.complaint.id;
            this.modalClass = "large";
            this.Modal();
        }
    },
    created() {
        this.$http.get('complaints/' + this.id).then(
            response => {
                this.loading = false;
                if(this.session('role') == 1 || response.data.complaint.author.id == this.session('id')){
                    this.complaint = response.data.complaint;
                }
            }, 
            error => {
                this.Err(error);
            }
        )

        eventBus.$on('EventSaved', (event) => {
            let index = this.complaint.tracings.map(t => { return t.id }).indexOf(event.id);
            if(index > -1)
                this.complaint.tracings.splice(index, 1);
            this.complaint.tracings.push(event);
        });

        eventBus.$on('EventDeleted', (event) => {
            console.log(event);
            let index = this.complaint.tracings.map(t => { return t.id }).indexOf(event.id);
            this.complaint.tracings.splice(index, 1);
        });

        eventBus.$on('EventOpen', (event) => {
            this.viewEvent(event);
        });
    }
}
</script>

