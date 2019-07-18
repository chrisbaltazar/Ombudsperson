// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import { router } from './router'
import VueResource from 'vue-resource'
import VueSweetalert2 from 'vue-sweetalert2'
import moment from 'moment'
import VueProgressBar from 'vue-progressbar'
	
import { mix } from './main.mixin'
import { API } from './config'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap/dist/js/bootstrap.js'
import 'jszip/dist/jszip'
import pdfmake from 'pdfmake/build/pdfmake'
import vfs_fonts from 'pdfmake/build/vfs_fonts'
pdfmake.vfs = vfs_fonts.pdfMake.vfs;
import 'datatables.net-dt/css/jquery.dataTables.css'
import 'datatables.net/js/jquery.dataTables'
import 'datatables.net-buttons/js/dataTables.buttons'
import 'datatables.net-buttons/js/buttons.html5'
import 'datatables.net-buttons-dt/css/buttons.dataTables.css'
import 'font-awesome/css/font-awesome.min.css'
import "vue-simple-calendar/dist/static/css/default.css"
import "vue-simple-calendar/dist/static/css/holidays-us.css"

Vue.use(VueResource)
Vue.use(VueSweetalert2)
Vue.use(VueProgressBar, {
  color: 'rgb(143, 255, 199)',
  failedColor: 'red',
  height: '4px'
})

// Filters 

Vue.filter('dateFormat', (value, onlyDate) => { 
  if (value) {
       if(onlyDate)
           return moment(String(value)).format('DD/MM/YYYY');
       else
           return moment(String(value)).format('DD/MM/YYYY HH:mm');
  }
})

Vue.filter('timeFormat', (value) => {
  if (value) {
       return moment(String(value)).format('HH:mm');
  }
})

Vue.filter('year', (value) => {
  if (value) {
       return moment(String(value)).year().toString();
  }
})

Vue.filter('text', (value, max) => {
  if(value){
    let len = max ? max : 100;
    return value.substr(0, len) + (value.length > len ? "..." : "");
  }
})

// Vue Resource 
Vue.http.options.root = "//" + window.location.hostname + (window.location.hostname == "localhost"?":8000":"") +  "/api/";
Vue.http.interceptors.push(function(request) {
  this.$Progress.start();
  const token = JSON.parse(localStorage.getItem('app-token') || null);
  const auth = 'Bearer ' + (token ? token.hash : "");
  request.headers.set('Authorization', auth);

  return function(response) {
    this.$Progress.finish();
  };
});

// Globals 
import Modal from './components/modal.component.vue';
import Title from './components/Title.component.vue';
import AppLabel from './components/Label.component.vue';
import UserForm from './components/users/UserForm.component.vue'
import QuestionForm from './components/questions/QuestionForm.component.vue'
import TypeForm from './components/types/TypeForm.component.vue'
import ComplaintForm from './components/complaints/ComplaintForm.component.vue'
import vSelect from 'vue-select';
import CalendarView from "vue-simple-calendar"
import ComplaintEvent from './components/complaints/ComplaintEvent.component.vue'
import ComplaintHistory from './components/complaints/ComplaintHistory.component.vue'

Vue.component('app-modal', Modal);
Vue.component('app-title', Title);
Vue.component('app-label', AppLabel);
Vue.component('v-select', vSelect);
Vue.component('calendar-view', CalendarView);
Vue.component('user-form', UserForm);
Vue.component('question-form', QuestionForm);
Vue.component('type-form', TypeForm);
Vue.component('complaint-form', ComplaintForm);
Vue.component('complaint-event', ComplaintEvent);
Vue.component('complaint-history', ComplaintHistory);

// Mixins 
Vue.mixin(mix)

Vue.config.productionTip = false

export const eventBus = new Vue();

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>'
})
