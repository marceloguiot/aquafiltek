<script setup>
import { ref, onBeforeMount } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

import Modals from '@/Components/Modals.vue';
import ModalGestion from '@/Components/ModalGestion.vue';
import ClienteInfo from '@/Components/ClienteInfo.vue';
import ComentariosHistorico from '@/Components/ComentariosHistorico.vue';
import { usePage } from '@inertiajs/vue3';

const { props } = usePage();

const datos = ref(props.datos);

const actual = ref(datos.value[3]);
const comentarios = ref([]);
const gestionesPast = ref([]);


      const fetchComentarios = async () => {
        const data = { id: actual.value.codigo };
  try {
    const response = await axios.post(route('getComentarios'), data, {
      headers: {
        'Content-Type': 'application/json'
      }
    });
    comentarios.value = response.data;
  } catch (err) {
    console.error(err);
    
  } finally {
    
  }
};

const fetchPasadas = async () => {
  const data = { id: actual.value.codigo };
  try {
    const response = await axios.post(route('getPasadas'), data, {
      headers: {
        'Content-Type': 'application/json'
      }
    });
    gestionesPast.value = response.data;
  } catch (err) {
    console.error(err);
    
  }
}

      onBeforeMount(fetchComentarios);
      onBeforeMount(fetchPasadas);

</script>

<template>
    <Head title="Tablero" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-[94%] mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">Bienvenido</div>
                </div>
                    <!-- Inician modales-->
                    <Modals :actual="actual" />
                    <!-- Terminan modales-->
                <div class="flex flex-row justify-center mt-5 h-40">

                    <div v-for="(dat, index) in datos" class="h-42 w-[14%] flex flex-col">
                        <div v-if="index == 3" class="h-16 text-xs bg-teal-500 overflow-auto text-center content-center border">{{ dat.direccion }}</div>
                        <div v-else class="h-16 text-xs bg-yellow-400 overflow-auto text-center content-center border">{{ dat.direccion }}</div>

                        <div v-if="index == 3" class="h-20 text-sm bg-teal-500 text-center content-center border">{{ dat.nombre_cliente}}</div>
                        <div v-else class="h-20 text-sm bg-yellow-400 text-center content-center border">{{ dat.nombre_cliente}}</div>

                        <div v-if="index == 3" class="h-12 text-sm bg-teal-500 text-center content-center border">{{ dat.estado }}</div>
                        <div v-else class="h-12 text-sm bg-yellow-400 text-center content-center border">{{ dat.estado }}</div>
                    </div>
                </div>
                <ModalGestion  :actual="actual" :scope="'gestion'" />
                <div class="flex flex-col">
                <ClienteInfo :actual="actual" :ventas="gestionesPast" />
                </div>
                <ComentariosHistorico :items="comentarios" />
            </div>
        </div>
    </AuthenticatedLayout>


    
</template>
