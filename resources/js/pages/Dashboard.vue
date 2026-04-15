<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import { dashboard } from '@/routes';
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

const devices = ref([]);
let interval = null;


const fetchStatus = async () => {
  try {
    const response = await axios.get('/api/monitors');
    devices.value = response.data;
  } catch (error) {
    console.error("Error fetching monitor status:", error);
  }
};

const formatDate = (dateString) => {
    if (!dateString) return 'Never';
    return new Date(dateString).toLocaleTimeString();
};

onMounted(() => {
  fetchStatus();
  // Refresh data every 5 seconds to keep the dashboard current
  interval = setInterval(fetchStatus, 5000);
});

onUnmounted(() => {
  clearInterval(interval);
});


defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Dashboard" />

    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
    >
        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div v-for="device in devices" :key="device.id" 
                class="p-4 rounded-lg shadow-md border-t-4 transition-all duration-500"
                :class="device.has_power ? 'bg-green-50 border-green-500' : 'bg-red-50 border-red-500'">
            
            <div class="flex justify-between items-center">
                <h3 class="font-bold text-gray-800">{{ device.device_name }}</h3>
                <span :class="device.has_power ? 'bg-green-500' : 'bg-red-500 animate-ping'" 
                    class="h-3 w-3 rounded-full"></span>
            </div>

            <p class="text-2xl mt-2 font-black" :class="device.has_power ? 'text-green-700' : 'text-red-700'">
                {{ device.has_power ? 'ONLINE' : 'OUTAGE' }}
            </p>

            <p class="text-xs text-gray-500 mt-2">
                Last Seen: {{ formatDate(device.last_seen_at) }}
            </p>
            </div>
        </div>
    </div>
</template>
