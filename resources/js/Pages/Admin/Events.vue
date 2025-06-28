<script setup>
import { useSidebar } from '../../composables/useSidebar';
import { router, useForm } from '@inertiajs/vue3';
import DemoHeader from '../../Layouts/DemoHeader.vue';
import Sidebar from '../../Layouts/Sidebar.vue';
import SessionMessage from '../../Components/SessionMessage.vue';
import InputField from '../../Components/InputField.vue'; // Assuming this is a custom component for styled input
import PaginationLinks from '../../Components/PaginationLinks.vue'; // Assuming this is your pagination component

const props = defineProps({
    events: Object, // Inertia Paginator object
    status: String, // Session message status
});

const params = route().params;
const form = useForm({ search: params.search || '' }); // Initialize search with empty string if not present

// Safely get the user name: check if events.data exists and has items before finding
const userName = params.user_id && props.events.data.length
    ? props.events.data.find(i => i.created_by === Number(params.user_id))?.user?.name || 'Unknown User'
    : null;

const search = () => {
    router.get(route("admin.events"), {
        search: form.search,
        user_id: params.user_id
    }, { preserveState: true, preserveScroll: true }); // Preserve state/scroll for better UX
}

const selectUser = (id) => {
    router.get(route("admin.events"), {
        user_id: id,
        search: form.search
    }, { preserveState: true, preserveScroll: true }); // Preserve state/scroll for better UX
};

const deleteEvent = (id) => {
    if (confirm("Are you sure you want to delete this event? This action cannot be undone.")) {
        router.delete(route('events.destroy', id), {
            preserveScroll: true,
            onSuccess: () => {
                // Optionally, add a success message via flash
                // Assuming your backend handles the flash message on delete
            },
            onError: (errors) => {
                console.error('Error deleting event:', errors);
                // Handle errors, e.g., display a generic error message
            }
        });
    }
};

const { isSidebarOpen } = useSidebar()
</script>

<template>
    <Head title=" | Manage Events "/>
    <div :class="[isSidebarOpen ? 'ml-72' : 'ml-0', 'transition-all duration-300 min-h-screen flex flex-col']">
        <DemoHeader />
        <Sidebar />

        <div class="p-4 sm:p-8 flex-grow">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 gap-4">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Events Management</h1>
                <Link
                    :href="route('events.create')"
                    class="px-4 py-2 text-white bg-blue-600 rounded-md font-bold text-sm text-center w-full sm:w-auto hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Add New Event
                </Link>
            </div>

            <SessionMessage :status="status" class="mb-4" />

            <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4 mb-6">
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-wrap">
                    <Link
                        class="px-3 py-1.5 rounded-md bg-blue-500 text-white flex items-center gap-2 text-sm justify-center flex-grow sm:flex-grow-0"
                        v-if="params.search"
                        :href="route('admin.events', { ...params, search: null, page: null })"
                    >
                        Search: "{{ params.search }}"
                        <i class="fa-solid fa-xmark"></i>
                    </Link>
                    <Link
                        class="px-3 py-1.5 rounded-md bg-blue-500 text-white flex items-center gap-2 text-sm justify-center flex-grow sm:flex-grow-0"
                        v-if="params.user_id"
                        :href="route('admin.events', { ...params, user_id: null, page: null })"
                    >
                        Creator: {{ userName }}
                        <i class="fa-solid fa-xmark"></i>
                    </Link>
                </div>

                <form @submit.prevent="search" class="w-full md:w-1/3">
                    <InputField
                        label=""
                        icon="magnifying-glass"
                        placeholder="Search events..."
                        v-model="form.search"
                        class="w-full"
                    />
                </form>
            </div>

            <div v-if="events.data.length" class="overflow-x-auto shadow-lg rounded-md ring-1 ring-slate-300 dark:ring-slate-600 bg-white dark:bg-slate-800">
                <table
                    class="w-full min-w-[700px] table-auto border-collapse text-sm"
                >
                    <thead
                        class="bg-slate-300 text-xs uppercase text-slate-600 dark:text-slate-400 dark:bg-slate-900"
                    >
                        <tr>
                            <th class="p-3 text-left">Event Title</th>
                            <th class="py-3 pl-3 text-left">Creator</th>
                            <th class="py-3 pr-3 text-right">Actions</th> </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="event in events.data"
                            :key="event.id"
                            class="border-b border-slate-200 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 dark:border-slate-600"
                        >
                            <td class="p-3 text-left">
                                <div class="flex items-center gap-2">
                                    <img
                                        :src="
                                            event.image
                                                ? `/storage/${event.image}`
                                                : '/storage/images/events/default.png'
                                        "
                                        alt="Event Image"
                                        class="h-10 w-10 rounded-full object-cover object-center"
                                    />
                                    <h4 class="font-bold text-gray-800 dark:text-white line-clamp-1">
                                        {{ event.title }}
                                    </h4>
                                </div>
                            </td>

                            <td
                                class="py-3 pl-3 text-left text-blue-500"
                            >
                                <button @click="selectUser(event.user.id)" class="text-blue-500 font-medium text-sm hover:underline">
                                    {{ event.user.name ?? 'Unknown' }}
                                </button>
                            </td>
                            <td class="py-3 pr-3 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end space-x-4">
                                    <Link
                                        :href="route('events.show', event.id)"
                                        class="text-blue-600 hover:underline text-sm"
                                    >
                                        View
                                    </Link>
                                    <Link
                                        :href="route('events.edit', event.id)"
                                        class="text-green-600 hover:underline text-sm"
                                    >
                                        Edit
                                    </Link>
                                    <button
                                        type="button"
                                        @click="deleteEvent(event.id)"
                                        class="text-red-600 hover:underline text-sm"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-else class="text-center py-8 text-gray-600 dark:text-gray-400">
                No events found.
            </div>

            <div v-if="events.data.length" class="mt-6">
                <PaginationLinks :paginator="events" />
            </div>
        </div>
    </div>
</template>