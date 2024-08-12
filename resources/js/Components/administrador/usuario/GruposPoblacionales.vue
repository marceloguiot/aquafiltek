<script setup>
import { ref, onBeforeMount } from 'vue';
import axios from 'axios';
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogTitle,
} from '@headlessui/vue';


const nombre = ref('');
const selectedProvincia = ref('');
const selectedCanton = ref('');
const selectedParroquia = ref('');
const comentario = ref('');

const isOpenagregar = ref(false);

const provincias = ref([]);
const cantones = ref([]);
const parroquias = ref([]);

const headers = ref([
  { text: "Nombre", value: "nombre" },
  { text: "Provincia", value: "idprovincia"},
  { text: "Canton", value: "idcanton"},
  { text: "Parroquia", value: "idparroquia"},
  { text: "Comentario", value: "comentario"},
  { text: "Opciones", value: "id"},
]);

const items = ref([]);

const fetchProvincias = async () => {
  const response = await axios.get('/api/provincias');
  provincias.value = response.data;
};

const fetchCantones = async () => {
  if (selectedProvincia.value) {
    const response = await axios.get(`/api/cantones/${selectedProvincia.value}`);
    cantones.value = response.data;
  }
};

const fetchParroquias = async () => {
  if (selectedCanton.value) {
    const response = await axios.get(`/api/parroquias/${selectedCanton.value}`);
    parroquias.value = response.data;
  }
};

const closeModal = () =>{
  isOpenagregar.value = false;
}

const openModal = () => {
  isOpenagregar.value = true;
}

const submitForm = async () => {
  const payload = {
    nombre: nombre.value,
    idprovincia: selectedProvincia.value,
    idcanton: selectedCanton.value,
    idparroquia: selectedParroquia.value,
    comentario: comentario.value,
  };

  try {
    await axios.post('/grupos-poblacionales', payload);
    // handle success (e.g., clear form, show success message)
  } catch (error) {
    // handle error (e.g., show error message)
  }
};

onBeforeMount(fetchProvincias);
  </script>
<template>
    <div>
      <h2 class="text-xl font-bold mb-4">Grupos Poblacionales</h2>
    </div>
    <div class="flex justify-end">
      <button class="bg-teal-500 p-2 rounded-md text-sm text-white text-semibold mb-5 hover:bg-teal-600" @click="openModal">Agregar grupo</button>

    </div>
    <easy-data-table
    :headers="headers"
    :items="items"
  />

  
  <TransitionRoot appear :show="isOpenagregar" as="template">
    <Dialog as="div" @close="closeModal" class="relative z-10">
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
                Nuevo grupo poblacional
              </DialogTitle>
              <div class="mt-2">
              <div class="flex flex-col">
                <!--  EMPIEZA FORMULARIO -->
                  <form @submit.prevent="submitForm">
                    <div class="mb-4 mt-4">
        <label for="nombre" class="block text-gray-700 font-bold mb-2">Nombre</label>
        <input type="text" v-model="nombre" id="nombre" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required />
      </div>

      <div class="mb-4">
        <label for="provincia" class="block text-gray-700 font-bold mb-2">Provincia</label>
        <select v-model="idprovincia" id="provincia" @change="fetchCantones" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
          <option v-for="provincia in provincias" :key="provincia.id" :value="provincia.id">
            {{ provincia.nombre }}
          </option>
        </select>
      </div>

      <div class="mb-4">
        <label for="canton" class="block text-gray-700 font-bold mb-2">Cantón</label>
        <select v-model="idcanton" id="canton" @change="fetchParroquias" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
          <option v-for="canton in cantones" :key="canton.id" :value="canton.id">
            {{ canton.nombre }}
          </option>
        </select>
      </div>

      <div class="mb-4">
        <label for="parroquia" class="block text-gray-700 font-bold mb-2">Parroquia</label>
        <select v-model="idparroquia" id="parroquia" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
          <option v-for="parroquia in parroquias" :key="parroquia.id" :value="parroquia.id">
            {{ parroquia.nombre }}
          </option>
        </select>
      </div>

      <div class="mb-4">
        <label for="comentario" class="block text-gray-700 font-bold mb-2">Comentario</label>
        <textarea v-model="comentario" id="comentario" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
      </div>

      <div class="flex items-center justify-between">
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
          Guardar
        </button>
      </div>
      </form>
        <!--  TERMINA FORMULARIO -->
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

  </template>
  

  
  <style scoped>
  /* Añadir estilos específicos si es necesario */
  </style>
  