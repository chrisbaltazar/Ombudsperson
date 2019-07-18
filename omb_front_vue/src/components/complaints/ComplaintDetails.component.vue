<template>
    <div class="col-12">
        <form @submit.prevent="saveStatement()">
            <div class="card">
                <div class="card-header bg-info text-white">
                    Datos de la persona contra se presenta la queja
                </div>
                <div class="card-body">
                    <div class="form-group row">
                       <app-label class="col-1">Nombre</app-label>
                       <div class="col-5">
                           <v-select :options = "users" label = "name" v-model ="complement.user" ></v-select>
                       </div>
                       <app-label class="col-1">Correo</app-label>
                       <span class="col-5">{{accused.email}}</span>
                    </div>
                    <div class="form-group row">
                       <app-label class="col-1">Área</app-label>
                       <span class="col-5">{{accused.area}}</span>
                       <app-label class="col-1">Puesto</app-label>
                       <span class="col-5">{{accused.position}}</span>
                    </div>
                    <div class="form-group row">
                       <app-label class="col-3">Relación laboral existente</app-label>
                       <input type="text" class="form-control col-8" v-model = "complement.accused_relation" placeholder="Describa la relación con la persona">
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-dark text-white">
                    Declaración completa
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        A continuación, le pedimos que escriba con tinta y letra clara, marcando el inciso correspondiente o en su caso, conteste las preguntas.
                    </div>
                    <div class="form-group">
                       <app-label>¿De qué forma se manifestó el hecho sobre el que presenta su queja?</app-label>
                       <textarea class="form-control" v-model = "complement.facts" rows ="3" required></textarea>
                    </div>
                    <div class="form-group row">
                       <app-label class="col-4">¿Con qué frecuencia ha ocurrido?</app-label>
                       <div class="form-inline col-8">
                           <label v-for="frecuency in frecuencies" :key="frecuency.key" class="mx-3 text-uppercase">
                               <input type="radio" class="m-1" v-model ="complement.frequency" :value="frecuency.key">
                               {{frecuency.value}}
                           </label>
                       </div>
                    </div>
                    <div class="form-group row" v-if="complement.frequency == 'ONCE'">
                       <app-label class="col-1">Fecha</app-label>
                       <input type="date" class="col-2 form-control" v-model="complement.date_since" required>
                       <app-label class="col-1">Hora</app-label>
                       <input type="time" class="col-2 form-control" v-model="complement.time" required>
                       <app-label class="col-1">Lugar</app-label>
                       <input type="text" class="col-5 form-control" v-model="complement.place" placeholder="Lugar" required>
                    </div>
                    <template v-else-if="complement.frequency">
                        <div class="form-group row" >
                            <app-label class="col-1">Desde</app-label>
                            <input type="date" class="col-3 form-control" v-model="complement.date_since" required>
                            <app-label class="col-1">Hasta</app-label>
                            <input type="date" class="col-3 form-control" v-model="complement.date_until" required>
                        </div>
                        <div class="form-group">
                            <app-label>Lugares de ocurrencia</app-label>
                            <textarea class="form-control" v-model="complement.place" placeholder="Describa los lugares" required></textarea>
                        </div>
                    </template>
                    <div class="form-group">
                        <app-label >La actitud de la persona que le hostigó, acosó o violentó fue</app-label>
                        <div class="form-inline">
                           <label v-for="attitude in attitudes" :key="attitude.key" class="mx-3 text-uppercase">
                               <input type="radio" class="m-1" v-model ="complement.attitude" :value="attitude.key">
                               {{attitude.value}}
                           </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <app-label >La reacción inmediata de usted ante la(s) conducta(s) de ésa persona fue:</app-label>
                        <div class="form-inline">
                           <label v-for="reaction in reactions" :key="reaction.key" class="mx-3 text-uppercase">
                               <input type="radio" class="m-1" v-model ="complement.reaction" :value="reaction.key">
                               {{reaction.value}}
                           </label>
                           <label class="mx-3 text-uppercase">
                               <input type="radio" class="m-1" v-model ="complement.reaction" value="">
                               OTRO
                           </label>
                           <input type="text" class="col-7 form-control " v-model="complement.reaction" v-if="otherValue('reaction')" placeholder="Explique" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <app-label>Mencione si su caso es aislado o conoce de otras personas que hayan sido objeto de esos mismos hechos por parte de la persona agresora</app-label>
                        <textarea class="form-control" v-model="complement.other_cases" placeholder="Otros casos similares"></textarea>
                    </div>
                    <div class="form-group">
                        <app-label >Señale si existieron testigos cuando acontecieron los hechos de la presente queja:</app-label>
                        <textarea class="form-control" v-model="complement.witnesses" placeholder="Datos de los testigos, en su caso"></textarea>
                    </div>
                    <div class="form-group">
                        <app-label >¿Qué cambios en su situación laboral se dieron a partir de los hechos?</app-label>
                        <div class="form-inline">
                           <label v-for="change in changes" :key="change.key" class="mx-3 text-uppercase">
                               <input type="radio" class="m-1" v-model ="complement.made_changes" :value="change.key">
                               {{change.value}}
                           </label>
                           <label for="" class="mx-3 text-uppercase">
                               <input type="radio" class="m-1" v-model ="complement.made_changes" value="">
                               Otro
                           </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="text" class=" form-control " v-model="complement.made_changes" v-if="otherValue('change')" placeholder="Explique" required>
                    </div>
                    <div class="form-group">
                        <app-label >¿De qué forma le afectaron los hechos de los que se queja?</app-label>
                        <div class="form-inline">
                           <label v-for="affectation in affectations" :key="affectation.key" class="mx-3 text-uppercase">
                               <input type="radio" class="m-1" v-model ="complement.affectation" :value="affectation.key">
                               {{affectation.value}}
                           </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" v-model="complement.affect_info" placeholder="Describa el tipo de afectación sufrida"></textarea>
                    </div>
                    <div class="form-group">
                        <app-label>¿Considera que el acoso u hostigamiento que sufrió le afectará a largo plazo?</app-label>
                        <textarea class="form-control" v-model="complement.long_term_considerations" placeholder="Describa las posibles consecuencias"></textarea>
                    </div>
                    <div class="form-group">
                        <app-label>¿Cree necesario acudir con una/un experta/o para que le apoye a superar los efectos psicológicos causados?</app-label>
                        <textarea class="form-control" v-model="complement.psy_support" placeholder="Describa en su caso"></textarea>
                    </div>
                    <div class="form-group">
                        <app-label>¿Cree necesario recibir atención médica?</app-label>
                        <textarea class="form-control" v-model="complement.medical_support" placeholder="Describa en su caso"></textarea>
                    </div>
                    <div class="form-group">
                        <app-label>¿Comunicó estos hechos a su superior jerárquico?</app-label>
                        <textarea class="form-control" v-model="complement.boss_knowledge" placeholder="Describa en su caso"></textarea>
                    </div>
                    <div class="form-group">
                        <app-label>¿Cómo piensa que se puede resolver este problema?</app-label>
                        <textarea class="form-control" v-model="complement.solution" placeholder="Describa en sus palabras la posible solución"></textarea>
                    </div>
                    <div class="form-group">
                        <app-label>¿Para dar un fundamento a los hechos se cuenta con algún tipo de evidencia?</app-label>
                        <textarea class="form-control" v-model="complement.evidence" placeholder="Describa en su caso"></textarea>
                    </div>
                    <div class="form-group">
                        <app-label>Datos e información adicional que considere necesario aportar para el seguimiento de su queja:</app-label>
                        <textarea class="form-control" v-model="complement.aditional_info" placeholder="Describa en su caso"></textarea>
                    </div>

                </div>
                <div class="card-footer" v-if="data.status.id < 3">
                    <button ref ="btnStatement" class="btn btn-danger btn-lg float-right"> <i class="fa fa-pencil"></i> Guardar declaración</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    props: ['data'], 
    data() {
        return {
            users: [], 
            complement: {
                user: ''
            }, 
            frecuencies: [
                {key: 'ONCE', value: 'UNA SÓLA VÉZ'}, 
                {key: 'SEVERAL', value: 'VARIAS VECES'}, 
                {key: 'CONSTANT', value: 'CONTINUAMENTE'}, 
            ], 
            attitudes: [
                {key: 'OPEN', value: 'ABIERTA Y CLARA'}, 
                {key: 'THREAT', value: 'AMENAZANTE'}, 
                {key: 'SUBTLE', value: 'DISCRETA Y SUTIL'}, 
            ], 
            reactions: [
                {key: 'FACE', value: 'LO CONFRONTÉ'}, 
                {key: 'IGNORE', value: 'LO IGNORÉ'}, 
            ], 
            changes: [
                {key: 'SAME', value: 'Sigue igual'}, 
                {key: 'AWKWARD', value: 'Es tensa e incómoda'}, 
                {key: 'TRASFERED', value: 'Fui asignado/a a otra área'},
            ], 
            affectations: [
                {key: 'EMOTIONAL', value: 'Emocional'}, 
                {key: 'SOCIAL', value: 'Social'}, 
                {key: 'WORKING', value: 'Laboral'}, 
            ]
        }
    },
    computed: {
        accused() {
            return (this.complement.user);
        }
    },
    methods: {
       otherValue(obj){
           switch(obj){
               case 'reaction': 
                    return (this.reactions.map(r => { return r.key }).indexOf(this.complement.reaction) < 0);
               case 'change': 
                    return (this.changes.map(c => { return c.key }).indexOf(this.complement.made_changes) < 0);
               case 'affectation': 
                    return (this.affectations.map(a => { return a.key }).indexOf(this.complement.affectation) < 0);
           }
       }, 
       saveStatement() {
           this.loadButton(this.$refs.btnStatement);
           this.$http.post('complaints/statement/' + this.data.id, this.complement).then(
               response => {
                   this.onReady();
                   this.OK("Declaración guardada");
                   this.$emit('update', response.data.complaint);
               }, 
               error => {
                   this.Err(error);
               }
           )
       }
    },
    created() {
        this.$http.get('users/search').then(
            response => {
                this.users = response.data.users;
            },
            error => {
                this.Err(error);
            }
        )

        if(this.data.status.id > 2){
            this.complement = this.data; 
            this.complement.user = this.data.accused;
        }
    }
}
</script>

