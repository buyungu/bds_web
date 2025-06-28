<script setup>
import { router } from '@inertiajs/vue3';
import SessionMessage from '../../../Components/SessionMessage.vue';
import PaginationLinks from '../../../Components/PaginationLinks.vue';


const props = defineProps({
    events: Object,
    status: String
});

const deleteEvent = (id) => {
    if (confirm("Are you sure?")) {
        router.delete(route('events.destroy', id))
    };
};


</script>

<template>
    <Head title=" | Events" />

    <SessionMessage :status="status"/>

    <div v-if="events.data.length"> 
        <div class="mb-6">
            <!-- Responsive Table -->
            <div class="overflow-x-auto rounded-md">
                <table
                    class="min-w-[600px] w-full table-fixed border-collapse overflow-hidden rounded-md text-sm ring-1 ring-slate-300 dark:ring-slate-600 bg-white shadow-lg"
                >
                    <thead
                        class="bg-slate-300 text-xs uppercase text-slate-600 dark:text-slate-400 dark:bg-slate-900"
                    >
                        <tr>
                            <th class="w-3/4 p-3 text-left">Event Title</th>
                            <th class="w-1/5 py-3 pr-3 text-right">View</th>
                            <th class="w-1/5 py-3 pr-3 text-right">Edit</th>
                            <th class="w-1/5 py-3 pr-3 text-right">Delete</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="event in events.data"
                            :key="event.id"
                            class="border-b border-slate-200 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-600 dark:border-slate-600"
                        >
                            <td class="w-3/4 p-3 text-left">
                                <div class="flex items-center gap-2">
                                    <img
                                        :src="
                                            event.image
                                                ? `/storage/${event.image}`
                                                : '/storage/images/events/default.png'
                                        "
                                        alt=""
                                        class="h-10 w-10 rounded-full object-cover object-center"
                                    />
                                    <h4 class="font-bold">
                                        {{ event.title }}
                                    </h4>
                                </div>
                            </td>
                            <td class="w-1/5 py-3 pr-3 text-right text-blue-500">
                                <Link :href="route('events.show', event.id)">View</Link>
                            </td>
                            <td class="w-1/5 py-3 pr-3 text-right text-blue-500">
                                <Link :href="route('events.edit', event.id)">Edit</Link>
                            </td>
                            <td class="w-1/5 py-3 pr-3 text-right text-red-500">
                                <button type="button" @click="deleteEvent(event.id)">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div>
            <PaginationLinks :paginator="events" />
        </div>
    </div>

    <div v-else>You have no events!</div>
</template>
