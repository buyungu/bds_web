<script setup>
import { useSidebar } from '../../composables/useSidebar';
import Container from '../../Components/Container.vue';
import DemoHeader from '../../Layouts/DemoHeader.vue';
import Sidebar from '../../Layouts/Sidebar.vue';
import PaginationLinks from '../../Components/PaginationLinks.vue'
import Card from '../../Components/Card.vue';

const props = defineProps({
    registeredDonors: Object,
})

const { isSidebarOpen } = useSidebar()

</script>

<template>
    <Head title=" | Blood Requests" />
        <div :class="[isSidebarOpen ? 'ml-72' : 'ml-0', 'transition-all duration-300 ']">

    <DemoHeader />
    <Sidebar />
    <div class="p-8">

        <div v-if="props.registeredDonors.data.length">
                <div class="mb-6">


                    <!-- Table -->
                    <table
                        class="w-full table-fixed border-collapse overflow-hidden rounded-md text-sm ring-1 ring-slate-300 dark:ring-slate-600 bg-white shadow-lg"
                    >
                        <thead
                            class="bg-slate-300 text-xs uppercase text-slate-600 dark:text-slate-400 dark:bg-slate-900"
                        >
                            <tr>
                                <th class="p-3 w-2/6 text-left"> name</th>

                                <th class=" p-3 w-2/6 text-left">email</th>
                                <th class="p-3 w-2/6 text-left">Location</th>
                                <th class="p-3 w-1/6 text-left">Blood Type</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="donor in props.registeredDonors.data"
                                :key="donor.id"
                                class="border-b border-slate-200 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-600 dark:border-slate-600"
                            >
                                <td class=" p-3 w-2/6 text-left">
                                    
                                    <div class="flex items-center gap-2">
                                        <img
                                            :src="
                                                donor.avatar
                                                    ? `/storage/${donor.avatar}`
                                                    : '/storage/avatars/default.jpg'
                                            "
                                            alt=""
                                            class="h-10 w-10 rounded-full object-cover object-center"
                                        />
                                        <h4 class="font-bold">
                                            {{ donor.name }}
                                            
                                        </h4>
                                    </div>
                                
                                </td>

                                <td
                                    class="p-3 w-2/6 text-left "
                                >
                                    <a :href="`mailto:${donor.email}`" class="text-blue-500">
                                        {{ donor.email }}
                                    </a>
                                </td>
                                <td
                                    class="p-3 w-2/6 text-left"
                                >
                                    <p>{{ donor.ward?.name ?? '' }}</p>
                                </td>
                                <td
                                    class="py-3 pl-6 w-1/6 text-left "
                                >
                                    {{ donor.blood_type }}
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>
                <div>
                    <PaginationLinks :paginator="registeredDonors" />
                </div>
            </div>

        <div v-else>No Registered Donor near your Hospital!!</div>
    </div>
    </div>
</template>
