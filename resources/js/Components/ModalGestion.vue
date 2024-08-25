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
import Swal from 'sweetalert2';

const isOpenacepto = ref(false);
const isOpeninspeccion = ref(false);
const isOpencobros = ref(false);
const isOpenimportante = ref(false);
const isOpenvolver = ref(false);
const isOpenrechazo = ref(false);
const isOpenaveriado = ref(false);
const isOpencompetencia = ref(false);

//NUEVAS MODALES
const isOpennoresponde = ref(false);
const isOpenequivocado = ref(false);
const isOpenotro = ref(false);

const emit = defineEmits(['updateEjecutado']);

const props = defineProps({
  actual: Object,
  scope: String
});


  const acepto = ref({
  codigo: props.actual.codigo,
  nombre_cliente: props.actual.nombre_cliente,
  fecha_acepto: '',
  hora_acepto: '',
  precio: '',
  comentarios: '',
  ambito: props.scope
});


const gestiones = ref({
  codigo: props.actual.codigo,
  nombre_cliente: props.actual.nombre_cliente,
  fecha: '',
  hora: '',
  comentarios: '',
  tipo :'',
  ambito: props.scope
});

if(props.scope == 'llamadas'){
  acepto.value.id_llamada = props.actual.id_llamada;
  gestiones.value.id_llamada = props.actual.id_llamada;
}


const reset_acepto = () => {
isOpenacepto.value = false;
isOpeninspeccion.value = false;
isOpencobros.value = false;
isOpenimportante.value = false;
isOpenvolver.value = false;
isOpenrechazo.value = false;
isOpenaveriado.value = false;
isOpencompetencia.value = false;
isOpennoresponde.value = false;
isOpenequivocado.value = false;
isOpenotro.value = false;

  acepto.value.codigo = props.actual.codigo;
  acepto.value.nombre_cliente = props.actual.nombre_cliente;
  acepto.value.fecha_acepto = '';
  acepto.value.hora_acepto = '';
  acepto.value.precio = '';
  acepto.value.comentarios = '';
}

const reset_gestiones = () => {
  gestiones.value.codigo = props.actual.codigo;
  gestiones.value.nombre_cliente = props.actual.nombre_cliente;
  gestiones.value.fecha = '';
  gestiones.value.hora = '';
  gestiones.value.comentarios = '';
  gestiones.value.tipo = '';

isOpenacepto.value = false;
isOpeninspeccion.value = false;
isOpencobros.value = false;
isOpenimportante.value = false;
isOpenvolver.value = false;
isOpenrechazo.value = false;
isOpenaveriado.value = false;
isOpencompetencia.value = false;
isOpennoresponde.value = false;
isOpenequivocado.value = false;
isOpenotro.value = false;
}



const submitAcepto = async () => {
  try {


    const response = await axios.post('/gestion_acepto', acepto.value);
    console.log('Datos enviados con éxito:', response.data);
    emit('updateEjecutado', 'true');
    reset_acepto();
    
  } catch (error) {
    console.error('Error al enviar los datos:', error);
  }
};


const submitGestion = async (tipo) => {
  try {
    gestiones.value.tipo = tipo;
    const response = await axios.post('/gestion', gestiones.value);
    console.log('Datos enviados con éxito:', response.data);
    emit('updateEjecutado', 'true');
    reset_gestiones();
    
  } catch (error) {
    console.error('Error al enviar los datos:', error);
  }
};

const submitAveriado = async () => {
  try {
    gestiones.value.tipo = 'Averiado';
    const response = await axios.post('/gestion-averiado', gestiones.value);
    console.log('Datos enviados con éxito:', response.data);
    emit('updateEjecutado', 'true');
    reset_gestiones();
    
  } catch (error) {
    console.error('Error al enviar los datos:', error);
  }
};




function openAcepto() {
  isOpenacepto.value = true;
}
function closeModalAcepto() {
  isOpenacepto.value = false;
}

function openInspeccion() {
  isOpeninspeccion.value = true;
}
function closeModalInspeccion() {
  isOpeninspeccion.value = false;
}

function openCobros() {
  isOpencobros.value = true;
}
function closeModalCobros() {
  isOpencobros.value = false;
}

function openImportante() {
  isOpenimportante.value = true;
}
function closeModalImportante() {
  isOpenimportante.value = false;
}

function closeModalnoresponde() {
  isOpennoresponde.value = false;
}
function closeModalequivocado() {
  isOpenequivocado.value = false;
}
function closeModalotro() {
  isOpenotro.value = false;
}

function openVolver() {
  isOpenvolver.value = true;
}

function openNoresponde() {
  isOpennoresponde.value = true;
}

function openEquivocado() {
  isOpenequivocado.value = true;
}

function openOtro() {
  isOpenotro.value = true;
}

function closeModalVolver() {
  isOpenvolver.value = false;
}

function openAveriado() {
  isOpenaveriado.value = true;
}
function closeModalAveriado() {
  isOpenaveriado.value = false;
}

function openCompetencia() {
  isOpencompetencia.value = true;
}
function closeModalCompetencia() {
  isOpencompetencia.value = false;
}

function openRechazo() {
  isOpenrechazo.value = true;
}
function closeModalRechazo() {
  isOpenrechazo.value = false;
}
</script>
<template>
                <div class="flex flex-row justify-evenly mt-5 mb-10">
                <button class="bg-teal-500 rounded-md lg:text-[15px] lg:p-2 sm:p-1 md:text-[12.5px] text-white hover:bg-teal-400" @click="openAcepto">Aceptó</button>
                <button class="bg-teal-500 rounded-md lg:text-[15px] lg:p-2 sm:p-1 md:text-[12.5px] text-white hover:bg-teal-400" @click="openInspeccion">Inspección</button>
                <button class="bg-teal-500 rounded-md lg:text-[15px] lg:p-2 sm:p-1 md:text-[12.5px] text-white hover:bg-teal-400" @click="openCobros">Cobros</button>
                <button class="bg-teal-500 rounded-md lg:text-[15px] lg:p-2 sm:p-1 md:text-[12.5px] text-white hover:bg-teal-400" @click="openImportante">Importante</button>
                <button class="bg-teal-500 rounded-md lg:text-[15px] lg:p-2 sm:p-1 md:text-[12.5px] text-white hover:bg-teal-400" @click="openVolver">Volver a llamar</button>
                <button class="bg-teal-500 rounded-md lg:text-[15px] lg:p-2 sm:p-1 md:text-[12.5px] text-white hover:bg-teal-400" @click="openRechazo">Rechazó</button>
                <button class="bg-teal-500 rounded-md lg:text-[15px] lg:p-2 sm:p-1 md:text-[12.5px] text-white hover:bg-teal-400" @click="openNoresponde">No responde</button>
                <button class="bg-teal-500 rounded-md lg:text-[15px] lg:p-2 sm:p-1 md:text-[12.5px] text-white hover:bg-teal-400" @click="openEquivocado">Equivocado</button>
                <button class="bg-teal-500 rounded-md lg:text-[15px] lg:p-2 sm:p-1 md:text-[12.5px] text-white hover:bg-teal-400" @click="openAveriado">Averiado</button>
                <button class="bg-teal-500 rounded-md lg:text-[15px] lg:p-2 sm:p-1 md:text-[12.5px] text-white hover:bg-teal-400" @click="openOtro">Otro</button>
                <button class="bg-red-500 rounded-md lg:text-[15px] lg:p-2 sm:p-1 md:text-[12.5px] text-white hover:bg-red-400" @click="openCompetencia">Competencia</button>
                </div>

                <TransitionRoot appear :show="isOpenacepto" as="template">
    <Dialog as="div" @close="closeModalAcepto" class="relative z-10">
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
                Gestión aceptada
              </DialogTitle>
              <div class="mt-2">
              <div class="flex flex-col">
                <!--  EMPIEZA FORMULARIO -->
                  <form @submit.prevent="submitAcepto">
                <div class="mb-4">
            <label for="nombre_cliente" class="block text-gray-700 text-sm font-bold mb-2">Nombre del cliente</label>
            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="acepto.nombre_cliente" required readonly>
          </div>

        <div class="mb-4">
            <label for="fecha_ejecucion" class="block text-gray-700 text-sm font-bold mb-2">Fecha de ejecución del servicio</label>
            <input type="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="acepto.fecha_acepto" required>
        </div>

        <div class="mb-4">
            <label for="hora_ejecucion" class="block text-gray-700 text-sm font-bold mb-2">Hora de ejecución del servicio</label>
            <input type="time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="acepto.hora_acepto" min="06:00" max="19:00" required>
        </div>

        <div class="mb-4">
            <label for="precio_aceptado" class="block text-gray-700 text-sm font-bold mb-2">Precio aceptado</label>
            <input type="number" step="0.50" min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="acepto.precio" required>
        </div>

        <div class="mb-4">
            <label for="comentarios_adicionales" class="block text-gray-700 text-sm font-bold mb-2">Comentarios adicionales</label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="acepto.comentarios" rows="4"></textarea>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Guardar</button>
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


  <TransitionRoot appear :show="isOpeninspeccion" as="template">
    <Dialog as="div" @close="closeModalInspeccion" class="relative z-10">
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
                Inspección
              </DialogTitle>
              <div class="mt-2">
              <div class="flex flex-col">
                <form @submit.prevent="submitGestion('inspeccion')">
                <div class="mb-4">
            <label for="nombre_cliente" class="block text-gray-700 text-sm font-bold mb-2">Nombre del cliente</label>
            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.nombre_cliente"  required readonly>
        </div>

        <div class="mb-4">
            <label for="fecha_ejecucion" class="block text-gray-700 text-sm font-bold mb-2">Fecha de ejecución del servicio</label>
            <input type="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.fecha" required>
        </div>

        <div class="mb-4">
            <label for="hora_ejecucion" class="block text-gray-700 text-sm font-bold mb-2">Hora de ejecución del servicio</label>
            <input type="time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.hora" min="06:00" max="19:00" required>
        </div>

        <div class="mb-4">
            <label for="comentarios_adicionales" class="block text-gray-700 text-sm font-bold mb-2">Comentarios adicionales</label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.comentarios" rows="4"></textarea>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Guardar</button>
        </div>
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

  <TransitionRoot appear :show="isOpencobros" as="template">
    <Dialog as="div" @close="closeModalCobros" class="relative z-10">
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
                Cobros
              </DialogTitle>
              <div class="mt-2">
              <div class="flex flex-col">
                <form @submit.prevent="submitGestion('cobros')">
                <div class="mb-4">
            <label for="nombre_cliente" class="block text-gray-700 text-sm font-bold mb-2">Nombre del cliente</label>
            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.nombre_cliente" readonly required>
        </div>

        <div class="mb-4">
            <label for="fecha_ejecucion" class="block text-gray-700 text-sm font-bold mb-2">Fecha de ejecución del servicio</label>
            <input type="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.fecha" required>
        </div>

        <div class="mb-4">
            <label for="hora_ejecucion" class="block text-gray-700 text-sm font-bold mb-2">Hora de ejecución del servicio</label>
            <input type="time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.hora" min="06:00" max="19:00" required>
        </div>

        <div class="mb-4">
            <label for="comentarios_adicionales" class="block text-gray-700 text-sm font-bold mb-2">Comentarios adicionales</label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.comentarios" rows="4"></textarea>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Guardar</button>
        </div>
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

  <TransitionRoot appear :show="isOpenimportante" as="template">
    <Dialog as="div" @close="closeModalImportante" class="relative z-10">
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
                Importante
              </DialogTitle>
              <div class="mt-2">
              <div class="flex flex-col">
                <form @submit.prevent="submitGestion('importante')">
                <div class="mb-4">
            <label for="nombre_cliente" class="block text-gray-700 text-sm font-bold mb-2">Nombre del cliente</label>
            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.nombre_cliente" readonly required>
        </div>

        <div class="mb-4">
            <label for="fecha_ejecucion" class="block text-gray-700 text-sm font-bold mb-2">Fecha llamada</label>
            <input type="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.fecha" required>
        </div>

        <div class="mb-4">
            <label for="hora_ejecucion" class="block text-gray-700 text-sm font-bold mb-2">Hora llamada</label>
            <input type="time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.hora" min="06:00" max="19:00" required>
        </div>

        <div class="mb-4">
            <label for="comentarios_adicionales" class="block text-gray-700 text-sm font-bold mb-2">Comentarios adicionales</label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.comentarios" rows="4"></textarea>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Guardar</button>
        </div>
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


  <TransitionRoot appear :show="isOpenvolver" as="template">
    <Dialog as="div" @close="closeModalVolver" class="relative z-10">
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
                Reprogramar llamada
              </DialogTitle>
              <div class="mt-2">
              <div class="flex flex-col">
                <form @submit.prevent="submitGestion('reprogramar')">
                <div class="mb-4">
            <label for="nombre_cliente" class="block text-gray-700 text-sm font-bold mb-2">Nombre del cliente</label>
            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.nombre_cliente" readonly required>
        </div>

        <div class="mb-4">
            <label for="fecha_ejecucion" class="block text-gray-700 text-sm font-bold mb-2">Fecha llamada</label>
            <input type="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.fecha" required>
        </div>

        <div class="mb-4">
            <label for="hora_ejecucion" class="block text-gray-700 text-sm font-bold mb-2">Hora llamada</label>
            <input type="time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.hora" min="06:00" max="19:00" required>
        </div>

        <div class="mb-4">
            <label for="comentarios_adicionales" class="block text-gray-700 text-sm font-bold mb-2">Comentarios adicionales</label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.comentarios" rows="4"></textarea>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Guardar</button>
        </div>
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

  <TransitionRoot appear :show="isOpenrechazo" as="template">
    <Dialog as="div" @close="closeModalRechazo" class="relative z-10">
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
                Gestión rechazada
              </DialogTitle>
              <div class="mt-2">
              <div class="flex flex-col">
                <form @submit.prevent="submitGestion('rechazo')">
                <div class="mb-4">
            <label for="nombre_cliente" class="block text-gray-700 text-sm font-bold mb-2">Nombre del cliente</label>
            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.nombre_cliente" readonly required>
        </div>

        <div class="mb-4">
            <label for="fecha_ejecucion" class="block text-gray-700 text-sm font-bold mb-2">Fecha rechazo</label>
            <input type="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.fecha" required>
        </div>

        <div class="mb-4">
            <label for="hora_ejecucion" class="block text-gray-700 text-sm font-bold mb-2">Hora rechazo</label>
            <input type="time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.hora" min="06:00" max="19:00" required>
        </div>

        <div class="mb-4">
            <label for="comentarios_adicionales" class="block text-gray-700 text-sm font-bold mb-2">Comentarios adicionales</label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.comentarios" rows="4"></textarea>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Guardar</button>
        </div>
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

  <TransitionRoot appear :show="isOpennoresponde" as="template">
    <Dialog as="div" @close="closeModalnoresponde" class="relative z-10">
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
                Reprogramar llamada
              </DialogTitle>
              <div class="mt-2">
              <div class="flex flex-col">
                <form @submit.prevent="submitGestion('no responde')">
                <div class="mb-4">
            <label for="nombre_cliente" class="block text-gray-700 text-sm font-bold mb-2">Nombre del cliente</label>
            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.nombre_cliente" readonly required>
        </div>

        <div class="mb-4">
            <label for="fecha_ejecucion" class="block text-gray-700 text-sm font-bold mb-2">Fecha llamada</label>
            <input type="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.fecha" required>
        </div>

        <div class="mb-4">
            <label for="hora_ejecucion" class="block text-gray-700 text-sm font-bold mb-2">Hora llamada</label>
            <input type="time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.hora" min="06:00" max="19:00" required>
        </div>

        <div class="mb-4">
            <label for="comentarios_adicionales" class="block text-gray-700 text-sm font-bold mb-2">Comentarios adicionales</label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.comentarios" rows="4"></textarea>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Guardar</button>
        </div>
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

  <TransitionRoot appear :show="isOpenequivocado" as="template">
    <Dialog as="div" @close="closeModalequivocado" class="relative z-10">
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
                Reprogramar llamada
              </DialogTitle>
              <div class="mt-2">
              <div class="flex flex-col">
                <form @submit.prevent="submitGestion('equivocado')">
                <div class="mb-4">
            <label for="nombre_cliente" class="block text-gray-700 text-sm font-bold mb-2">Nombre del cliente</label>
            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.nombre_cliente" readonly required>
        </div>

        <div class="mb-4">
            <label for="fecha_ejecucion" class="block text-gray-700 text-sm font-bold mb-2">Fecha llamada</label>
            <input type="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.fecha" required>
        </div>

        <div class="mb-4">
            <label for="hora_ejecucion" class="block text-gray-700 text-sm font-bold mb-2">Hora llamada</label>
            <input type="time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.hora" min="06:00" max="19:00" required>
        </div>

        <div class="mb-4">
            <label for="comentarios_adicionales" class="block text-gray-700 text-sm font-bold mb-2">Comentarios adicionales</label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.comentarios" rows="4"></textarea>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Guardar</button>
        </div>
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

  <TransitionRoot appear :show="isOpenotro" as="template">
    <Dialog as="div" @close="closeModalotro" class="relative z-10">
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
                Reprogramar llamada
              </DialogTitle>
              <div class="mt-2">
              <div class="flex flex-col">
                <form @submit.prevent="submitGestion('otro')">
                <div class="mb-4">
            <label for="nombre_cliente" class="block text-gray-700 text-sm font-bold mb-2">Nombre del cliente</label>
            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.nombre_cliente" readonly required>
        </div>

        <div class="mb-4">
            <label for="fecha_ejecucion" class="block text-gray-700 text-sm font-bold mb-2">Fecha llamada</label>
            <input type="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.fecha" required>
        </div>

        <div class="mb-4">
            <label for="hora_ejecucion" class="block text-gray-700 text-sm font-bold mb-2">Hora llamada</label>
            <input type="time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.hora" min="06:00" max="19:00" required>
        </div>

        <div class="mb-4">
            <label for="comentarios_adicionales" class="block text-gray-700 text-sm font-bold mb-2">Comentarios adicionales</label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.comentarios" rows="4"></textarea>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Guardar</button>
        </div>
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

  <TransitionRoot appear :show="isOpenaveriado" as="template">
    <Dialog as="div" @close="closeModalAveriado" class="relative z-10">
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
                Averiado
              </DialogTitle>
              <div class="mt-2">
              <div class="flex flex-col">
                <form @submit.prevent="submitAveriado()">
                <div class="mb-4">
            <label for="nombre_cliente" class="block text-gray-700 text-sm font-bold mb-2">Nombre del cliente</label>
            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nombre_cliente" name="nombre_cliente" v-model="gestiones.nombre_cliente" readonly>
        </div>

        <div class="mb-4">
            <label for="comentarios_adicionales" class="block text-gray-700 text-sm font-bold mb-2">Comentarios adicionales</label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="comentarios_adicionales" name="comentarios_adicionales"  v-model="gestiones.comentarios" rows="4"></textarea>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Guardar</button>
        </div>
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

  <TransitionRoot appear :show="isOpencompetencia" as="template">
    <Dialog as="div" @close="closeModalCompetencia" class="relative z-10">
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
                Competencia
              </DialogTitle>
              <div class="mt-2">
              <div class="flex flex-col">
                <form @submit.prevent="submitGestion('competencia')">
                <div class="mb-4">
            <label for="nombre_cliente" class="block text-gray-700 text-sm font-bold mb-2">Nombre del cliente</label>
            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.nombre_cliente" readonly required>
        </div>

        <div class="mb-4">
            <label for="fecha_ejecucion" class="block text-gray-700 text-sm font-bold mb-2">Fecha ejecución del servicio por la competencia</label>
            <input type="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.fecha" required>
        </div>

        <div class="mb-4">
            <label for="hora_ejecucion" class="block text-gray-700 text-sm font-bold mb-2">Hora ejecución del servicio por la competencia</label>
            <input type="time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.hora" min="06:00" max="19:00" required>
        </div>

        <div class="mb-4">
            <label for="comentarios_adicionales" class="block text-gray-700 text-sm font-bold mb-2">Comentarios adicionales</label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="gestiones.comentarios" rows="4"></textarea>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Guardar</button>
        </div>
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
</template>