<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import { ref } from 'vue';

const emits = defineEmits(['passData'])

const selected = ref('')

const passData = () => {
    emits('passData', selected);
}

</script>

<template>
    <select class="rounded-lg border-gray-300 drop-shadow-md" v-model="selected" @change="passData">
        <option value="35.6764,139.6500">Tokyo</option>
        <option value="35.4437,139.6380">Yokohama</option>
        <option value="35.0116,135.7681">Kyoto</option>
        <option value="34.6937,135.5023">Osaka</option>
        <option value="43.0618,141.3545">Sapporo</option>
        <option value="35.1815,136.9066">Nagoya</option>
    </select>
</template>
