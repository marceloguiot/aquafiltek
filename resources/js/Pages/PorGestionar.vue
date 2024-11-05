<script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import { Head } from '@inertiajs/vue3';
  import ModalGestion from '@/Components/ModalGestion.vue';
  import Swal from 'sweetalert2';
  import DataTable from 'datatables.net-vue3';
  import DataTablesCore from 'datatables.net';
  import ClienteInfo from '@/Components/ClienteInfo.vue';
import ComentariosHistorico from '@/Components/ComentariosHistorico.vue';
  
  DataTable.use(DataTablesCore);
  // Definir estados reactivamente
  const clientes = ref([]);
  const loading = ref(true);
  const gestionesPast = ref([]);
  
  const searchValue = ref('');
  
  const actual = ref([]);
  // Función para obtener los clientes por gestionar
  const fetchClientesPorGestionar = async () => {
    try {
      const response = await axios.get('/clientes-por-gestionar');
      clientes.value = response.data;
    } catch (error) {
      console.error('Error al obtener los clientes:', error);
    } finally {
      loading.value = false;
    }
  };
  const handleUpdateEjecutado = (ejecutado) => {
  if(ejecutado == 'true')
{
  Swal.fire({
      title: "¡gestion guardada!",
      text: "La gestión se guardo correctamente.",
      confirmButtonText: "Aceptar",
      icon: "success"
    }).then((result) => {
  /* Read more about isConfirmed, isDenied below */
  if (result.isConfirmed) {
    fetchClientesPorGestionar();
  }
});
  //
}
};

  const seleccionar = (cliente) =>{
    actual.value = cliente;
    fetchPasadas();
  }
  // Llamar a la función al montar el componente
  onMounted(fetchClientesPorGestionar);

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

  const columns = [
  { data: 'codigo', title: 'Código' },
  { data: 'nombre_cliente', title: 'Nombre' },
  { data: 'direccion', title: 'Dirección' },
  { data: 'estado', title: 'Estado' },
  { data: 'id', title: 'Acciones' },
];

const headers = [
   { text: "Código", value: "codigo", sortable: true},
   { text: "Nombre", value: "nombre_cliente", sortable: true},
   { text: "Dirección", value: "direccion", sortable: true},
   { text: "Estado", value: "estado", sortable: true},
   { text: "Acciones", value: "id", sortable: true},
     ];

     const headclass = () =>
{
  return 'text-[16px]';
}
const bodyclass = () =>
{
  return 'text-[15px]';
}

  </script>
<template>
     <Head title="Por gestionar" />

<AuthenticatedLayout>
    <div class="flex flex-col w-5/6  mx-auto mt-10">
      <h1 class="text-lg font-bold mb-4">Clientes Por Gestionar</h1>
  
      <div v-if="loading" class="text-center">Cargando clientes...</div>

      <div class="flex flex-row">
        <label>Buscar:</label>
        <input type="text" class="h-6 text-sm rounded-sm mb-5 ml-2" v-model="searchValue">
      </div>
    
    <EasyDataTable
            :header-item-class-name="headclass"
            :body-item-class-name="bodyclass"
            :rows-per-page="10"
            buttons-pagination
            :headers= "headers"
            :items="clientes"
            alternating
            rowsPerPageMessage="Registros por página"
		        rowsOfPageSeparatorMessage="de"
            emptyMessage="No hay registros que mostrar"
            border-cell
            :search-value="searchValue"
            >
    
            <template #item-id="cliente">
              <span v-if="cliente.id === actual.id">Cliente seleccionado</span>
       <button v-else class="bg-blue-500 text-white px-2 py-1 rounded mx-auto border border-gary-700" @click="seleccionar(cliente)">Gestionar</button>
            </template>
      
            </EasyDataTable>



      <ModalGestion  :actual="actual"  :key="actual.codigo" :scope="'gestion'" @updateEjecutado="handleUpdateEjecutado" />
      <div class="flex flex-col">
                <ClienteInfo :actual="actual" :ventas="gestionesPast" />
                </div>
                <ComentariosHistorico :actual="actual" :items="comentarios" />
    </div>
    </AuthenticatedLayout>
  </template>
  
  
  
  <style scoped>
  /* Estilos específicos del componente */
  @import 'datatables.net-dt';
  </style>
  