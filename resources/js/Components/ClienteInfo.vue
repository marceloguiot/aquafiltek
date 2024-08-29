<script setup>
import { ref } from 'vue';

import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogTitle,
} from '@headlessui/vue';
const props = defineProps({
  actual: Object,
  ventas: Array
});

const precio = ref({
  id_gestion: '',
  codigo:'',
  nombre_cliente:'',
  precio_ant: '',
  precio_act: '',
  motivo: ''
});

const isOpenedp = ref(false);

const editar_precio = (gestion) =>{
  precio.value.id_gestion = gestion.id;
  precio.value.codigo = gestion.codigo;
  precio.value.precio_ant = gestion.precio;
  precio.value.nombre_cliente = gestion.nombre_cliente;
  isOpenedp.value = true;

}

function closeModalPrecio() {
  isOpenedp.value = false;
}

const submitPrecio = async () =>{
  try {
    const response = await axios.post('/edprecio', precio.value);
    console.log('Datos enviados con éxito:', response.data);
    reset_gestiones();
    
  } catch (error) {
    console.error('Error al enviar los datos:', error);
  }


}
</script>
<template>
                    <table class="table-fixed">
                <thead>
                    <tr class="bg-slate-800 text-white h-12">
                        <th  class="border">Fechas de Ventas Anteriores</th>
                        <th  class="border">Nombre Cliente</th>
                        <th  class="border">Datos Factura</th>
                        <th  class="border">Dirección</th>
                        <th  class="border">Telefonos</th>
                        <th  class="border">Celulares</th>
                        <th  class="border">Observaciones</th>
                        <th  class="border">Campo Libre</th>
                        <th  class="border">Correo</th>
                        <th  class="border">Estado</th>
                        <th  class="border">Info Cisterna</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-slate-600">
                        <td class="text-white border">
                            <div class="flex flex-col">
                                <div v-for="element in props.ventas" class="text-sm">
                                  <div v-if="element.tipo == 'competencia'">
                                    <span class="text-red-400 font-semibold">{{ element.fecha }}</span>
                                  </div>
                                  <div v-else>
                                    {{ element.fecha_acepto }} - ${{ element.precio }} <span class="hover:cursor-pointer text-blue-400" @click="editar_precio(element)">editar</span>
                          
                                  </div> 
                                </div>

                            </div>
                        </td>
                        <td class="text-white border">{{ props.actual.nombre_cliente }}</td>
                        <td class="text-white border">{{ props.actual.datos_factura }}</td>
                        <td class="text-white border text-sm w-[25%]">{{ props.actual.direccion }}</td>
                        <td class="text-white border">
                            <div class="flex flex-col">
                                <div>{{ props.actual.telefono }}</div>
                                <div>{{ props.actual.telefono_oficina }}</div>
                            </div>
                        </td>
                        <td class="text-white border">
                            <div class="flex flex-col">
                                <div>{{ props.actual.celular1 }}</div>
                                <div>{{ props.actual.celular2 }}</div>
                            </div>
                        </td>
                        <td class="text-white border">{{ props.actual.observaciones }}</td>
                        <td class="text-white border"></td>
                        <td class="text-white border">{{ props.actual.correo }}</td>
                        <td class="text-white border">{{ props.actual.estado }}</td>
                        <td class="text-white border">{{ props.actual.informacion_cisterna }}</td>
                    </tr>
                </tbody>
                </table>
                <TransitionRoot appear :show="isOpenedp" as="template">
    <Dialog as="div" @close="closeModalPrecio" class="relative z-10">
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
                Editar precio aceptado
              </DialogTitle>
              <div class="mt-2">
              <div class="flex flex-col">
                <form @submit.prevent="submitPrecio()">
                <div class="mb-4">
            <label for="nombre_cliente" class="block text-gray-700 text-sm font-bold mb-2">Nombre del cliente</label>
            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="precio.nombre_cliente"  required readonly>
        </div>

        <div class="mb-4">
            <label for="fecha_ejecucion" class="block text-gray-700 text-sm font-bold mb-2">Nuevo precio</label>
            <input type="number" min="1" max="10000" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="precio.precio_act"  required>
        </div>

        <div class="mb-4">
            <label for="comentarios_adicionales" class="block text-gray-700 text-sm font-bold mb-2">Comentarios</label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="precio.motivo"  rows="4"></textarea>
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