<template>
    <div class="col-12">
        <div class="col-10 offset-1">
            <h3>Expediente digital</h3>
            <form @submit.prevent = "saveFile" v-if="stillModify">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        Cargar nuevo archivo
                    </div>
                    <div class="card-body">
                        
                        <!-- <div class="form-group">
                            <app-label>Fuente</app-label>
                        </div>
                        <div class="form-group form-inline">
                            <label for="">
                                ARCHIVO LOCAL 
                                <input type="radio" class="mx-2" v-model="newFile.source" value ="LOCAL">
                            </label>
                            <label for="">
                                DESDE CORRESPONDENCIA
                                <input type="radio" class="mx-2" v-model="newFile.source" value ="CORRES">
                            </label>
                        </div> -->
                        <template v-if="newFile.source == 'LOCAL'">
                            <div class="form-group">
                                <app-label>Nombre del archivo</app-label>
                                <input type="text" class="form-control" v-model="newFile.document" required>
                            </div>
                            <div class="form-group">
                                <app-label>Seleccionar archivo</app-label>
                                <input type="file" class="form-control" @change="addFile($event)" required>
                            </div>
                        </template>
                        
                    </div>
                    <div class="card-footer">
                        <button ref = "btnUpload" class="btn btn-primary btn-lg float-right"> <i class="fa fa-upload"></i> Guardar</button>
                    </div>
                </div>
            </form>

            <div class="card">
                <div class="card-header bg-success text-white">
                    Lista de archivos
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead class="thead-light">
                            <th>#</th>
                            <th>Archivo</th>
                            <th width ="30">Descargar</th>
                            <th width ="30">Eliminar</th>
                        </thead>
                        <tr v-for="(file, index) in files" :key="file.id"> 
                            <td>{{ index+1 }}</td>
                            <td>{{file.document}}</td>
                            <td class="text-center"
                                ><a target ="_blank" :href="'//' + apiRoot + 'file/download/' + file.path.replace('documents/', '')"> <i class="fa fa-file fa-2x"></i> </a> 
                            </td>
                            <td class="text-center"> 
                               <button v-if="stillModify" class="btn btn-danger btn-sm" @click="deleteFile(file)"><i class="fa fa-trash" ></i></button>  
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
    </div>
</template>

<script>
import { API } from '../../config'

export default {
    props: ['data'],
    data(){
        return {
            apiRoot: API, 
            newFile: {
                complaint_id: this.data.id,
                source: 'LOCAL'
            }, 
            files: []
        }
    }, 
    computed: {
        stillModify() {
          return (this.session('role') == 1 && this.data.status.id < 5);
        }
    },
    methods: {
        addFile(event){
            this.newFile.file = event.target.files[0];
        }, 
        saveFile(){
            const formData = this.getFormData(this.newFile);
            this.loadButton(this.$refs.btnUpload);
            this.$http.post('file', formData).then(
                response => {
                    this.onReady();
                    this.OK("Archivo guardado");
                    this.files.unshift(response.data.file);
                    this.newFile.document = "";
                    this.newFile.file = null;
                }, 
                error => {
                    this.Err(error);
                }
            )
        }, 
        deleteFile(file) {
            this.Ask("¿Desea eliminar este archivo?", () => {
                this.$http.delete('file/' + file.id).then(
                    response => {
                        let index = this.files.map(f => { return f.id }).indexOf(file.id);
                        this.files.splice(index, 1);
                    },  
                    error => {
                        this.Err(error);
                    }
                )
            });
        }
    }, 
    created() {
        this.$http.get('file/' + this.data.id).then(
            response => {
                this.files = response.data.files;
            }, 
            error => {
                this.Err(error);
            }
        )
    }
}
</script>
