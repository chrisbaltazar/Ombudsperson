<template>
    <div class="col-9 border border-success rounded p-3">
        <div class="form-group row">
            <h5 class="col-3"><span class="badge badge-secondary badge-pill">Nombre</span></h5>
            <div class="col-7">
                <span >{{data.author.name}}</span>
            </div>
            <h5 class="col-1"><span class="badge badge-secondary badge-pill">Edad</span></h5>
            <span class="col-1">{{data.author.age}}</span>
        </div>
        
        <div  class="form-group row">
            <h5 class="col-3"><span class="badge badge-secondary badge-pill">Área</span></h5>
            <span class="col-9">{{data.author.area}}</span>
        </div>
        <div class="form-group row">
            <h5 class="col-3"><span class="badge badge-secondary badge-pill">Puesto</span></h5>
            <span class="col-9">{{data.author.position}}</span>
        </div>
        <div class="form-group row">
            <h5 class="col-3"><span class="badge badge-secondary badge-pill">Jefe</span></h5>
            <span class="col-9">{{data.author.boss_name}}, {{data.author.boss_title}}</span>
        </div>
        
        <div class="form-group row">
            <h5 class="col-3"><span class="badge badge-danger badge-pill">Clasificación</span></h5>
            <span class="col-9">{{data.type.name}}</span>
        </div>
    </div>
</template>
<script>
export default {
    props: ['data']
}
</script>
