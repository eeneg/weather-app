<script setup>
import mapboxgl from 'mapbox-gl';
import { onMounted, onUnmounted, ref } from 'vue';
mapboxgl.accessToken = import.meta.env.VITE_MapBoxKEY;

const mapContainer = ref('')

const map = ref(null)

const initMap = () => {
    map.value = new mapboxgl.Map({
        container: mapContainer.value,
        style: "mapbox://styles/mapbox/streets-v12", // Replace with your preferred map style
        center: [139.6500, 35.6764],
        zoom: 9,
    })

    map.value.on('load', function() {
        map.value.resize()
    });

}

const resetMapMarkers = () =>{
    if(map.value.getLayer('end')){
        map.value.removeLayer('end')
        map.value.removeSource('end')
    }

    if(map.value.getLayer('route')){
        map.value.removeLayer('route')
        map.value.removeSource('route')
    }
}

const markSingleLocation = (location) => {
    const start = [location.longitude, location.latitude]
    map.value.setCenter(start)
    if(map.value.getLayer('start')) {
        map.value.getSource('start').setData({
            type: 'FeatureCollection',
            features: [
            {
                type: 'Feature',
                properties: {},
                geometry: {
                    type: 'Point',
                    coordinates: start
                }
            }
            ]
        })
    }else{
        map.value.addLayer({
        id: 'start',
        type: 'circle',
        source: {
            type: 'geojson',
            data: {
            type: 'FeatureCollection',
            features: [
                {
                type: 'Feature',
                properties: {},
                geometry: {
                    type: 'Point',
                    coordinates: start
                }
                }
            ]
            }
        },
        paint: {
            'circle-radius': 10,
            'circle-color': '#3887be'
        }
        });
    }
}

const updateMap = (route) => {
    const start = route.waypoints[0]
    const end = route.waypoints[1]
    const geojson = route.routes[0].geometry.coordinates

    map.value.setCenter(start.location)

    if(map.value.getLayer('start')) {
        map.value.getSource('start').setData({
            type: 'FeatureCollection',
            features: [
            {
                type: 'Feature',
                properties: {},
                geometry: {
                    type: 'Point',
                    coordinates: start.location
                }
            }
            ]
        })
    }else{
        map.value.addLayer({
        id: 'start',
        type: 'circle',
        source: {
            type: 'geojson',
            data: {
            type: 'FeatureCollection',
            features: [
                {
                type: 'Feature',
                properties: {},
                geometry: {
                    type: 'Point',
                    coordinates: start.location
                }
                }
            ]
            }
        },
        paint: {
            'circle-radius': 10,
            'circle-color': '#3887be'
        }
        });
    }

    if(map.value.getLayer('end')){
        map.value.getSource('end').setData({
            type: 'FeatureCollection',
            features: [
            {
                type: 'Feature',
                properties: {},
                geometry: {
                type: 'Point',
                coordinates: end.location
                }
            }
            ]
        })
    }else{
        map.value.addLayer({
          id: 'end',
          type: 'circle',
          source: {
            type: 'geojson',
            data: {
              type: 'FeatureCollection',
              features: [
                {
                  type: 'Feature',
                  properties: {},
                  geometry: {
                    type: 'Point',
                    coordinates: end.location
                  }
                }
              ]
            }
          },
          paint: {
            'circle-radius': 10,
            'circle-color': '#f30'
          }
        });
    }

    if(map.value.getSource('route')){
        map.value.getSource('route').setData({
            type: 'Feature',
            properties: {},
            geometry: {
            type: 'LineString',
            coordinates: geojson
            }
        });
    }else{
         map.value.addLayer({
          id: 'route',
          type: 'line',
          source: {
            type: 'geojson',
            data: {
                type: 'Feature',
                properties: {},
                geometry: {
                type: 'LineString',
                coordinates: geojson
                }
            }
          },
          layout: {
            'line-join': 'round',
            'line-cap': 'round'
          },
          paint: {
            'line-color': '#3887be',
            'line-width': 5,
            'line-opacity': 0.75
          }
        });
    }
}

defineExpose({
    resetMapMarkers,
    markSingleLocation,
    updateMap
})

onMounted(() => {
    initMap()
})

onUnmounted(() => {
    map.value.remove()
    map.value = null
})

</script>

<style>
 .map-container {
    flex: 1;
    display: flex;
    height: 100%;
  }
</style>

<template>
    <div ref="mapContainer" class="map-container"></div>
</template>
