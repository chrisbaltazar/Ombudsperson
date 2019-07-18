<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Ombudsperson STRC</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarColor01">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item ">
            <router-link tag = "a" class="nav-link" active-class = "active" to="/home">Inicio </router-link>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Denuncias
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <router-link class="dropdown-item" tag = "a" to="/complaints">Registro</router-link>
                </div>
            </li>
            <li class="nav-item dropdown" v-if="session('role') == 1">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Opciones
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <router-link class="dropdown-item" tag = "a" to="/users">Usuarios</router-link>
                    <router-link class="dropdown-item" tag = "a" to="/questions">Encuesta</router-link>
                    <router-link class="dropdown-item" tag = "a" to="/types">Tipos de denuncia</router-link>
                    <!-- <div class="dropdown-divider"></div>-->
                </div>
            </li>
            <li class="nav-item dropdown" v-if="session('role') == 1">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Reportes
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <router-link class="dropdown-item" tag = "a" to="/report/general">Reporte general</router-link>
                </div>
            </li>
        </ul>
    </div>
  </nav>
</template>
