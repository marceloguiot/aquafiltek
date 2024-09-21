<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';
import { Loader } from '@googlemaps/js-api-loader';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ModalGestion from '@/Components/ModalGestion.vue';
import { Head } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const map = ref(null);
const clientes = ref([]); // Variable reactiva para almacenar los registros obtenidos de /gestiones
const actual = ref([]);

// Función para hacer la solicitud a la URL /gestiones
const fetchClientes = async () => {
  try {
    const response = await axios.get('/gestiones');
    clientes.value = response.data.proximas.original; // Guardar los registros en la variable reactiva
  } catch (error) {
    console.error('Error al obtener los registros:', error);
  }
};

// Función para convertir el string de coordenadas en un objeto { lat, lng }
const parseCoordinates = (coordenadas) => {
  const [lat, lng] = coordenadas.split(',').map(coord => parseFloat(coord.trim()));
  return { lat, lng };
};

// Función para inicializar el mapa y centrarlo en la primera ubicación
const initMap = (initialCoordinates) => {
  const mapOptions = {
    center: { lat: initialCoordinates.lat, lng: initialCoordinates.lng },
    zoom: 12,
  };
  map.value = new google.maps.Map(document.querySelector('.map'), mapOptions);
};

// Función para añadir marcadores al mapa usando los datos de clientes
const addMarkers = (mapInstance) => {
  clientes.value.forEach(cliente => {
    const { nombre_cliente, coordenadas, direccion, estado } = cliente; // Suponemos que `coordenadas` es un string
    const { lat, lng } = parseCoordinates(coordenadas); // Parsear las coordenadas

    const marker = new google.maps.Marker({
      position: { lat, lng },
      map: mapInstance,
      title: nombre_cliente, // Nombre del cliente
    });

    // Contenido de la ventana de información con un botón
    const infoWindowContent = `
      <div class="flex flex-col">
        <span class="text-xs">${nombre_cliente}</span>
        <span class="text-xs">${direccion}</span>
        <span class="text-xs">${estado}</span>
        <button onclick="alertCity('${nombre_cliente}', '${cliente}')" class="bg-teal-600 rounded-sm text-white font-semibold text-xs px-2 py-1 hover:bg-teal-500">Seleccionar cliente</button>
      </div>
    `;

    // Crear la ventana de información
    const infoWindow = new google.maps.InfoWindow({
      content: infoWindowContent,
    });

    // Mostrar la ventana de información cuando se haga clic en el marcador
    marker.addListener('click', () => {
      infoWindow.open(mapInstance, marker);
    });
  });
};

// Función para mostrar la alerta con el nombre del cliente
window.alertCity = function(clienteName, cliente) {
  actual.value = cliente;
  alert(`Cliente seleccionado: ${clienteName}`);
};

// Hacer la solicitud de los registros y cargar el mapa una vez montado el componente
onMounted(async () => {
  await fetchClientes(); // Hacer la solicitud a /gestiones antes de cargar el mapa

  const loader = new Loader({
    apiKey: 'AIzaSyB1pBMfBjAmmtfqzCwQAa_6TOjq3Eq_jB4',
    version: 'weekly',
  });

  loader.load().then(() => {
    if (clientes.value.length > 0) {
      // Obtener las coordenadas del primer cliente
      const initialCoordinates = parseCoordinates(clientes.value[0].coordenadas);
      initMap(initialCoordinates); // Inicializar el mapa centrado en el primer cliente
      addMarkers(map.value); // Añadir los marcadores al mapa
    }
  });
});

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
    location.reload();
  }
});
  //
}
};

</script>

<template>
  <Head title="Tablero" />

  <AuthenticatedLayout>
    <div class="flex flex-col w-5/6 mx-auto">
      <h1 class="text-lg font-bold mb-4 mt-6">Gestión por mapa</h1>
      <div class="map-container">
        <div ref="map" class="map"></div>
      </div>
      <ModalGestion  :actual="actual"  :key="actual.codigo" :scope="'gestion'" @updateEjecutado="handleUpdateEjecutado" />
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.map-container {
  width: 100%;
  height: 100vh;
}

.map {
  margin: 0px auto;
  margin-top: 20px;
  width: 100%;
  height: 100%;
}
</style>
