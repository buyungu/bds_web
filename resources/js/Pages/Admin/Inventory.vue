<script setup>
import { useSidebar } from '../../composables/useSidebar';
import { router, useForm } from '@inertiajs/vue3';
import Card from '../../Components/Card.vue';
import InputField from '../../Components/InputField.vue';
import PaginationLinks from '../../Components/PaginationLinks.vue';
import DemoHeader from '../../Layouts/DemoHeader.vue';
import Sidebar from '../../Layouts/Sidebar.vue';
import { computed, ref, watch } from 'vue';

const props = defineProps({
    bloodStock: Array,
    bloodData: Object,
    regions: Array,
    regionsWithDistricts: Object, // ✅ Add this
})

const params = route().params;
const form =useForm({
    search: params.search,
    region: params.region,
    district: params.district,

});

const districts = ref([])

// Populate districts if region is preselected (e.g. from URL)
if (form.region) {
    districts.value = props.regionsWithDistricts[form.region] || [];
}

const search = () => {
    router.get(route("admin.inventory"), {
        search: form.search,
        region: params.region,
        district: params.district,
        blood_type: params.blood_type,
    })
};

const selectRegion = () => {
    districts.value = props.regionsWithDistricts[form.region] || [];
    form.district = '';

    router.get(route("admin.inventory"), {
        search: params.search,
        region: form.region,
        district: '',
        blood_type: params.blood_type,
    });

};

const selectDistrict = () => {
    router.get(route("admin.inventory"), {
        search: params.search,
        region: params.region,
        district: form.district,
        blood_type: params.blood_type,
    })
};

const selectBloodType = (type) => {
    router.get(route("admin.inventory"), {
        blood_type: type,
        search: params.search,
        region: params.region,
        district: params.district,
    })
}

const { isSidebarOpen } = useSidebar()

</script>

<template>
    <Head title=" | Inventory Management"/>
    <div :class="[isSidebarOpen ? 'ml-72' : 'ml-0', 'transition-all duration-300 ']">
    <DemoHeader />
    <Sidebar />
    <div class=" p-8">

        <div class="flex items-center justify-between ">
            <div class="flex items-center gap-4 w-1/2">

                <div class="relative w-full rounded-lg">

                    <div class="pointer-event-non absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="grid place-content-center text-sm text-slate-400">
                            <i class="fa-solid fa-city"></i>
                        </span>
                    </div>
                        <select
                            @change="selectRegion"
                            id="region"
                            name="region"
                            v-model="form.region"
                            placeholder="Filter by Region"
                            class="block w-full rounded-md pr-3 pl-9 text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                        >
                            <option 
                                value="" disabled selected>Filter by Region
                            </option>
                            <option v-for="region in regions"
                                :key="region" 
                                :value="region">{{ region }}</option>
                            
                        </select>
                    
                </div>
                <div class="relative w-full rounded-lg">

                    <div class="pointer-event-non absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="grid place-content-center text-sm text-slate-400">
                            <i class="fa-solid fa-city"></i>
                        </span>
                    </div>
                        <select
                            @change="selectDistrict"
                            id="district"
                            name="district"
                            v-model="form.district"
                            placeholder="Filter by District"
                            class="block w-full rounded-md pr-3 pl-9 text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                        >
                            <option 
                                value=""
                            
                                disabled selected>Filter by District
                            </option>
                            <option v-for="district in districts"
                                :key="district" 
                                :value="district">{{ district }}</option>
                            
                        </select>
                    
                </div>
                
            </div>
            
            <div class=" w-1/4">
                
                <form @submit.prevent="search">
                    <InputField
                        label=""
                        icon="magnifying-glass"
                        placeholder="Search Hospital..."
                        v-model="form.search"
                    />
                </form>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 mt-3 mb-6">
                <Link
                    class="px-2 py-[6px] rounded-md bg-blue-500 text-white flex items-center gap-2"
                    v-if="params.search"
                    :href="
                        route('admin.inventory', {
                            ...params,
                            search: null,
                            page: null,
                        })
                    "
                >
                    {{ params.search }}
                    <i class="fa-solid fa-xmark"></i>
                </Link>
                <Link
                    class="px-2 py-[6px] rounded-md bg-blue-500 text-white flex items-center gap-2"
                    v-if="params.blood_type"
                    :href="
                        route('admin.inventory', {
                            ...params,
                            blood_type: null,
                            page: null,
                        })
                    "
                >
                    {{ params.blood_type }}
                    <i class="fa-solid fa-xmark"></i>
                </Link>
                <Link 
                    class="px-2 py-[6px] rounded-md bg-blue-500 text-white flex items-center gap-2"
                    v-if="params.region"
                    :href="route('admin.inventory', { ...params, region: null, page: null })"
                >
                    {{ params.region }}
                    <i class="fa-solid fa-xmark"></i>
                </Link>
                <Link 
                    class="px-2 py-[6px] rounded-md bg-blue-500 text-white flex items-center gap-2"
                    v-if="params.district"
                    :href="route('admin.inventory', { ...params, district: null, page: null })"
                >
                    {{ params.district }}
                    <i class="fa-solid fa-xmark"></i>
                </Link>
                
                
            </div>


        <div class="grid grid-cols-4 gap-6">
            <Card
                @click="selectBloodType(stock.blood_type)"
                v-for="(stock, index) in bloodStock" 
                :key="index"
                :number="stock.total_quantity"
                :name="`Blood ${stock.blood_type}`"
                routeName="admin.inventory"
                :label="stock.blood_type"
                quantity="Unit"
            />
            
        </div>


        <div class="max-w-6xl mx-auto my-8 dark:text-white">
            
            <table class="w-full table-fixed border-collapse overflow-hidden rounded-md text-sm ring-1 ring-slate-300 dark:ring-slate-600 bg-white shadow-lg">
                <thead
                    class="bg-slate-300 text-xs uppercase text-slate-600 dark:text-slate-400 dark:bg-slate-900"
                >
                    <tr>
                        <th class="p-3 text-left">Hospital</th>
                        <th class="p-3 text-left w-1/4">Location</th>
                        <th class="p-3 text-right">Blood type</th>
                        <th class="p-3 w-1/5 text-right">quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="blood in props.bloodData.data" :key="blood.id" class="border-b border-slate-200 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-600 dark:border-slate-600">
                        <td class="p-3">{{ blood.hospital.name }}</td>
                        <td class="p-3 w-1/4">{{ blood.hospital.location.address}}</td>
                        <td class="p-3 text-right">{{ blood.blood_type }}</td>
                        <td class="p-3 w-1/5 text-right pr-10">{{ blood.quantity }}</td>
                        
                    </tr>
                </tbody>
            </table>

            <!-- Pagination Controls -->
            <PaginationLinks :paginator="bloodData" />
        </div>

    </div>
    </div>

</template>
