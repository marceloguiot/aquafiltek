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
            <td class="text-center border border-slate-700 flex flex-col"><span class="text-blue-500 hover:cursor-pointer">Editar contraseña</span>
              <span class="text-blue-500 hover:cursor-pointer">Editar permisos</span></td>

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
  </template>
  <style scoped>
  </style>
  