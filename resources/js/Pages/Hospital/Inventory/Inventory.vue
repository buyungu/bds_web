<script setup>
import { useSidebar } from '../../../composables/useSidebar';
import { useForm, usePage } from '@inertiajs/vue3';
import Card from '../../../Components/Card.vue';
import PaginationLinks from '../../../Components/PaginationLinks.vue';
import SessionMessage from '../../../Components/SessionMessage.vue';
import DemoHeader from '../../../Layouts/DemoHeader.vue';
import Sidebar from '../../../Layouts/Sidebar.vue';

const form = useForm({});

const props = defineProps({
    bloodStock: Object,
    bloodType: Array,
});

const error = usePage().props.flash.error;
const status = usePage().props.flash.status;

const formatDate = (date) => new Date(date).toLocaleDateString();

const deleteInventory = (id) => {
    if (confirm("Are you sure to delete this blood stock?")) {
        form.delete(route('inventory.destroy', id))
    };
};

const { isSidebarOpen } = useSidebar()

</script>

<template>
    <Head title=" | Blood Inventory" />
    <div :class="[isSidebarOpen ? 'ml-72' : 'ml-0', 'transition-all duration-300 ']">
        <DemoHeader />
        <Sidebar />
        <div class="p-4 sm:p-8">
            <SessionMessage :status="status"/>

            <!-- Responsive Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 mb-8">
                <Card
                    @click=""
                    v-for="(stock, index) in bloodType" 
                    :key="index"
                    :number="stock.total_quantity"
                    :name="`Blood ${stock.blood_type}`"
                    routeName="hospital.inventory"
                    :label="stock.blood_type"
                    quantity="Unit"
                />
            </div>

            <Link 
                :href="route('inventory.create')"
                class="block w-full sm:w-auto text-center px-3 py-2 text-white bg-blue-600 rounded-md font-bold text-sm mb-6"
            >
                Add Inventory
            </Link>

            <div class="max-w-6xl mx-auto my-8 dark:text-white" v-if="props.bloodStock.data.length">
                <div class="overflow-x-auto rounded-md">
                    <table class="min-w-[600px] w-full table-fixed border-collapse overflow-hidden rounded-md text-sm ring-1 ring-slate-300 dark:ring-slate-600 bg-white shadow-lg">
                        <thead class="bg-slate-300 text-xs uppercase text-slate-600 dark:text-slate-400 dark:bg-slate-900">
                            <tr>
                                <th class="p-3 text-left">Source</th>
                                <th class="p-3 text-left">Blood type</th>
                                <th class="p-3 text-left">quantity</th>
                                <th class="p-3 text-left">Exipiry Date</th>
                                <th class="p-3 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="blood in props.bloodStock.data" :key="blood.id" class="border-b border-slate-200 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-600 dark:border-slate-600">
                                <td class="p-3 ">{{ blood.source }}</td>
                                <td class="p-3 ">{{ blood.blood_type }}</td>
                                <td class="p-3 ">{{ blood.quantity }}</td>
                                <td class="p-3 ">{{ formatDate(blood.expiry_date) }}</td>
                                <td class="p-3 space-x-2">
                                    <Link :href="route('inventory.edit',blood.id)" class=" text-blue-500 font-medium text-sm dark:text-blue-300">Edit</Link>                    
                                    <button type="button" @click="deleteInventory(blood.id)"
                                        class=" text-red-500 font-medium text-sm dark:text-red-300"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination Controls -->
                <PaginationLinks :paginator="bloodStock" />
            </div>
            <div v-else>
                <p>No blood available!!</p>
            </div>
        </div>
    </div>
</template>
