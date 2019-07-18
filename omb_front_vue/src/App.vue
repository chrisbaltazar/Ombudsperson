<template>
  <div id="app">
    <!-- <img src="./assets/logo.png"> -->
   <app-menu v-if="session()"></app-menu>
    <div class="container">
      <router-view></router-view>
      <vue-progress-bar></vue-progress-bar>
    </div>
  </div>
</template>

<script>
import AppMenu from './components/Menu.component.vue'

export default {
  components: {AppMenu},
  name: 'App'
}
</script>

<style>

</style>
