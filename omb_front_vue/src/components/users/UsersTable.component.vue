<template>
 <div class="col-12">

 
    <table class="table table-striped table-hover" id ="tbl-users">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Área</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(user, index) in users" :key = "user.id">
                <td>{{index+1}}</td>
                <td>{{user.name}}</td>
                <td>{{user.email}}</td>
                <td>{{user.area}}</td>
                <td>
                    <button class="btn btn-danger btn-sm"><i class="fa fa-trash" @click="$emit('UserDelete', user)"></i> </button>
                </td>
            </tr>
        </tbody>
    </table>

</div>
</template>

<script>
export default {
  props: ["users"],

  updated() {
    this.makeDataTable('#tbl-users');
  }
};
</script>
