<script setup>
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogTitle,
} from '@headlessui/vue';

import { ref, computed, reactive, onBeforeUnmount } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { defineEmits } from 'vue';

// Define emits
const emit = defineEmits(['clienteSeleccionado']);


const redirectToGestionCalendario = () => {
  router.visit(route('calendario'));
}


const searchResults = ref([]);
//form

const form = reactive({
  codigo: null,
  coordenadas: null,
  nombre_cliente: null,
  datos_factura: null,
  direccion: null,
  telefono: null,
  telefono_oficina: null,
  celular1: null,
  celular2: null,
  correo: null,
  informacion_cisterna: null,
  observaciones: null,
  tipo_tel1: null,
  tipo_telof: null,
  tipo_cel1: null,
  tipo_cel2: null,
  obser_tel1: null,
  obser_telof: null,
  obser_cel1: null,
  obser_cel2: null,
  provincia: null,
  canton: null,
  parroquia: null,
});

// Define reactive variables
const time = ref(0);
const running = ref(false);
let intervalId = null;

// Computed property to format time as MM:SS
const formattedTime = computed(() => {
  const minutes = String(Math.floor(time.value / 60)).padStart(2, '0');
  const seconds = String(time.value % 60).padStart(2, '0');
  return `${minutes}:${seconds}`;
});

const submit = () => {

  router.post('/cliente', form)
}

const searchCriteria = reactive({
        nombre: '',
        ciudadela: '',
        telefono: '',
        factura: '',
        id: ''
      });

const props = defineProps({
  actual: Object
});


const handleSearch = async (field, value) => {
  try {
    if(value.length > 3)
  {
    const response = await axios.post('/getClientSearch', {
      field: field,
      value: value
    });
    searchResults.value = response.data;
  }
  else
  {
    searchResults.value = [];
  }
  } catch (error) {
    console.error('There was an error!', error);
  }
};

const edit = ref(props.actual);


const actualizar = async () => {
  router.post('/cliente_actualizar', edit.value);
  isOpened.value = false;
}

// Method to start the timer
const startTimer = () => {
  if (!running.value) {
    running.value = true;
    intervalId = setInterval(() => {
      time.value += 1;
    }, 1000);
  }
};

// Method to stop the timer
const stopTimer = () => {
  if (running.value) {
    running.value = false;
    clearInterval(intervalId);
    intervalId = null;
  }
};

// Method to reset the timer
const resetTimer = () => {
  stopTimer();
  time.value = 0;
};

// Lifecycle hook to clean up the interval
onBeforeUnmount(() => {
  stopTimer();
});

const isOpen = ref(false);
const isOpened = ref(false)
const isOpendesc = ref(false);
const showsrch = ref(true);

function closeModal() {
  isOpen.value = false
}
function openModal() {
  isOpen.value = true
}

function openDesc() {
  isOpendesc.value = true;
  startTimer();
}
const closeModalDesc = async () => {
  isOpendesc.value = false;
  stopTimer();


  const response = await axios.post('/guardar-descanso', {
      tipo: 'descanso',
      tiempo: time.value,
    });

    resetTimer();
}

const showsearch = () => {
  showsrch.value = !showsrch.value;
}

const elegir_actual = (cliente) =>{
  emit('clienteSeleccionado', cliente);
  showsrch.value = true;

  searchCriteria.id = '';
  searchCriteria.nombre = '';
  searchCriteria.factura = '';
  searchCriteria.ciudadela = '';
  searchCriteria.telefono = '';
  searchResults.value = [];

}

function closeModaled() {
  isOpened.value = false
}
function openModaled() {
  isOpened.value = true
}
</script>
<template>
     <div class="flex flex-row justify-evenly mt-5">
                    <button class="bg-blue-500 text-white font-semibold rounded-md p-2" @click="openDesc"><font-awesome-icon icon="fa-solid fa-bed" /></button>
                    <button class="bg-blue-500 text-white font-semibold rounded-md p-2" @click="openModal"><font-awesome-icon icon="fa-solid fa-user-plus" /></button>
                    <button class="bg-blue-500 text-white font-semibold rounded-md p-2" @click="openModaled"><font-awesome-icon icon="fa-solid fa-vcard" /></button>
                    <button class="bg-blue-500 text-white font-semibold rounded-md p-2"><font-awesome-icon icon="fa-solid fa-hourglass-1" /></button>
                    <button class="bg-blue-500 text-white font-semibold rounded-md p-2"><font-awesome-icon icon="fa-solid fa-file-pdf" /></button>
                    <button class="bg-blue-500 text-white font-semibold rounded-md p-2" @click="showsearch"><font-awesome-icon icon="fa-solid fa-search" /></button>
                    <button class="bg-blue-500 text-white font-semibold rounded-md p-2" @click="redirectToGestionCalendario"><font-awesome-icon icon="fa-solid fa-calendar" /></button>
                </div>
    <div class="flex flex-row space-x-4 p-4">
    <!-- Campo de búsqueda: Nombre -->
    <div class="flex-1"   :hidden="showsrch">
      <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
      <input v-model="searchCriteria.nombre" type="text" @keyup="handleSearch('nombre_cliente', $event.target.value)" id="nombre" placeholder="Buscar por nombre" class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
    </div>
    
    <!-- Campo de búsqueda: Ciudadela -->
    <div class="flex-1"   :hidden="showsrch">
      <label for="ciudadela" class="block text-sm font-medium text-gray-700">Ciudadela</label>
      <input v-model="searchCriteria.ciudadela" type="text" id="ciudadela" @keyup="handleSearch('ciudadela', $event.target.value)" placeholder="Buscar por ciudadela" class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
    </div>
    
    <!-- Campo de búsqueda: Teléfono -->
    <div class="flex-1"   :hidden="showsrch">
      <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
      <input v-model="searchCriteria.telefono" type="text" id="telefono" @keyup="handleSearch('telefono', $event.target.value)" placeholder="Buscar por teléfono" class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
    </div>
    
    <!-- Campo de búsqueda: Factura -->
    <div class="flex-1"   :hidden="showsrch">
      <label for="factura" class="block text-sm font-medium text-gray-700">Factura</label>
      <input v-model="searchCriteria.factura" type="text" id="factura" @keyup="handleSearch('factura', $event.target.value)" placeholder="Buscar por factura" class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
    </div>
    
    <!-- Campo de búsqueda: ID -->
    <div class="flex-1"   :hidden="showsrch">
      <label for="id" class="block text-sm font-medium text-gray-700">ID</label>
      <input v-model="searchCriteria.id" type="text" id="id" placeholder="Buscar por ID" @keyup="handleSearch('codigo', $event.target.value)" class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
    </div>
  </div>
  <div v-if="searchResults.length > 0" class="mt-4" :hidden="showsrch">
    <h2 class="text-lg font-medium text-gray-900">Resultados de la búsqueda:</h2>
    <ul class="mt-1 space-y-1">
      <li v-for="cliente in searchResults" @click="elegir_actual(cliente)" :key="cliente.id" class="p-2 bg-white border border-gray-200 rounded-md shadow-sm hover:cursor-pointer hover:bg-slate-300">
        <p class="text-xs">{{ cliente.nombre_cliente }}</p>
      </li>
    </ul>
  </div>
<TransitionRoot appear :show="isOpen" as="template">
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
                class="text-2xl font-medium text-blue-500"
              >
                Agregar un nuevo cliente
              </DialogTitle>
              <div class="mt-2">
                <p class="text-sm text-gray-500">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">
      <form class="space-y-4" @submit.prevent="submit">
            <div>
                <label for="client-id" class="block text-sm font-medium text-gray-700">ID Cliente</label>
                <input type="text" id="client-id" v-model="form.codigo" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>
            <div>
                <label for="coordinates" class="block text-sm font-medium text-gray-700">Coordenadas</label>
                <input type="text" id="coordinates" v-model="form.coordenadas" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>
            <div>
                <label for="client-name" class="block text-sm font-medium text-gray-700">Nombre Cliente</label>
                <input type="text" id="client-name" v-model="form.nombre_cliente" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>
            <div>
                <label for="invoice-data" class="block text-sm font-medium text-gray-700">Datos de la Factura</label>
                <input type="text" id="invoice-data" v-model="form.datos_factura" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700">Dirección</label>
                <input type="text" id="address" v-model="form.direccion" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>
            <div>
                <label for="primary-phone" class="block text-sm font-medium text-gray-700">Teléfono Principal</label>
                <input type="number" id="primary-phone" min="1111111111" v-model="form.telefono" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>
            <div>
                <label for="office-phone" class="block text-sm font-medium text-gray-700">Teléfono Oficina</label>
                <input type="text" id="office-phone" min="1111111111" v-model="form.telefono_oficina" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="cellphone-1" class="block text-sm font-medium text-gray-700">Celular 1</label>
                <input type="text" id="cellphone-1"  min="1111111111" v-model="form.celular1" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="cellphone-2" class="block text-sm font-medium text-gray-700">Celular 2</label>
                <input type="text" id="cellphone-2"  min="1111111111" v-model="form.celular2" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                <input type="email" id="email" v-model="form.correo" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>
            <div>
                <label for="cistern-info" class="block text-sm font-medium text-gray-700">Información de Cisterna</label>
                <textarea id="cistern-info" v-model="form.informacion_cisterna" rows="3" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            </div>
            <div>
                <label for="observations" class="block text-sm font-medium text-gray-700">Observaciones</label>
                <textarea id="observations" v-model="form.observaciones" rows="3" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            </div>
            <div class="text-right">
                <button
                  type="button"
                  class="inline-flex mr-5 justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                  @click="closeModal"
                >
                  Cancelar
                </button>
                <button type="submit" class="py-2 px-4 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Guardar</button>
            </div>
        </form>

    </div>
                </p>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>

  <TransitionRoot appear :show="isOpened" as="template">
    <Dialog as="div" @close="closeModaled" class="relative z-10">
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
                class="text-2xl font-medium text-blue-500"
              >
                Modificar cliente actual
              </DialogTitle>
              <div class="mt-2">
                <p class="text-sm text-gray-500">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">
     
        <form class="space-y-4" @submit.prevent="actualizar">
            <div>
                <label for="client-id" class="block text-sm font-medium text-gray-700">ID Cliente</label>
                <input type="text" id="client-id" name="client-id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" v-model="edit.codigo">
            </div>
            <div>
                <label for="coordinates" class="block text-sm font-medium text-gray-700">Coordenadas</label>
                <input type="text" id="coordinates" name="coordinates" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"  v-model="edit.coordenadas">
            </div>
            <div>
                <label for="client-name" class="block text-sm font-medium text-gray-700">Nombre Cliente</label>
                <input type="text" id="client-name" name="client-name" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"  v-model="edit.nombre_cliente">
            </div>
            <div>
                <label for="invoice-data" class="block text-sm font-medium text-gray-700">Datos de la Factura</label>
                <input type="text" id="invoice-data" name="invoice-data" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"  v-model="edit.datos_factura">
            </div>
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700">Dirección</label>
                <input type="text" id="address" name="address" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"  v-model="edit.direccion">
            </div>
            <div>
                <label for="primary-phone" class="block text-sm font-medium text-gray-700">Teléfono Principal</label>
                <input type="text" id="primary-phone" name="primary-phone" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"  v-model="edit.telefono">
            </div>
            <div>
                <label for="office-phone" class="block text-sm font-medium text-gray-700">Teléfono Oficina</label>
                <input type="text" id="office-phone" name="office-phone" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"  v-model="edit.telefono_oficina">
            </div>
            <div>
                <label for="cellphone-1" class="block text-sm font-medium text-gray-700">Celular 1</label>
                <input type="text" id="cellphone-1" name="cellphone-1" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"  v-model="edit.celular1">
            </div>
            <div>
                <label for="cellphone-2" class="block text-sm font-medium text-gray-700">Celular 2</label>
                <input type="text" id="cellphone-2" name="cellphone-2" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"  v-model="edit.celular2">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                <input type="email" id="email" name="email" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"  v-model="edit.correo">
            </div>
            <div>
                <label for="initial-comments" class="block text-sm font-medium text-gray-700">Comentarios Iniciales</label>
                <textarea id="initial-comments" name="initial-comments" rows="3" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            </div>
            <div>
                <label for="cistern-info" class="block text-sm font-medium text-gray-700">Información de Cisterna</label>
                <textarea id="cistern-info" name="cistern-info" rows="3" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"  v-model="edit.informacion_cisterna"></textarea>
            </div>
            <div>
                <label for="observations" class="block text-sm font-medium text-gray-700">Observaciones</label>
                <textarea id="observations" name="observations" rows="3" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"  v-model="edit.observaciones"></textarea>
            </div>
            <div class="text-right">
                <button
                  type="button"
                  class="inline-flex mr-5 justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                  @click="closeModaled"
                >
                  Cancelar
                </button>
                <button type="submit" class="py-2 px-4 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Guardar</button>
            </div>
        </form>
    </div>
                </p>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>

  <TransitionRoot appear :show="isOpendesc" as="template">
    <Dialog as="div" class="relative z-10">
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
                Tomate un tiempo para relajarte
              </DialogTitle>
              <div class="mt-2">
                <p class="text-sm text-gray-500">
    <div class="max-w-4xl mx-auto p-8">
     <p class="text-center text-[24px] text-teal-700 font-semibold">
      Tiempo que has descansado
     </p>
     <div class="flex flex-col justify-evenly mt-16">
    <p class="text-center text-[36px] font-semibold text-teal-700">{{ formattedTime }}</p>
    <div>
  </div>
  </div>
    </div>
                </p>
              </div>
              <div class="flex justify-center mt-16">
              <button class="py-2 px-4 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" @click="closeModalDesc">Seguir trabajando</button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>
