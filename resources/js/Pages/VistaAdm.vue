<template>
    <Head title="Administración" />
    <AuthenticatedLayout>
    <div class="p-6 bg-gray-100 min-h-screen">
      <h1 class="text-2xl font-bold mb-6">Administración</h1>
      <div class="flex flex-row justify-center mb-6">
        <button
          v-for="(tab, index) in tabs"
          :key="index"
          @click="activeTab = tab"
          :class="[
            'px-4 py-2',
            activeTab === tab ? 'bg-blue-500 text-white' : 'bg-white text-blue-500',
            'rounded-t',
            'border'
          ]"
        >
          {{ tab }}
        </button>
      </div>
      <div class="p-4 bg-white rounded-b border">
        <component :is="currentTabComponent"></component>
      </div>
    </div>
</AuthenticatedLayout>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue';
  import Usuarios from '../Components/administrador/Usuarios.vue';
  import ControlPermisos from '../Components/administrador/ControlPermisos.vue';
  import Inactivos from '../Components/administrador/Inactivos.vue';
  import GenerarReportes from '../Components/administrador/GenerarReportes.vue';
  import ControlEscalas from '../Components/administrador/ControlEscalas.vue';
  import TiraInformativa from '../Components/administrador/TiraInformativa.vue';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import { Head } from '@inertiajs/vue3';
  
  const tabs = ['Usuarios', 'Control permisos', 'Inactivos', 'Generar reportes', 'Control Escalas', 'Tira informativa'];
  const activeTab = ref('Usuarios');
  
  const currentTabComponent = computed(() => {
    switch (activeTab.value) {
      case 'Usuarios':
        return Usuarios;
      case 'Control permisos':
        return ControlPermisos;
      case 'Inactivos':
        return Inactivos;
      case 'Generar reportes':
        return GenerarReportes;
      case 'Control Escalas':
        return ControlEscalas;
      case 'Tira informativa':
        return TiraInformativa;
      default:
        return Usuarios;
    }
  });
  </script>
  
  <style scoped>
  /* Añadir estilos específicos si es necesario */
  </style>
  