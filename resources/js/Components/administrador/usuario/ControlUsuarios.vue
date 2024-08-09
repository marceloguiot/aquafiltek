<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogTitle,
} from '@headlessui/vue';


const isOpeneditar = ref(false);
const isOpenpermisos = ref(false);


const users = ref([]);
const actual_user = ref([]);
const startDate = ref('');
const endDate = ref('');

const filterUsers = async () => {

};

const fetchUsers = async () => {
  try {
    const response = await axios.get('/users');
    users.value = response.data;
  } catch (error) {
    console.error('Error fetching users:', error);
  }
};

const seleccionar_operador = (user) => {
actual_user.value = user;
}

const editar_pass = (usuario) =>{
  isOpeneditar.value = true;

}

const editar_permisos = (usuario) =>{
  isOpenpermisos.value = true;

}

const closeModalEditar = () =>{
  isOpeneditar.value = false;
}


const closeModalPermisos = () =>{
  isOpenpermisos.value = false;
}

const submitForm = async () => {
  try {
    const response = await axios.post('/cliente/guardar-permisos', {
      clientes: permisos.value.clientes,
      inactivacion: permisos.value.inactivacion
    });
    
    // Manejar la respuesta aquí (opcional)
    console.log('Permisos guardados correctamente:', response.data);
  } catch (error) {
    // Manejar el error aquí
    console.error('Error al guardar permisos:', error);
  }
};

// Crear un objeto para almacenar los permisos seleccionados
const permisos = ref({
  clientes: 'agregar_modificar', // Valor predeterminado
  inactivacion: 'puede_inactivar' // Valor predeterminado
});
onMounted(fetchUsers);
</script>
<template>
    <div>
      <h2 class="text-xl font-bold mb-4">Control de Usuarios</h2>
    
    <div>
      <table class="table w-3/4 mx-auto mt-6 mb-10">
        <thead>
          <tr class="bg-slate-500 text-white">
            <th class="border border-slate-700 font-semibold">ID</th>
            <th class="border border-slate-700 font-semibold">Nombre</th>
            <th class="border border-slate-700 font-semibold">Usuario</th>
            <th class="border border-slate-700 font-semibold">Opciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users">
            <td class="text-center border border-slate-700 hover:cursor-pointer" @click="seleccionar_operador(user)">{{user.id}}</td>
            <td class="text-center border border-slate-700 hover:cursor-pointer" @click="seleccionar_operador(user)">{{user.name}}</td>
            <td class="text-center border border-slate-700 hover:cursor-pointer" @click="seleccionar_operador(user)">{{user.email}}</td>
            <td class="text-center border border-slate-700 flex flex-col"><span class="text-blue-500 hover:cursor-pointer" @click="editar_pass(user)">Editar contraseña</span>
              <span class="text-blue-500 hover:cursor-pointer"  @click="editar_permisos(user)">Editar permisos</span></td>

          </tr>
        </tbody>
      </table>
    </div>
    </div>
    <div v-if="actual_user.length != 0" class="flex flex-col mb-6 w-3/4 mx-auto bg-slate-700 p-4 rounded-md">
      <span class="text-white text-center text-2xl font-semibold mt-4">Historial de ingreso</span>
      <span class="text-white text-center text-xl  mb-6 mt-5">Operador: {{ actual_user.name }}</span>
      <div class="flex flex-row justify-around">
      <div class="mr-4">
        <label for="start_date" class="block text-white text-lg mb-2">Fecha de Inicio</label>
        <input type="date" v-model="startDate" id="start_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
      </div>
      <div class="mr-4">
        <label for="end_date" class="block text-white text-lg mb-2">Fecha de Fin</label>
        <input type="date" v-model="endDate" id="end_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
      </div>
    </div>
      <div class="flex justify-center mt-6 mb-6">
        <button @click="filterUsers" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Filtrar</button>
      </div>
      <table class="min-w-full bg-gray-800 text-white">
      <thead>
        <tr>
          <th class="py-2 px-4 border-b border-white">Fecha ingreso</th>
          <th class="py-2 px-4 border-b border-white">Hora ingreso</th>
          <th class="py-2 px-4 border-b border-white">Hora salida</th>
          <th class="py-2 px-4 border-b border-white">Dirección IP</th>
          <th class="py-2 px-4 border-b border-white">Tiempo inactivo</th>
          <th class="py-2 px-4 border-b border-white">Tiempo almuerzo</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td class="py-2 px-4 border-b border-white"></td>
          <td class="py-2 px-4 border-b border-white"></td>
          <td class="py-2 px-4 border-b border-white"></td>
          <td class="py-2 px-4 border-b border-white"></td>
          <td class="py-2 px-4 border-b border-white"></td>
          <td class="py-2 px-4 border-b border-white"></td>
        </tr>
      </tbody>
    </table>
    </div>
    <TransitionRoot appear :show="isOpeneditar" as="template">
    <Dialog as="div" @close="closeModalEditar" class="relative z-10">
      <TransitionChild
        as="template"
        enter="duration-300 ease-out"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="duration-200 ease-in"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-black/25" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div
          class="flex min-h-full items-center justify-center p-4 text-center"
        >
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel
              class="w-full max-w-[50%] transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
            >
              <DialogTitle
                as="h3"
                class="text-2xl font-bold text-blue-500"
              >
                Editar contraseña
              </DialogTitle>
              <div class="mt-2">
              <div class="flex flex-col">
                <form @submit.prevent="submitGestion('competencia')">
       
      </form>
              </div>
              </div>
              <div class="flex justify-center mt-16">
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>

  <TransitionRoot appear :show="isOpenpermisos" as="template">
    <Dialog as="div" @close="closeModalPermisos" class="relative z-10">
      <TransitionChild
        as="template"
        enter="duration-300 ease-out"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="duration-200 ease-in"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-black/25" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div
          class="flex min-h-full items-center justify-center p-4 text-center"
        >
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel
              class="w-full max-w-[50%] transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
            >
              <DialogTitle
                as="h3"
                class="text-2xl font-bold text-blue-500"
              >
                Editar permisos
              </DialogTitle>
              <div class="mt-2">
              <div class="flex flex-col">
                
           <!-- Primer Apartado -->
    <div class="mb-6 mt-6">
      <h3 class="text-xl font-semibold mb-2">Permisos de Clientes</h3>
      
      <div class="flex items-center mb-4">
        <input 
          type="radio" 
          id="agregar_modificar" 
          value="agregar_modificar" 
          v-model="permisos.clientes" 
          class="mr-2"
        />
        <label for="agregar_modificar" class="text-gray-700">Puede agregar nuevos clientes y modificar los datos</label>
      </div>

      <div class="flex items-center">
        <input 
          type="radio" 
          id="no_agregar" 
          value="no_agregar" 
          v-model="permisos.clientes" 
          class="mr-2"
        />
        <label for="no_agregar" class="text-gray-700">NO puede agregar nuevos clientes</label>
      </div>
    </div>

    <!-- Segundo Apartado -->
    <div class="mt-4">
      <h3 class="text-xl font-semibold mb-2">Permisos de Inactivación</h3>
      
      <div class="flex items-center mb-4">
        <input 
          type="radio" 
          id="puede_inactivar" 
          value="puede_inactivar" 
          v-model="permisos.inactivacion" 
          class="mr-2"
        />
        <label for="puede_inactivar" class="text-gray-700">Puede inactivar clientes</label>
      </div>

      <div class="flex items-center">
        <input 
          type="radio" 
          id="no_inactivar" 
          value="no_inactivar" 
          v-model="permisos.inactivacion" 
          class="mr-2"
        />
        <label for="no_inactivar" class="text-gray-700">No puede inactivar clientes</label>
      </div>
    </div>
    <div class="mt-7 mb-5 flex justify-end">
      <button 
        @click="submitForm" 
        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700"
      >
        Guardar Permisos
      </button>
    </div>
  
              </div>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
  </template>
  <style scoped>
  </style>
  