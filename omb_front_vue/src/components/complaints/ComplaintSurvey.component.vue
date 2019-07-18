<template>
    <div class="col-12" v-if="survey">
        <div class="alert alert-info text-center">Por favor responda las siguientes preguntas sobre su nivel de satisfacción en el proceso</div>
        <form @submit.prevent="saveSurvey">
            <table class="table table-striped table-hover">
                <template v-for="(question, index) in survey" >
                    <tr class="table-dark">
                        <td class="row">
                            <app-label class="col-1">{{ index + 1 }}</app-label>
                            <span class="col-11">{{question.body}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <textarea class="form-control" v-if="question.type == 'OPEN'" v-model="response[question.id]" placeholder="Respuesta" required></textarea>
                            <div class="form-inline row" v-else>
                                <label class="col" v-for="(option, i) in options" :key="i">
                                    {{ option }}
                                    <input type="radio" class="mx-2" v-model="response[question.id]" :value="i" >
                                </label>
                            </div>
                        </td>
                    </tr>
                </template>
            </table>
            <p><button ref = "btnSurvey" class="btn btn-success btn-lg float-right mb-3"> <i class="fa fa-edit"></i> Guardar</button></p>
        </form>
    </div>
    <div class="col-6 offset-3 text-center" v-else>
        <div class="alert alert-dark"><h5>Ya ha sido respondido esta encuesta. Gracias</h5></div>
    </div>
</template>

<script>
export default {
    props: ['data'], 
    data() {
        return {
            survey: [], 
            options: [
                'Muy malo', 
                'Malo', 
                'Regular', 
                'Bueno', 
                'Muy bueno'
            ], 
            response:[]
        }
        
    },
    computed: {
        questions() {
            return this.survey.map(q => { return q.id });
        },
        fullSurvey() {
            return (this.response.filter((val, index) => val === "" && this.questions.indexOf(index) > -1  ).length == 0);
        }
    },  
    methods: {
        saveSurvey() {
            if(this.fullSurvey){
                this.loadButton(this.$refs.btnSurvey);
                this.$http.post('survey/' + this.data.id, this.response).then(
                    resp => {
                        this.OK("Gracias!");
                        this.survey = null;
                    }, 
                    error => {
                        this.Err(error);
                    }
                )
            }else{
                this.Err("Favor de completar la encuesta");
            }
        }
    },
    created() { 
        this.$http.get('survey/' + this.data.id).then(
            response => {
                this.survey = response.data.survey;
                if(this.survey) {
                    this.survey.forEach(question => {
                        this.response[question.id] = "";
                    });
                    // this.response.forEach((item, index) => {
                    //     if(this.survey.map)
                    // })
                }
            }, 
            error => {
                this.Err(error);
            }
        )
    }
}
</script>
