<template>
    <div class="col-12">
        <app-title>Reporte general de denuncias</app-title>
        <report-table :report = "report"></report-table>
    </div>
</template>

<script>
import ReportTable from './GeneralTable.component.vue'

export default {
    components: {ReportTable}, 
    data() {
        return {
            report: []
        }
    },
    created() {
        this.$http.get('complaints/report').then(
            response => {
                this.report = response.data.report;
            }, 
            error => {
                this.Err(error);
            }
        )
    }
}
</script>
