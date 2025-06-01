<script setup>
import { useSidebar } from '../../composables/useSidebar';
import DemoHeader from '../../Layouts/DemoHeader.vue';
import Sidebar from '../../Layouts/Sidebar.vue';
import PaginationLinks from '../../Components/PaginationLinks.vue'
import Card from '../../Components/Card.vue';
import SessionMessage from '../../Components/SessionMessage.vue';

const props = defineProps({
    bloodRequests: Object,
    status: String,
})

const { isSidebarOpen } = useSidebar()

</script>

<template>
    <Head title=" | Blood Requests" />
        <div :class="[isSidebarOpen ? 'ml-72' : 'ml-0', 'transition-all duration-300 ']">

    <DemoHeader />
    <Sidebar />
    <div class="p-8">

        <div v-if="props.bloodRequests.data.length">
                <div class="mb-6">

                    <SessionMessage :status="status" />

                    <!-- Table -->
                    <table
                        class="w-full table-fixed border-collapse overflow-hidden rounded-md text-sm ring-1 ring-slate-300 dark:ring-slate-600 bg-white shadow-lg"
                    >
                        <thead
                            class="bg-slate-300 text-xs uppercase text-slate-600 dark:text-slate-400 dark:bg-slate-900"
                        >
                            <tr>
                                <th class="p-3 w-2/6 text-left">hospital </th>

                                <th class="p-3 w-2/6 text-left">Location</th>
                                <th class="p-3 w-1/6 text-left">Blood Type</th>
                                <th class="p-3 w-1/6 text-left">quantity </th>
                                <th class="p-3 w-1/6 text-left">status</th>
                                <th class="p-3 w-1/6 text-left">urgency</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="request in props.bloodRequests.data"
                                :key="request.id"
                                class="border-b border-slate-200 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-600 dark:border-slate-600"
                            >
                                <td class=" p-3 w-2/6 text-left">
                                    
                                    <div class="flex items-center gap-2">
                                        <img
                                            :src="
                                                request.hospital.avatar
                                                    ? `/storage/${request.hospital.avatar}`
                                                    : '/storage/avatars/default.jpg'
                                            "
                                            alt=""
                                            class="h-10 w-10 rounded-full object-cover object-center"
                                        />
                                        <h4 class="font-bold">
                                            {{ request.hospital.name }}
                                            
                                        </h4>
                                    </div>
                                
                                </td>

                                
                                <td
                                    class="p-3 w-2/6 text-left"
                                >
                                    <p>{{ request.hospital.location.address}}</p>
                                </td>
                                <td
                                    class="py-3 pl-6 w-1/6 text-left "
                                >
                                    {{ request.blood_type }}
                                </td>

                                <td class="p-3 w-1/6 text-left ">
                                    {{ request.quantity }}
                                </td>
                                <td class="p-3 w-1/6 text-left ">
                                    {{ request.status }}
                                </td>
                                <td class="p-3 w-1/6 text-left ">
                                    {{ request.urgency }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div>
                    <PaginationLinks :paginator="bloodRequests" />
                </div>
            </div>

        <div v-else>No blood requests!!</div>
    </div>
    </div>
</template>
