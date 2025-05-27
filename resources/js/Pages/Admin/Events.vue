<script setup>
import { useSidebar } from '../../composables/useSidebar';
import { router, useForm } from '@inertiajs/vue3';
import DemoHeader from '../../Layouts/DemoHeader.vue';
import Sidebar from '../../Layouts/Sidebar.vue';
import SessionMessage from '../../Components/SessionMessage.vue';
import InputField from '../../Components/InputField.vue';
import PaginationLinks from '../../Components/PaginationLinks.vue';


const props = defineProps({
    events: Object,
    status: String,
});

const params = route().params;
const form = useForm({ search: params.search });

const userName = params.user_id ? props.events.data.find(i => i.created_by === Number(params.user_id)).user.name : null;

const search = () => {
    router.get(route("admin.events"), {
        search: form.search,
        user_id: params.user_id
    })
}

const selectUser = (id) => {
    router.get(route("admin.events"), {
        user_id: id,
        search: params.search
    })
};

const deleteEvent = (id) => {
    if (confirm("Are you sure?")) {
        router.delete(route('events.destroy', id))
    };
};

const { isSidebarOpen } = useSidebar()

</script>

<template>
    <Head title=" | Manage Events "/>
    <div :class="[isSidebarOpen ? 'ml-72' : 'ml-0', 'transition-all duration-300 ']">
    <DemoHeader />
    <Sidebar />
    <div class=" p-8 space-y-4">
        <h1 class="text-2xl font-bold mb-4">Events</h1>
  
        <SessionMessage :status="status" />

        <div class="flex items-center justify-between">
        <Link 
            :href="route('events.create')"
            class=" px-3 py-2 text-white bg-blue-600 rounded-md font-bold text-sm "
        >
            Add Event
        </Link>
        <div class="flex items-end gap-2">

            <Link
                class="px-2 py-[6px] rounded-md bg-blue-500 text-white flex items-center gap-2"
                v-if="params.search"
                :href="
                    route('admin.events', {
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
                v-if="params.user_id"
                :href="route('admin.events', { ...params, user_id: null, page: null })"
            >
                {{ userName }}
                <i class="fa-solid fa-xmark"></i>
            </Link>

            <!-- Search form -->
            <form @submit.prevent="search">
                <InputField
                    label=""
                    icon="magnifying-glass"
                    placeholder="Search..."
                    v-model="form.search"
                />
            </form>
            
        </div>
        </div>

        <div v-if="events.data.length">
            <div class="mb-6">


                <!-- Table -->
                <table
                    class="w-full table-fixed border-collapse overflow-hidden rounded-md text-sm ring-1 ring-slate-300 dark:ring-slate-600 bg-white shadow-lg"
                >
                    <thead
                        class="bg-slate-300 text-xs uppercase text-slate-600 dark:text-slate-400 dark:bg-slate-900"
                    >
                        <tr>
                            <th class="w-3/4 p-3 text-left">event Title</th>

                            <th class="w-1/4 py-3 pl-3 text-left">Creator</th>
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

                            <td
                                class="w-1/4 py-3 pl-3 text-left text-blue-500"
                            >
                                <button @click="selectUser(event.user.id)" class="text-blue-500 font-medium text-sm">
                                    {{ event.user.name ?? 'Unknown' }}
                                </button>
                            </td>
                            <td
                                class="w-1/5 py-3 pr-3 text-right text-blue-500"
                            >
                                <Link
                                    :href="route('events.show', event.id)"
                                    >View</Link
                                >
                            </td>

                            <td
                                class="w-1/5 py-3 pr-3 text-right text-blue-500"
                            >
                                <Link :href="route('events.edit', event.id)"
                                    >Edit</Link
                                >
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
            <div>
                <PaginationLinks :paginator="events" />
            </div>
        </div>

        <div v-else>You have no events!</div>


        </div>
        </div>
</template>
