<template>
 <div class="col-12">

 
    <table class="table table-striped table-condensed table-hover" id ="tbl-comp">
        <thead class="thead-dark">
            <tr>
                <th width = "30">#</th>
                <th>Fecha</th>
                <th width = "80">Folio</th>
                <th>Descripción</th>
                <th>Remitente</th>
                <th>Estatus</th>
                <th width = "80"></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(comp, index) in complaint_list" :key = "comp.id">
                <td>{{index+1}}</td>
                <td>{{comp.created_at | dateFormat }}</td>
                <td>{{comp.folio}} / {{comp.created_at | year}}</td>
                <td>{{comp.description | text }}</td>
                <td>{{comp.author.name}}</td>
                <td>
                    <h5>
                        <span class="badge badge-primary" :class="getClass(comp)">
                            {{comp.status.name}}
                        </span>
                    </h5>
                </td>
                <td class="text-right">
                    <button class="btn btn-primary btn-sm" @click = "$emit('edit', comp)" title = "Abrir"><i class="fa fa-folder-open" ></i> </button>
                    <!-- <button class="btn btn-info btn-sm" v-if="comp.status.id > 1" @click = "$emit('continue', comp)" title = "Continuar"><i class="fa fa-edit" ></i> </button> -->
                    <button class="btn btn-danger btn-sm" v-if="comp.author.id == session('id')" @click = "$emit('delete', comp)"><i class="fa fa-trash" ></i> </button>
                </td>
            </tr>
        </tbody>
    </table>

</div>
</template>

<script>
export default {
    props: ['complaint_list'], 
    methods: {
        getClass(comp){
            return "";
        }
    },
    filters: {

    },
    updated() {
        this.makeDataTable('#tbl-comp');
    }
}
</script>
