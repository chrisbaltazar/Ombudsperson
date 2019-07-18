<template>
    <div>
        
        <form @submit.prevent = "sendComplaint()">
            <h3>Formato de expediente único</h3>
            <div class="card">
                <div class="card-header bg-info text-white ">
                    Información personal
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <h5 class="col-2"><span class="badge badge-secondary badge-pill">Nombre</span></h5>
                        <div class="col-8">
                            <v-select v-if="!payload && session('role') == 1" :options = "users" label = "name" v-model = "complaint.creator"></v-select>
                            <span v-else >{{user.name}}</span>
                        </div>
                        <template v-if ="payload">
                            <h5 class="col-1"><span class="badge badge-secondary badge-pill">Edad</span></h5>
                            <span class="col-1">{{user.age}}</span>
                        </template>
                    </div>
                    <template v-if ="payload">
                        <div  class="form-group row">
                            <h5 class="col-2"><span class="badge badge-secondary badge-pill">Área</span></h5>
                            <span class="col-10">{{user.area}}</span>
                        </div>
                        <div class="form-group row">
                            <h5 class="col-2"><span class="badge badge-secondary badge-pill">Puesto</span></h5>
                            <span class="col-10">{{user.position}}</span>
                        </div>
                        <div class="form-group row">
                            <h5 class="col-2"><span class="badge badge-secondary badge-pill">Jefe</span></h5>
                            <span class="col-10">{{user.boss_name}}, {{user.boss_title}}</span>
                        </div>
                    </template>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-dark text-white">
                    Datos iniciales de la denuncia
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <span class="col-2">Teléfono de contacto</span>
                        <input type="text" class="col-4 form-control" v-model = "complaint.contact_phone" placeholder="Teléfono" required>
                        <span class="col-2">Horario disponible</span>
                        <input type="text" class="col-4 form-control" v-model = "complaint.contact_available" placeholder="Lun-Vie 9:00-2:00" required>
                    </div>
                    <div class="form-group">
                        <label for="">Motivo de la denuncia o queja</label>
                        <textarea class="form-control" rows = "5" placeholder="Descripción general" v-model ="complaint.description" required></textarea>
                    </div>
                </div>
                <div class="card-footer" v-if="stillModify">
                    <button ref = "btnSend" class="btn btn-success btn-lg float-right"><i class="fa fa-send"></i> Enviar</button>
                </div>
            </div>

        </form>

        <div class="card" v-if="viewRevision">
            <div class="card-header bg-success text-white">
                Revisión de la denuncia
            </div>
            <div class="card-body">
                <form @submit.prevent = "sendReview()">
                    <div class="form-group">
                        <label for="">Categoría</label>
                        <select class="form-control" v-model ="complaint.type_id" required>
                            <option v-for="type in types" :key="type.id" :value="type.id"> {{type.name}} </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Fecha y hora de entrevista inicial</label>
                        <div class="row">
                            <div class="col-4">
                                <input type="date" class="form-control" v-model="review.interview.date" required>
                            </div>
                            <div class="col-4">
                                <input type="time" class="form-control" v-model="review.interview.time" required>
                            </div>
                        </div>
                    </div>
                    <p>
                        <button ref="btnReview" v-if="session('role') == 1 && complaint.status_id < 3" class="btn btn-warning btn-lg"><i class="fa fa-check"></i> Aceptar denuncia</button>
                        <button  v-if="currentStatus > 1" @click.prevent = "open" class="btn btn-info btn-lg float-right"><i class="fa fa-external-link"></i> Abrir caso</button>
                    </p>
                </form>    
            </div>
        
        </div>
    </div>
</template>

<script>
import {eventBus} from '../../main'
import moment from 'moment'

export default {
    props: ['payload'],
    data() {
        return {
            user: {}, 
            complaint: {
                creator: ''
            }, 
            users: [], 
            types: [],
            review:{
                type: '',
                procedure: {
                    type: '', 
                    description: ''
                }, 
                interview: {}
            }
        }
    }, 
    computed: {
        stillModify() {
            return (!this.payload || (this.session('id') == this.creator && this.complaint.status.id == 1));
        }, 
        creator() {
            return this.complaint.author ? this.complaint.author.id : "";   
        }, 
        currentStatus() {
            return this.complaint.status ? this.complaint.status.id : "";   
        }, 
        viewRevision() {
            return (this.payload && (this.session('role') == 1 || this.currentStatus > 1));
        }
    },
    methods: {
        getUser(id) {
            this.$http.get('users/' + id).then(
                response => {
                    this.user = response.data.user;
                }, 
                error => {
                    this.Err(error);
                }
            )
        },
        sendComplaint() {
            this.loadButton(this.$refs.btnSend);
            if(this.payload){
                this.$http.put('complaints/' + this.complaint.id, this.complaint).then(
                    response => {
                        this.OK("Denuncia enviada");
                        $('.modal').modal('hide');
                        eventBus.$emit('ComplaintLoad');
                    }, 
                    error => {
                        this.Err(error);
                    }
                )
            }else{
                this.$http.post('complaints', this.complaint).then(
                    response => {
                        this.OK("Denuncia enviada");
                        $('.modal').modal('hide');
                        eventBus.$emit('ComplaintLoad');
                    }, 
                    error => {
                        this.Err(error);
                    }
                )
            }
            
        }, 
        sendReview(){
            this.loadButton(this.$refs.btnReview);
            this.review.type = this.complaint.type_id;
            this.$http.post('complaints/review/' + this.complaint.id, this.review).then(
                response => {
                    this.OK("Denuncia revisada");
                    $('.modal').modal('hide');
                    eventBus.$emit('ComplaintLoad');
                }, 
                error => {
                    this.Err(error);
                }
            )
        } , 
        open() {
            $('.modal').modal('hide');
            this.$router.push("/complaints/open/" + this.complaint.id);
        } 
    },
    created() {
        if(this.payload){
            this.$http.get('complaints/' + this.payload).then(
                response => {
                    this.complaint = response.data.complaint;
                    this.user = response.data.complaint.author;
                    this.types = response.data.types;
                    if(response.data.complaint.tracings.length){
                        this.review.interview.date = moment(response.data.complaint.tracings[0].meeting_date).format('YYYY-MM-DD');
                        this.review.interview.time = moment(response.data.complaint.tracings[0].meeting_date).format('HH:mm');
                    }
                }, 
                error => {
                    this.Err(error);
                }
            )
        }else{
            this.$http.get('users/search').then(
                response => {
                    this.users = response.data.users;
                }, 
                error => {
                    this.Err(error);
                }
            )
            this.getUser(this.session('id'));
        }
    }, 
    watch: {
        user() {
            this.complaint.creator = this.user;
        }
    }
}
</script>
