<script setup>
import { router, useForm, usePage } from "@inertiajs/vue3";
import Container from "../../Components/Container.vue"; // Assuming this is a responsive container component
import ErrorMessage from "../../Components/ErrorMessage.vue";
import SessionMessage from "../../Components/SessionMessage.vue";
import PaginationLinks from "../../Components/PaginationLinks.vue";

const authUser = usePage().props.auth.user; // Get authenticated user

const props = defineProps({
    event: Object, // The main event object
    canModify: Boolean, // Whether the current user can edit/delete the event
    enrolledDonors: Object, // Paginator object for enrolled donors
    error: String, // Error message from session flash
    status: String, // Success/status message from session flash
});

const form = useForm({
    user_id: authUser ? authUser.id : null, // Pre-fill user_id if authenticated
});

// Function to handle event enrollment
const enroll = (id) => {
    // Basic validation: ensure user is logged in before attempting to enroll
    if (!authUser) {
        alert('Please log in to enroll in an event.');
        return;
    }
    form.post(route("enroll.store", id), {
        preserveScroll: true, // Keep scroll position after enrollment attempt
        onSuccess: () => {
            // Optionally, clear form or show specific success message if needed
            // The SessionMessage component should pick up 'status' flash message
        },
        onError: (errors) => {
            console.error('Enrollment error:', errors);
            // The ErrorMessage component should pick up 'error' flash message
        }
    });
};

// Function to handle event deletion
const deleteEvent = () => {
    if (confirm("Are you sure you want to delete this event? This action cannot be undone.")) {
        router.delete(route("events.destroy", props.event.id), {
            preserveScroll: true, // Keep scroll position
            onSuccess: () => {
                // Redirect to events list or show a global success message
                router.visit(route('admin.events')); // Redirect after successful delete
            },
            onError: (errors) => {
                console.error('Deletion error:', errors);
                // Handle errors, e.g., display error message
            }
        });
    }
};

// Formats a date string to a more readable local date string
const formatDate = (date) => new Date(date).toLocaleDateString(undefined, {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
});
</script>

<template>
    <Head title="| Event Detail" />

    <Container class="py-6 sm:py-8">
        <ErrorMessage :error="error" class="mb-4" />
        <SessionMessage :status="status" class="mb-4" />

        <div class="flex flex-col md:flex-row gap-6 mb-8">
            <div class="w-full md:w-1/3 flex-shrink-0 rounded-lg overflow-hidden shadow-lg aspect-w-16 aspect-h-9 md:aspect-h-10">
                <img
                    :src="
                        event.image
                            ? `/storage/${event.image}`
                            : '/storage/images/events/default.png'
                    "
                    class="w-full h-full object-cover object-center"
                    :alt="event.title"
                />
            </div>

            <div class="w-full md:w-2/3">
                <div class="mb-6">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-2 pb-2 border-b border-slate-200 dark:border-slate-700">
                        <p class="text-slate-500 dark:text-slate-400 text-lg font-semibold mb-2 sm:mb-0">Event Details</p>

                        <div v-if="canModify" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
                            <Link
                                :href="route('events.edit', event.id)"
                                class="bg-green-500 rounded-md text-white px-6 py-2 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 text-center"
                            >
                                Edit Event
                            </Link>
                            <button
                                @click="deleteEvent"
                                class="bg-red-500 rounded-md text-white px-6 py-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 text-center"
                                type="button"
                            >
                                Delete Event
                            </button>
                        </div>
                    </div>

                    <h1 class="font-bold text-3xl mb-3 text-gray-900 dark:text-white">{{ event.title }}</h1>
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ event.description }}</p>
                </div>

                <div class="mb-6">
                    <p class="text-slate-500 dark:text-slate-400 text-lg font-semibold mb-2 pb-2 border-b border-slate-200 dark:border-slate-700">Contact Information</p>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-y-2 gap-x-4 text-gray-700 dark:text-gray-300">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-calendar text-blue-500"></i>
                            <p><span class="font-medium">Date:</span> {{ formatDate(event.event_date) }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-blue-500"></i>
                            <p><span class="font-medium">Location:</span> {{ event.location.address }}</p>
                        </div>
                        <div v-if="event.email" class="flex items-center gap-2">
                            <i class="fa-solid fa-at text-blue-500"></i>
                            <p><span class="font-medium">Email:</span>
                                <a :href="`mailto:${event.email}`" class="text-blue-600 hover:underline">
                                    {{ event.email }}
                                </a>
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-user text-blue-500"></i>
                            <p><span class="font-medium">Created by:</span>
                                <Link
                                    :href="route('events', { user_id: event.user.id })"
                                    class="text-blue-600 hover:underline"
                                >
                                    {{ event.user.name }}
                                </Link>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="text-right mt-6">
                    <button
                        @click="enroll(event.id)"
                        class="bg-blue-600 rounded-md text-white px-8 py-3 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-lg font-semibold transition-colors duration-200"
                        type="button"
                    >
                        Enroll to this Event
                    </button>
                </div>
            </div>
        </div>
    </Container>

    <Container class="my-8" v-if="canModify">
        <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Enrolled Donors</h2>

        <div v-if="props.enrolledDonors && props.enrolledDonors.data && props.enrolledDonors.data.length" class="overflow-x-auto shadow-lg rounded-md ring-1 ring-slate-300 dark:ring-slate-600 bg-white dark:bg-slate-800">
            <table
                class="w-full min-w-[768px] table-auto border-collapse text-sm"
            >
                <thead
                    class="bg-slate-300 text-xs uppercase text-slate-600 dark:text-slate-400 dark:bg-slate-900"
                >
                    <tr>
                        <th class="p-3 text-left">Name</th>
                        <th class="p-3 text-left">Email</th>
                        <th class="p-3 text-left">Blood Type</th>
                        <th class="p-3 text-left">Location</th>
                        <th class="p-3 text-left">Status</th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="donor in props.enrolledDonors.data"
                        :key="donor.id"
                        class="border-b border-slate-200 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 dark:border-slate-600"
                    >
                        <td class="p-3 text-left">
                            <div class="flex items-center gap-3">
                                <img
                                    :src="
                                        donor.user.avatar
                                            ? `/storage/${donor.user.avatar}`
                                            : '/storage/avatars/default.jpg'
                                    "
                                    alt="Donor Avatar"
                                    class="h-10 w-10 rounded-full object-cover object-center flex-shrink-0"
                                />
                                <h4 class="font-semibold text-gray-800 dark:text-white line-clamp-1">
                                    {{ donor.user.name }}
                                </h4>
                            </div>
                        </td>

                        <td class="p-3 text-left">
                            <a :href="`mailto:${donor.user.email}`" class="text-blue-600 hover:underline">
                                {{ donor.user.email }}
                            </a>
                        </td>
                        <td class="p-3 text-left">
                            <span class="font-medium text-red-600">{{ donor.user.blood_type }}</span>
                        </td>

                        <td class="p-3 text-left line-clamp-1">
                            {{ donor.user.location.address }}
                        </td>

                        <td class="p-3 text-left">
                            <span :class="{
                                'text-green-600': donor.status === 'approved',
                                'text-yellow-600': donor.status === 'pending',
                                'text-red-600': donor.status === 'rejected'
                            }" class="font-semibold capitalize">
                                {{ donor.status }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-else class="text-center py-6 text-gray-600 dark:text-gray-400">
            No donors have enrolled in this event yet.
        </div>

        <div v-if="props.enrolledDonors && props.enrolledDonors.data.length" class="mt-6">
            <PaginationLinks :paginator="enrolledDonors" />
        </div>
    </Container>
</template>