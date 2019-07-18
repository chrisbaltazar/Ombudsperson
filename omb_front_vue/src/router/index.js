import Vue from 'vue'
import Router from 'vue-router'

import Login from '../components/Login.component.vue';
import Home from '../components/Home.component.vue';
import Users from '../components/Users/Users.component.vue';
import Questions from '../components/questions/Questions.component.vue';
import Types from '../components/types/Types.component.vue';
import Complaints from '../components/complaints/Complaints.component.vue';
import ComplaintView from '../components/complaints/ComplaintView.component.vue';
import GeneralReport from '../components/reports/General.component.vue'

Vue.use(Router)

const router = new Router({
  routes: [
    {path: '', redirect: '/home'}, 
    {path: '/login/:usermail', component: Login, name: 'login', props: true}, 
    {path: '/home', component: Home}, 
    {path: '/users', component: Users}, 
    {path: '/questions', component: Questions}, 
    {path: '/types', component: Types}, 
    {path: '/complaints', component: Complaints}, 
    {path: '/complaints/open/:id', component: ComplaintView, props: true}, 
    {path: '/report/general', component: GeneralReport }, 
    {path: '*', redirect: '/login/error'}
    // {path: '*', redirect: to => {
    //   // console.log(to);
    //   if(to.params){
    //       let param  = (to.params[0].toString().replace('/', '') || "undefined");
    //       let route =  "/login/" + param;
    //       console.log("redirect " + route);
    //       return route;
    //   }   
    // }}
  ]
});

router.beforeEach((to, from, next) => {
  if(to.name == 'login' || localStorage.getItem('app-token')){
      next();
  }else{
      console.log(to);
      next('/login/error');
  }
  
});

export { router };