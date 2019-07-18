<template>
    <form @submit.prevent="saveEvent">
        <div class="form-group">
            <label for="">Fecha del evento</label>
            <app-label>{{event_date | dateFormat(true) }}</app-label>
        </div>
        <div class="form-group">
            <label for="">Hora del evento</label>
            <input type="time" class="form-control" v-model ="event.time" step ="1800" required>
        </div>
        <div class="form-group">
            <label for="">Tipo de evento</label>
            <select class="form-control" v-model ="event.type" required>
                <option value = "ENTREVISTA">ENTREVISTA</option>
                <option value = "REUNION">REUNIÓN</option>
            </select>
        </div>
        <div class="form-group">
            <label for="">Asunto</label>
            <input type="text" class="form-control" v-model ="event.subject" required>
        </div>
        <div class="form-group" v-if="event_passed">
            <label for="">Acuerdos</label>
            <textarea class="form-control" rows="5" v-model ="event.agreements" placeholder="Acuerdos generados" required></textarea>
        </div>
        <p v-if="stillModify">
            <button ref="btnAgenda" class="btn btn-success btn-lg"> <i class="fa fa-calendar"></i> Agendar</button>
            <button ref="btnDelete" v-if="event.id" class="btn btn-danger btn-lg float-right" @click.prevent="deleteEvent"> <i class="fa fa-trash"></i> Eliminar</button>
        </p>
    </form>
</template>

<script>
import moment from 'moment'
import { eventBus } from '../../main'

export default {
    props: ['payload'], 
    data() {
        return {
            event: {}
        }
    },
    computed: {
        event_date() {
            return (this.event.id ? this.event.meeting_date : this.payload.date );
        }, 
        event_passed() {
            return moment() > moment(this.event_date);
        }, 
        stillModify() {
          return (this.session('role') == 1 && this.payload.data.status.id < 5);
        }
    }, 
    methods: {
        saveEvent() {
            this.loadButton(this.$refs.btnAgenda);
            if(this.event.id){
                this.$http.put('tracings/' + this.event.id, this.event).then(
                    response => {
                        this.OK("Evento guardado");
                        $('.modal').modal('hide');
                        eventBus.$emit('EventSaved', response.data.tracing);
                    }, 
                    error => {
                        this.Err(error);
                    }
                )
            }else{
                this.$http.post('tracings', this.event).then(
                    response => {
                        this.OK("Evento guardado");
                        $('.modal').modal('hide');
                        eventBus.$emit('EventSaved', response.data.tracing);
                    }, 
                    error => {
                        this.Err(error);
                    }
                )
            }
        }, 
        deleteEvent() {
            this.Ask("¿Dese eliminar este evento?", () => {
                this.$http.delete('tracings/' + this.event.id).then(
                    response => {
                        $('.modal').modal('hide');
                        eventBus.$emit('EventDeleted', this.event);
                    },  
                    error => {
                        this.Err(error);
                    }
                )
            });
        }
    },
    created() {
        if(this.payload.event){
            this.$http.get('tracings/' + this.payload.event.id).then(
                response => {
                    this.event = response.data.tracing;
                    this.event.date = moment(this.event_date).format('YYYY/MM/DD');
                    this.event.time = moment(this.event_date).format('HH:mm');
                }, 
                error => {
                    this.Err(error);
                }
            )
        }else{
            this.event.complaint_id = this.payload.data.id; 
            this.event.date = moment(this.event_date).format('YYYY-MM-DD');
        }
    }
}
</script>
