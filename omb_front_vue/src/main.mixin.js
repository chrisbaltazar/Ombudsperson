export const mix = {
    data(){
        return {
            modalComponent: '', 
            modalTitle: '', 
            modalData: '',
            modalClass: '', 
            tableButtons: []
        }
    },
    methods: {
        OK(msg){
            this.$swal({
                title: "OK", 
                html: msg, 
                type: 'success' 
            });
        },
        Ask(msg, fn){
            this.$swal({
                title: 'Confirme',
                html: msg, 
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancelar'
              }).then((result) => {
                if (result.value) {
                  fn();
                }
              })
        },
        Err(error){
            this.onReady();
            this.$Progress.fail();
            let e = new Array(); 
            if(error.status && error.status == 401){
                console.log('unauthorized');
                location.href = "#/login/error";
            }else if(error.data && error.data.errors) {
                for(let x in error.data.errors){
                    e.push( error.data.errors[x] );
                    // console.log(error.data.errors[x]);
                }
            }else if(error.data && error.data.message){
                e.push( error.data.message );
            }else{
                e.push( error );
            }
            this.$swal({
                title: "Error", 
                html: e.join('<br>'), 
                type: 'error' 
            });
        }, 
        Modal() {
            $('.modal').modal('show').on('hidden.bs.modal', () => {
               this.modalComponent = ""; 
            });
        }, 
        session(key){
            let token = null;
            if(token = JSON.parse(localStorage.getItem('app-token'))){
                return (key ? token.data[key] : token.hash);
            }
            return false;
        }, 
        makeDataTable(selector){
            $(selector).DataTable({
                retrieve: true,
                dom: "Bfrtip",
                buttons: this.tableButtons,
                "pageLength": 50,
                "autoWidth": false,
                "language": {
                  "sProcessing":     "Procesando...",
                  "sLengthMenu":     "Mostrar _MENU_ registros",
                  "sZeroRecords":    "No se encontraron resultados",
                  "sEmptyTable":     "Ningún registro encontrado",
                  "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                  "sInfoEmpty":      "0 registros",
                  "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                  "sInfoPostFix":    "",
                  "sSearch":         "Filtrar:",
                  "sUrl":            "",
                  "sInfoThousands":  ",",
                  "sLoadingRecords": "Cargando...",
                  "oPaginate": {
                      "sFirst":    "Primero",
                      "sLast":     "Último",
                      "sNext":     "Siguiente",
                      "sPrevious": "Anterior"
                  },
                  "oAria": {
                      "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                      "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                  }
              }
          });
        }, 
        getFormData(obj){
            const formData = new FormData();
            Object.keys(obj).forEach(key => {
                //   console.log(key + " => " + obj[key]);
                if(Array.isArray(obj[key])){
                    obj[key].forEach((item, i) => { 
                        if(item instanceof File){
                            // console.log("FILE");
                            formData.append(key + '[' + i +']', item ); 
                        // }else if(typeof item == 'object'){
                        //     // console.log("OBJ");
                        //     formData.append(key + '[' + i +']', JSON.stringify(item) ); 
                        }else{
                            // console.log("PLAIN");
                            formData.append(key + '[' + i +']', item ); 
                        }
                    });
                }else{
                    formData.append(key, obj[key]);
                }
            });
            return formData;
        }, 
        loadButton(obj){
            const icon = $(obj).find('i').attr('class');
            $(obj).html("<i class = 'fa fa-spinner fa-spin' action = '" + icon + "'></i> " + $(obj).text());
            $(obj).attr('disabled', 'disabled');
            $(obj).addClass('disabled');
        }, 
        onReady() {
            const icon = $('.fa-spin').attr('action');
            $('.fa-spin').parents('button').removeAttr('disabled').removeClass('disabled');
            $('.fa-spin').parents('a').removeAttr('disabled').removeClass('disabled');
            if(icon)
                $('.fa-spin').parent().html("<i class = '" + icon + "'></i> " + $('.fa-spin').parent().text());
            $('.fa-spin').remove();
        }
    }
}
