<template>
    <div class="relative py-3 sm:px-0">
      <TabGroup>
        <TabList class="flex space-x-1 rounded-xl bg-blue-900 p-1">
          <Tab
            v-for="category in categories"
            as="template"
            :key="category"
            v-slot="{ selected }"
          >
            <button
              :class="[
                'w-full rounded-lg py-2.5 text-sm font-medium leading-5',
                'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
                selected
                  ? 'bg-white text-blue-700 shadow'
                  : 'text-blue-100 hover:bg-white/[0.12] hover:text-white',
              ]"
            >
              {{ category }}
            </button>
          </Tab>
        </TabList>

        <TabPanels class="mt-2">
          <TabPanel
            :class="[
              'rounded-xl bg-white p-3',
              'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
            ]"
          >

            <div class="flex-1 relative grid grid-cols-4 gap-4 w-full max-sm:h-[11rem] h-[31rem] overflow-auto">
                <div class="text-sm text-gray-400" v-if="props.data.photos.length == 0">
                    No photos..
                </div>
                <div
                    v-for="photo, idx in props.data.photos"
                    class="rounded-md flex-1 mx-auto ring-blue-400 focus:z-10 focus:outline-none focus:ring-2"
                >
                    <img class="w-24 h-24" :src="photo.prefix+'original'+photo.suffix"/>
                </div>
            </div>

          </TabPanel>
          <TabPanel
            :class="[
              'rounded-xl bg-white p-3',
              'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
            ]"
          >

            <div class="p-1 w-full rounded max-sm:h-[11rem] h-[31rem] overflow-auto">
                <div class="flex flex-row">
                    <div class="flex pl-4 py-2 flex-col grow">
                        <div class="text-4xl">
                            {{ data.weather.weather[0].main }}
                        </div>
                        <div class="text-sm text-gray-500">
                            Description: {{ data.weather.weather[0].description }}
                        </div>
                        <div class="text-sm text-gray-500">
                            Temp: {{ data.weather.main.temp }}°C
                        </div>
                        <div class="text-sm text-gray-500">
                            Humidity: {{ data.weather.main.humidity }}%
                        </div>
                        <div class="text-sm text-gray-500">
                            Wind: {{ data.weather.wind.speed }}m/s
                        </div>
                    </div>
                    <div class="flex items-center grow">
                        <img class="" :src="weatherIcon+data.weather.weather[0].icon+'@2x.png'" alt="">
                    </div>
                </div>
            </div>

          </TabPanel>
          <TabPanel
            :class="[
              'rounded-xl bg-white p-3',
              'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
            ]"
          >
            <div class="p-1 w-full max-sm:h-[11rem] h-[31rem] overflow-auto">
                <div v-if="data.related.response.venues.length == 0">
                    <div class=" text-sm text-gray-400">
                        No Related Trending Venues..
                    </div>
                    <div class=" text-sm text-gray-400">
                        Below is a sample view if Results are EMPTY
                    </div>
                    <div>
                        <ul>
                            <li
                            v-for="place in 1"
                            :key="place.id"
                            @click="sendData(
                                {
                                    id: '5982fe6ce1f0aa182c3dd82a',
                                    name: 'Le Sample',
                                    location: {
                                        address: '猿楽町5-10, Shibuya,',
                                        city: 'Tokyo',
                                        lat: 35.652296,
                                        lng: 139.703644
                                    }
                                }
                            )"
                            class="relative border mt-1 rounded-md p-3 hover:bg-gray-100"
                            >
                                <h3 class="text font-medium leading-5 truncate">
                                    Sample Place Name
                                </h3>

                                <ul
                                    class="mt-1 flex space-x-1 text-xs font-normal leading-4 text-gray-500"
                                >
                                    <li>Address</li>
                                    <li>&middot;</li>
                                    <li>City</li>
                                    <li>&middot;</li>
                                    <li>Country</li>
                                </ul>

                                <a
                                    href="#"
                                    :class="[
                                    'absolute inset-0 rounded-md',
                                    'ring-blue-400 focus:z-10 focus:outline-none focus:ring-2',
                                    ]"
                                />
                            </li>
                        </ul>
                    </div>
                </div>
                <ul>
                    <li
                    v-for="place in data.related.response.venues"
                    :key="place.id"
                    @click="sendData(place)"
                    class="relative border mt-1 rounded-md p-3 hover:bg-gray-100"
                    >
                        <h3 class="text font-medium leading-5 truncate">
                            {{ place.name }}
                        </h3>

                        <ul
                            class="mt-1 flex space-x-1 text-xs font-normal leading-4 text-gray-500"
                        >
                            <li>{{ place.location.address }}</li>
                            <li>&middot;</li>
                            <li>{{ place.location.city }}</li>
                            <li>&middot;</li>
                            <li>{{ place.location.country }}</li>
                        </ul>

                        <a
                            href="#"
                            :class="[
                            'absolute inset-0 rounded-md',
                            'ring-blue-400 focus:z-10 focus:outline-none focus:ring-2',
                            ]"
                        />
                    </li>
                </ul>
            </div>

          </TabPanel>
        </TabPanels>
      </TabGroup>
    </div>
  </template>

  <script setup>
  import { ref } from 'vue'
  import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'

  const props = defineProps({data: Object})

  const emits = defineEmits(['passData'])

  const sendData = (place) => {
    emits('passData', place)
  }

  const categories = ref(['Photos', 'Weather', 'Trending'])

  const weatherIcon = 'https://openweathermap.org/img/wn/'

</script>
