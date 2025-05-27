<script setup>
import PaginationLinks from '../../Components/PaginationLinks.vue';
import { router } from '@inertiajs/vue3';


defineProps({
    events: Object,
    status: String,
});

const params = route().params;

const selectUser = (id) => {
    router.get(route("events"), {
        user_id: id,
        search: params.search
    })
};

const formatDate = (date) => new Date(date).toLocaleDateString();
</script>

<template>
    
    <div class="max-w-5xl mx-auto">
        
        <!-- Check if there are events -->
         <div v-if="events.data.length">
        <div  class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div 
                v-for="event in events.data" 
                :key="event.id" 
                class="dark:bg-slate-800 dark:text-white p-4 rounded mb-3 border dark:border-slate-700 shadow-lg flex gap-6"
            >
                <!-- Cover Phote -->
                <Link :href="route('events.show', event.id)">
                    <img 
                        :src="event.image ? `/storage/${event.image}` : '/storage/images/events/default.png'" 
                        class="h-full w-28 rounded-md bg-slate-300 object-cover object-center"
                        alt="">
                </Link>
                <div class="w-4/5">
                    <h2 class="text-lg font-bold">{{ event.title.substring(0,50) }}...</h2>
                    <p class="dark:text-gray-300">{{ event.description.substring(0,80) }}...</p>
                    <p class="text-gray-400">{{ formatDate(event.event_date) }} </p>
                    <p class="text-gray-400">Created by: <button @click="selectUser(event.user.id)" class="text-blue-500">{{ event.user.name ?? 'Unknown' }}</button></p>
                    <Link 
                        :href="route('events.show', event.id)" 
                        class="text-blue-400 hover:underline dark:text-blue-300"
                    >
                        View Details
                    </Link>
                </div>
                
            </div>
        </div>
            <!-- Pagination Controls -->
        <PaginationLinks :paginator="events" />
        </div>
        <p v-else class="text-gray-400">No events available.</p>

        
    </div>
</template>

