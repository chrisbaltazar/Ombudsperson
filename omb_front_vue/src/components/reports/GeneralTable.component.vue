<template>
    <table class="table table-striped" id ="tbl-report">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Folio</th>
                <th>Clasif.</th>
                <th>Remitente</th>
                <th>Acusado</th>
                <th>Fecha</th>
                <th>Estatus</th>
                <th>Procedimiento</th>
                <th>Resolución</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(item, index) in report" :key="index">
                <td>{{ index+1 }}</td>
                <td>{{ item.folio }}/{{item.created_at | year}}</td>
                <td>{{ item.type.name }}</td>
                <td>{{ item.author.name }}</td>
                <td>{{ item.accused ? item.accused.name : "" }}</td>
                <td>{{ item.created_at | dateFormat(true) }}</td>
                <td>{{ item.status.name }}</td>
                <td>{{ item.procedure ? item.procedure.type : "" }}</td>
                <td>{{ item.resolution ? item.resolution.name : "" }}</td>
            </tr>
        </tbody>
    </table>
</template>


<script>
export default {
  props: ["report"],
  updated() {
    this.tableButtons = [
      {
        extend: "pdf",
        text: "Exportar PDF",
        filename: "Reporte",
        title: "Reporte Denuncias", 
        orientation: "landscape"
      }
    ];
    this.makeDataTable('#tbl-report');
  }
};
</script>
