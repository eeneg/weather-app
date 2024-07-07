<script setup>
import { Head } from '@inertiajs/vue3';
import { ArrowTrendingUpIcon, TrashIcon, MapPinIcon } from '@heroicons/vue/24/solid';
import TextInput from '@/Components/TextInput.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import NearSelection from '@/Components/Map/NearSelection.vue';
import LocationInfo from '@/Components/Map/LocationInfo.vue';
import Map from '@/Components/Map/Map.vue';
import '../../../node_modules/mapbox-gl/dist/mapbox-gl.css';
import { ref } from 'vue';
import axios from 'axios';
import _ from 'lodash';

// Reactive references for various states and input values
const directionInputMode = ref(false);
const startLocationInput = ref(false);
const nearStartAddress = ref('');
const endLocationInput = ref(false);
const nearEndAddress = ref('');
const startingAddressSearch = ref('');
const endAddressSearch = ref('');
const selectedPlaceText = ref({ name: '', address: '' });
const routeSearch = ref({ from: {}, to: {}, mode: 'cycling' });
const routeDetails = ref(null);
const selectedPlaceDetails = ref({});
const loading = ref(true);
const searchResults = ref([]);
const updateSmallMapEvent = ref(null);
const updateMapEvent = ref(null);
const hideFromNearSelection = ref(false);
const hideToNearSelection = ref(false);

// Function to toggle direction input mode
const toggleDirectionInput = (show) => {
    directionInputMode.value = show;
    if (!show) {
        searchResults.value = [];
        endLocationInput.value = false;
        endAddressSearch.value = '';
        routeDetails.value = null;
        updateSmallMapEvent.value.resetMapMarkers();
        updateMapEvent.value.resetMapMarkers();
    }
};

// Debounced search input handlers
const handleAddressSearchInput = _.debounce((isStart) => {
    startLocationInput.value = isStart;
    endLocationInput.value = !isStart;
    const searchValue = isStart ? startingAddressSearch.value : endAddressSearch.value;
    const nearValue = isStart ? nearStartAddress.value : nearEndAddress.value;
    searchPlace(searchValue, nearValue);
}, 500);

const startingAddressSearchInput = () => handleAddressSearchInput(true);
const endAddressSearchInput = () => handleAddressSearchInput(false);

// Function to select a place from search results and update the input values
const selectPlace = (data) => {
    const { primary, secondary } = data.text;
    const { latitude, longitude } = data.place.geocodes.main;

    if (startLocationInput.value && !endLocationInput.value) {
        startingAddressSearch.value = primary;
        routeSearch.value.from = { latitude, longitude };
        searchPlaceInformation(data.place.fsq_id, { latitude, longitude });
        updateSmallMapEvent.value.markSingleLocation({ latitude, longitude });
        updateMapEvent.value.markSingleLocation({ latitude, longitude });
    } else if (endLocationInput.value && !startLocationInput.value) {
        endAddressSearch.value = primary;
        routeSearch.value.to = { latitude, longitude };
        getDirections();
    }

    selectedPlaceText.value = { name: primary, address: secondary };
    searchResults.value = [];
};

// Function to fetch directions and update the map
const getDirections = () => {
    routeDetails.value = null;
    axios.post(`/search-placeDirection`, routeSearch.value)
        .then(response => {
            routeDetails.value = response.data;
            updateSmallMapEvent.value.updateMap(routeDetails.value);
            updateMapEvent.value.updateMap(routeDetails.value);
        })
        .catch(error => {
            console.log(error);
        });
};

// Function to update mode of transport
const updateDirectionMode = (mode) => {
    routeSearch.value.mode = mode;
    getDirections();
};

// Function to fetch place details from an API and update the state
const searchPlaceInformation = (id, ll) => {
    axios.get(`/search-placeDetails?query=${id}&lat=${ll.latitude}&long=${ll.longitude}`)
        .then(response => {
            selectedPlaceDetails.value = response.data;
            loading.value = false;
        })
        .catch(error => {
            console.log(error);
        });
};

// Function to perform a search for places based on a query and location
const searchPlace = (query, near) => {
    axios.get(`/search-autoComplete?near=${near}&query=${query}`)
        .then(response => {
            searchResults.value = response.data.results;
        })
        .catch(error => {
            console.log(error);
        });
};

// Function to handle near search based on mode ('from' or 'to') and update the state accordingly
const nearSearch = (mode, data) => {
    const isFrom = mode === 'from';
    const addressSearch = isFrom ? startingAddressSearch.value : endAddressSearch.value;
    const nearAddress = isFrom ? nearStartAddress : nearEndAddress;

    nearAddress.value = data.value;
    if (addressSearch) {
        searchPlace(addressSearch, nearAddress.value);
    }
};

// Function to toggle selection visibility based on mode ('from' or 'to')
const toggleSelectionVisibility = (mode, show) => {
    if (mode === 'from') {
        hideFromNearSelection.value = !show;
    } else if (mode === 'to') {
        hideToNearSelection.value = !show;
    }
};

// Function to handle trending location search and update the state
const trendingLocationSearch = (data) => {
    startingAddressSearch.value = data.name;
    selectedPlaceText.value = {
        name: data.name,
        address: `${data.location.address}, ${data.location.city}`
    };
    updateSmallMapEvent.value.markSingleLocation({ latitude: data.location.lat, longitude: data.location.lng });
    updateMapEvent.value.markSingleLocation({ latitude: data.location.lat, longitude: data.location.lng });
    searchPlaceInformation(data.id, { latitude: data.location.lat, longitude: data.location.lng });
};
</script>

<template>
    <Head title="Dashboard" />
    <div class="flex flex-row w-full">
        <div class="max-sm:w-full sm:border-r-2 max-w-96 min-w-96 w-96 h-screen flex flex-col">
            <div class="relative w-full">
                <div class="flex flex-col p-1">
                    <div class="flex flex-row w-full p-2 space-x-1">
                        <div class="grow" :class="{'hidden': hideFromNearSelection}">
                            <InputLabel>Near</InputLabel>
                            <NearSelection @passData="nearSearch('from', $event)" />
                        </div>
                        <div class="grow w-full">
                            <InputLabel>Search</InputLabel>
                            <TextInput class="w-full drop-shadow-md" @input="startingAddressSearchInput" @focus="toggleSelectionVisibility('from', false)" @blur="toggleSelectionVisibility('from', true)" v-model="startingAddressSearch" />
                        </div>
                        <div class="grow content-end">
                            <SecondaryButton class="drop-shadow-md" @click="toggleDirectionInput(true)">
                                <ArrowTrendingUpIcon class="h-6 w-4" />
                            </SecondaryButton>
                        </div>
                    </div>
                    <div class="flex flex-row w-full p-2 space-x-1" v-if="directionInputMode">
                        <div class="grow" :class="{'hidden': hideToNearSelection}">
                            <NearSelection @passData="nearSearch('to', $event)" />
                        </div>
                        <div class="grow w-full">
                            <TextInput class="w-full drop-shadow-md" @input="endAddressSearchInput" @focus="toggleSelectionVisibility('to', false)" @blur="toggleSelectionVisibility('to', true)" v-model="endAddressSearch" />
                        </div>
                        <div class="grow content-end">
                            <DangerButton class="h-full drop-shadow-md" @click="toggleDirectionInput(false)">
                                <TrashIcon class="h-6 w-4" />
                            </DangerButton>
                        </div>
                    </div>
                </div>
                <div class="absolute z-10 w-full bg-slate-100 flex px-4 drop-shadow-md" v-if="searchResults.length > 0">
                    <div class="max-h-96 h-96 w-full px-3 rounded-lg py-2 overflow-auto">
                        <div class="flex flex-row hover:bg-gray-300 cursor-pointer" v-for="places in searchResults" @click="selectPlace(places)">
                            <div class="flex items-center justify-center p-2">
                                <MapPinIcon class="h-4" />
                            </div>
                            <div class="flex flex-col items-left p-2 grow">
                                <div class="w-full">
                                    {{ places.text.primary }}
                                </div>
                                <div class="w-full text-xs text-gray-500">
                                    {{ places.text.secondary }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sm:hidden grow border p-2">
                <Map ref="updateSmallMapEvent" :route="routeDetails"></Map>
            </div>

            <div class="rounded-lg max-sm:max-h-[15rem] h-full border overflow-auto" v-if="directionInputMode && routeDetails">
                <div class="flex py-4 px-2 space-x-5 justify-center">
                    <SecondaryButton @click="updateDirectionMode('cycling')">Cycling</SecondaryButton>
                    <SecondaryButton @click="updateDirectionMode('driving')">Driving</SecondaryButton>
                </div>
                <div class="text-xl p-3">
                    Trip Instructions:
                </div>
                <div class="relative border mt-1 rounded-md p-3" v-for="(step, i) in routeDetails.routes[0].legs[0].steps" :key="i">
                    {{ i + 1 }}. {{ step.maneuver.instruction }}
                </div>
            </div>

            <div class="rounded-lg max-sm:max-h-[20rem] h-full" v-if="selectedPlaceDetails && !directionInputMode && !loading">
                <div class="flex flex-col max-h-full relative p-2">
                    <div class="w-full px-2">
                        <div>
                            {{ selectedPlaceText.name }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ selectedPlaceText.address }}
                        </div>
                    </div>
                    <div class="h-full">
                        <LocationInfo @passData="trendingLocationSearch" :data="selectedPlaceDetails" />
                    </div>
                </div>
            </div>
        </div>
        <div class="max-sm:hidden h-screen w-full">
            <Map ref="updateMapEvent" :route="routeDetails"></Map>
        </div>
    </div>
</template>
