<template>
    <div class="modal fade in" tabindex="-1" role="dialog">
        <div :class="cls" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{title}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <component :is ="load" :payload = "payload"></component>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
  props: ["load", "title", "payload", "size"], 
  computed:{
      cls() {
          return "modal-dialog " + (this.size == "large" ? "modal-lg " : "") + (this.size == "small" ? "modal-sm " : "");
      }
  }
};
</script>

