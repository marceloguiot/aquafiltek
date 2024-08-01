<template>
  <div class="container mx-auto p-4">
    <div class="flex flex-wrap">
      <button
        v-for="(tab, index) in tabs"
        :key="index"
        @click="activeTab = index"
        :class="['px-4 py-2 m-1', activeTab === index ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700']"
        class="rounded-lg"
      >
        {{ tab }}
      </button>
    </div>
    <div class="mt-4">
      <div v-if="activeTab === 0">
        <CrearUsuario />
      </div>
      <div v-if="activeTab === 1">
        <ControlUsuarios />
      </div>
      <div v-if="activeTab === 2">
        <CambiarContrasenas />
      </div>
      <div v-if="activeTab === 3">
        <GruposPoblacionales />
      </div>
      <div v-if="activeTab === 4">
        <SubirClientes />
      </div>
      <div v-if="activeTab === 5">
        <VerClientesNuevos />
      </div>
    </div>
  </div>
  </template>
  
  <script setup>
  import CrearUsuario from './usuario/CrearUsuarios.vue';
  import ControlUsuarios from './usuario/ControlUsuarios.vue';
  import CambiarContrasenas from './usuario/CambiarContrasenas.vue';
  import GruposPoblacionales from './usuario/GruposPoblacionales.vue';
  import SubirClientes from './usuario/SubirClientes.vue';
  import VerClientesNuevos from './usuario/VerClientesNuevos.vue';
  import { ref } from 'vue';

  const tabs = [
  'Crear Usuario',
  'Control de usuarios',
  'Cambiar contraseñas',
  'Grupos poblacionales',
  'Subir clientes',
  'Ver clientes nuevos',
];

const activeTab = ref(0);
  </script>
  
  <style scoped>
  /* Añadir estilos específicos si es necesario */
  </style>
  