<script setup>
import { useSidebar } from '../../composables/useSidebar';
import { router, useForm } from '@inertiajs/vue3';
import DemoHeader from '../../Layouts/DemoHeader.vue';
import Sidebar from '../../Layouts/Sidebar.vue';
import EventTable from '../Events/Components/EventTable.vue';
import InputField from '../../Components/InputField.vue';


defineProps({
    events: Object,
    status: String,
});

const params = route().params;
const form = useForm({ search: params.search });

const search = () => {
    router.get(route("hospital.events"), {
        search: form.search,
    })
}

const { isSidebarOpen } = useSidebar()

</script>

<template>
    <Head title=" | Manage Events "/>
    <div :class="[isSidebarOpen ? 'ml-72' : 'ml-0', 'transition-all duration-300 ']">
        <DemoHeader />
        <Sidebar />
        <div class="p-4 sm:p-8 space-y-4">
            <h1 class="text-2xl font-bold mb-4">Events</h1>
            <!-- Responsive Actions Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                <Link 
                    :href="route('events.create')"
                    class="px-3 py-2 text-white bg-blue-600 rounded-md font-bold text-sm w-full sm:w-auto text-center"
                >
                    Add Event
                </Link>
                <div class="flex flex-col sm:flex-row items-stretch gap-2 w-full sm:w-auto">
                    <Link
                        class="px-2 py-[6px] rounded-md bg-blue-500 text-white flex items-center gap-2"
                        v-if="params.search"
                        :href="route('hospital.events', { ...params, search: null, page: null })"
                    >
                        {{ params.search }}
                        <i class="fa-solid fa-xmark"></i>
                    </Link>
                    <form @submit.prevent="search" class="w-full sm:w-auto">
                        <InputField
                            label=""
                            icon="magnifying-glass"
                            placeholder="Search..."
                            v-model="form.search"
                        />
                    </form>
                </div>
            </div>
            <SessionMessage :status="status" />
            <EventTable :events="events" :status="status" />
        </div>
    </div>
</template>
