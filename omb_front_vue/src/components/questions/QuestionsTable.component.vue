<template>
 <div class="col-12">

 
    <table class="table table-striped table-hover" id ="tbl-questions">
        <thead class="thead-dark">
            <tr>
                <th width = "30">#</th>
                <th>Pregunta</th>
                <th width = "100">Tipo</th>
                <th width = "100"></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(question, index) in questions" :key = "question.id">
                <td>{{index+1}}</td>
                <td>{{question.body}}</td>
                <td>
                    <h5>
                        <span class="badge badge-pill badge-secondary">
                            {{question.type | type}}
                        </span>
                    </h5>
                </td>
                <td>
                    <button class="btn btn-warning btn-sm"><i class="fa fa-edit" @click="$emit('QuestionEdit', question)"></i> </button>
                    <button class="btn btn-danger btn-sm"><i class="fa fa-trash" @click="$emit('QuestionDelete', question)"></i> </button>
                </td>
            </tr>
        </tbody>
    </table>

</div>
</template>

<script>
export default {
    props: ['questions'], 
    filters: {
        type(value) {
            return value == 'OPEN' ? 'ABIERTA' : 'CERRADA';
        }
    },
    updated() {
        this.makeDataTable('#tbl-questions');
    }
}
</script>
