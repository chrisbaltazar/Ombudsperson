<template>
    <div id="login" class="row">
        <div v-if="loading" class="form-inline">
            <i class="fa fa-cog fa-spin fa-spinner fa-2x"></i>  
            <h1> Cargando... </h1> 
        </div>
        <div class="alert alert-danger col-8 offset-2 p-5 mt-5 text-center" v-if="!loading && !logged">
            <h3>Hubo un error cargando su sesión</h3>
        </div>
    </div>
</template>

<script>
export default {
  props: ['usermail'],
  data() {
    return {
      loading: true,
      logged: false
    };
  },
  created() {
    console.log("login");
    localStorage.removeItem("app-token");
    console.log(this.usermail);
    // const usermail = this.$route.params.user;
    if (this.usermail && this.usermail != "error") {
      this.$http.post("login", { email: this.usermail }).then(
        response => {
          this.logged = true;
          this.loading = false;
          const json = {data: response.data.token,  hash: response.data.hash };
          localStorage.setItem("app-token", JSON.stringify(json));
          location.href = "/";
        },
        error => {
          this.loading = false;
          this.Err(error);
        }
      );
    }else{
        this.loading = false;
    }
  }
};
</script>

