
<template>
    <div class="row">
        <app-title>Usuarios del sistema</app-title>
        <app-modal :load = "modalComponent" :payload = "modalData" :title = "modalTitle" :size = "modalClass"></app-modal>

        <button class="btn btn-primary mb-3 float-right" @click="newUser()"><i class="fa fa-plus"></i> Nuevo usuario</button>

        <users-table :users = "users" @UserDelete = "deleteUser($event)"></users-table>
        
    </div>
</template>

<script>

import {eventBus} from '../../main'
import UsersTable from './UsersTable.component.vue'


export default {
    components: {UsersTable},
    data() {
        return {
            users: []
        }
    },
    methods: {
        loadUsers() {
            this.$http.get('users').then(
                response => {
                    this.users = response.data.users;
                }, 
                error => {
                    this.Err(error);
                }
            )
        }, 
        newUser() {
            this.modalComponent = "user-form";
            this.modalTitle = "Nuevo usuario";
            this.modalData = "";
            this.modalClass = "large";
            this.Modal();
        }, 
        deleteUser(user){
            this.Ask("¿Desea eliminar este usuario?", () => {
                this.$http.delete('users/' + user.id).then(
                    response => {
                        let index = this.users.map(user => { return user.id }).indexOf(user.id);
                        this.users.splice(index, 1);
                    }, 
                    error => {
                        this.Err(error);
                    }
                )
            });
        }
    },
    created() {
        this.loadUsers(); 

        eventBus.$on('UserSaved', () => { this.loadUsers() });
    }
}
</script>

