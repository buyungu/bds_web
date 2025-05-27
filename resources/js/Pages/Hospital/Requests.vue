<script setup>
import { useSidebar } from '../../composables/useSidebar';
import Container from '../../Components/Container.vue';
import DemoHeader from '../../Layouts/DemoHeader.vue';
import Sidebar from '../../Layouts/Sidebar.vue';
import PaginationLinks from '../../Components/PaginationLinks.vue'
import Card from '../../Components/Card.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    bloodRequests: Object,
    bloodRequestQuantity: Array,
})

const form = useForm({});

const { isSidebarOpen } = useSidebar()


</script>

<template>
    <Head title=" | Blood Requests" />
        <div :class="[isSidebarOpen ? 'ml-72' : 'ml-0', 'transition-all duration-300 ']">

    <DemoHeader />
    <Sidebar />
    <div class=" p-8">

        <div class="grid grid-cols-4 gap-6 mb-6">
            <Card
                v-for="(request, index) in bloodRequestQuantity" 
                :key="index"
                :number="request.total_quantity"
                :name="`Blood ${request.blood_type}`"
                routeName="hospital.inventory"
                :label="request.blood_type"
                quantity="Unit"
            />
            
        </div>

        <div v-if="props.bloodRequests.data.length">
                <div class="mb-6">


                    <!-- Table -->
                    <table
                        class="w-full table-fixed border-collapse overflow-hidden rounded-md text-sm ring-1 ring-slate-300 dark:ring-slate-600 bg-white shadow-lg"
                    >
                        <thead
                            class="bg-slate-300 text-xs uppercase text-slate-600 dark:text-slate-400 dark:bg-slate-900"
                        >
                            <tr>
                                <th class="p-3 w-2/6 text-left">recipient name</th>

                                <th class="p-3 w-2/6 text-left">Location</th>
                                <th class="p-3 w-1/6 text-left">Blood Type</th>
                                <th class="p-3 w-1/6 text-left">quantity (Units)</th>
                                <th class="p-3 w-1/6 text-left">status</th>
                                <th class="p-3 w-1/6 text-left">action</th>
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
                                                request.recipient.avatar
                                                    ? `/storage/${request.recipient.avatar}`
                                                    : '/storage/avatars/default.jpg'
                                            "
                                            alt=""
                                            class="h-10 w-10 rounded-full object-cover object-center"
                                        />
                                        <h4 class="font-bold">
                                            {{ request.recipient.name }}
                                            
                                        </h4>
                                    </div>
                                </td>

                                <td
                                    class="p-3 w-2/6 text-left"
                                >
                                    <p>{{ request.recipient.ward?.name ?? '' }}, {{ request.recipient.ward?.district?.name ?? ''}}, {{ request.recipient.ward?.district?.region?.name ?? '' }}</p>
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

                                <td class=" w-1/6 flex gap-2 text-center">
                                    <button 
                                        v-if="request.status !== 'fulfilled' && request.status !== 'canceled'"
                                        @click="form.post(route('blood.assign', request.id))"
                                        :disabled="form.processing"
                                        class="px-2 py-1 mt-3 bg-green-600 rounded-full text-sm text-white font-bold disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <i class="fa-solid fa-check"></i>
                                    </button>

                                    <Link 
                                        :href="route('request.view', request.id)"
                                        class="px-2 py-1 mt-3 bg-blue-600 rounded-full text-sm text-white font-bold"
                                    >
                                        <i class="fa-solid fa-eye"></i>
                                    </Link>
                                </td>
                                
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div>
                    <PaginationLinks :paginator="bloodRequests" />
                </div>
            </div>

        <div v-else>No bloodRequests!!</div>
    </div>
    </div>
</template>
