<template>
    <div class="col-8 offset-2">
        <h3>Cierre de denuncia</h3>
        <form @submit.prevent = "closeComplaint">
            <div class="form-group">
                <label for="">Procedimiento iniciado</label>
                <select class="form-control" :class ="{'form-control-plaintext': !stillModify}" v-model ="close.procedure.type" :disabled ="!stillModify" required >
                    <option value = "INFORMAL">INFORMAL</option>
                    <option value = "ADMINISTRATIVO">ADMINISTRATIVO</option>
                    <option value = "LABORAL">LABORAL</option>
                    <option value = "PENAL">PENAL</option>
                </select>
            </div>
            <div class="form-group">
                <label for="">Descripción</label>
                <textarea class="form-control" :class ="{'form-control-plaintext': !stillModify}" rows = "5" v-model = "close.procedure.description" :disabled ="!stillModify"  placeholder="Descripción del procedimiento en su caso"></textarea>
            </div>
            <div class="form-group">
                <label for="">Tipo de resolución</label>
                <select class="form-control" :class ="{'form-control-plaintext': !stillModify}" v-model ="close.resolution_id" :disabled ="!stillModify" required>
                    <option v-for="res in resolutions" :key="res.id" :value = "res.id">{{ res.name }}</option>
                </select>
            </div>
            <p><button v-if="stillModify" ref="btnClose" class="btn btn-danger btn-lg float-right"> <i class="fa fa-check-square-o"></i> Cerrar denuncia</button></p>
        </form>
    </div>
</template>

<script>
export default {
    props: ['data'],
    data(){
        return {
            close: {
                procedure: {}
            }, 
            resolutions: []
        }
    }, 
    computed: {
        stillModify() {
          return (this.session('role') == 1 && this.data.status.id < 5);
        }
    },
    methods: {
        closeComplaint() {
            this.loadButton(this.$refs.btnClose);
            this.$http.post('complaints/close/' + this.data.id, this.close).then(
                response => {
                    this.onReady();
                    this.OK("Denuncia cerrada");
                    this.$emit('close', response.data.complaint);
                }, 
                error => {
                    this.Err(error);
                }
            )
        }
    }, 
    created() {
        if(this.data.procedure){
            this.close.procedure = this.data.procedure;
            this.close.resolution_id = this.data.resolution_id;
        }

        this.$http.get('complaints/resolution').then(
            response => {
                this.resolutions = response.data.resolutions;
            }
        )
    }
}
</script>
