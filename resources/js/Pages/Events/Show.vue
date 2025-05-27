<script setup>
import { router, useForm, usePage } from "@inertiajs/vue3";
import Container from "../../Components/Container.vue";
import ErrorMessage from "../../Components/ErrorMessage.vue";
import SessionMessage from "../../Components/SessionMessage.vue";
import PaginationLinks from "../../Components/PaginationLinks.vue";

const authUser = usePage().props.auth.user;
const props = defineProps({
    event: Object,
    canModify: Boolean,
    enrolledDonors: Object,
    error: String,
    status: String,
});

const form = useForm({
    user_id: authUser ? authUser.id : null,
});

const enroll = (id) => {
    form.post(route("enroll.store", id));
};

const deleteEvent = () => {
    if (confirm("Are you sure?")) {
        router.delete(route("events.destroy", props.event.id));
    }
};

const formatDate = (date) => new Date(date).toLocaleDateString();

</script>

<template>
    <Head title="| Event Detail" />

    <Container >
        <errorMessage :error="error" v-if="form.recentlySuccessful"/>
        <SessionMessage :status="status" v-if="form.recentlySuccessful"/>
        <div class="flex gap-4">
        <div class="w-1/4 rounded-md overflow-hidden">
            <img
                :src="
                    event.image
                        ? `/storage/${event.image}`
                        : '/storage/images/events/default.png'
                "
                class="w-full h-full object-cover object-center"
                alt=""
            />
        </div>

        <div class="w-3/4">
            <!-- event info -->
            <div class="mb-6">
                <div class="flex items-end justify-between mb-2">
                    <p class="text-slate-400 w-full border-b">Event detail</p>

                    <!-- Edit and delete buttons -->
                    <div v-if="canModify" class="pl-4 flex items-center gap-4">
                        <Link
                            :href="route('events.edit', event.id)"
                            class="bg-green-500 rounded-md text-white px-6 py-2 hover:outline outline-green-500 outline-offset-2"
                        >
                            Edit
                        </Link>

                        <button
                            @click="deleteEvent"
                            class="bg-red-500 rounded-md text-white px-6 py-2 hover:outline outline-red-500 outline-offset-2"
                            type="button"
                        >
                            Delete
                        </button>
                    </div>
                </div>

                <h3 class="font-bold text-2xl mb-4">{{ event.title }}</h3>
                <p>{{ event.description }}</p>

            </div>

            <!-- Contact info -->
            <div class="mb-6">
                <p class="text-slate-400 w-full border-b mb-2">Contact info</p>

                <!-- Event Date -->
                <div class="flex items-center mb-2 gap-2">
                    <i class="fa-solid fa-calendar"></i>
                    <p>Event Date: {{ formatDate(event.event_date) }}</p>
                </div>
                <!-- Location -->
                <div class="flex items-center mb-2 gap-2">
                    <i class="fa-solid fa-location-dot"></i>
                    <p>Location: {{ event.ward.name }}, {{ event.ward.district.name }}, {{ event.ward.district.region.name }}</p>
                </div>

                <!-- Email -->
                <div v-if="event.email" class="flex items-center mb-2 gap-2">
                    <i class="fa-solid fa-at"></i>
                    <p>Email:</p>
                    <a :href="`mailto:${event.email}`" class="text-link">
                        {{ event.email }}
                    </a>
                </div>
                <!-- User -->
                <div class="flex items-center mb-2 gap-2">
                    <i class="fa-solid fa-user"></i>
                    <p>Created by:</p>
                    <Link
                        :href="route('events', { user_id: event.user.id })"
                        class="text-blue-500"
                    >
                        {{ event.user.name }}
                    </Link>
                </div>
            </div>

            <div class="text-end -mt-10 ">
                <button 
                        
                        @click="enroll(event.id)"
                        class="bg-blue-600 rounded-md text-white px-6 py-2 hover:outline outline-blue-600 outline-offset-2"
                        type="button"
                    >
                        Enroll to the Event
                    </button>
            </div>

        </div>
    </div>
    </Container>

    <Container class="my-8" v-if="canModify">
        <div v-if="props.enrolledDonors.data.length">
                <div class="mb-6">


                    <!-- Table -->
                    <table
                        class="w-full table-fixed border-collapse overflow-hidden rounded-md text-sm ring-1 ring-slate-300 dark:ring-slate-600 bg-white shadow-lg"
                    >
                        <thead
                            class="bg-slate-300 text-xs uppercase text-slate-600 dark:text-slate-400 dark:bg-slate-900"
                        >
                            <tr>
                                <th class="p-3 text-left">name</th>

                                <th class=" p-3 text-left">email</th>
                                <th class="p-3 text-left">Blood Type</th>
                                <th class="p-3 text-left">Location</th>
                                <th class="p-3 text-left">status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="donor in props.enrolledDonors.data"
                                :key="donor.id"
                                class="border-b border-slate-200 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-600 dark:border-slate-600"
                            >
                                <td class=" p-3 text-left">
                                    
                                        <div class="flex items-center gap-2">
                                            <img
                                                :src="
                                                    donor.user.avatar
                                                        ? `/storage/${donor.user.avatar}`
                                                        : '/storage/avatars/default.jpg'
                                                "
                                                alt=""
                                                class="h-10 w-10 rounded-full object-cover object-center"
                                            />
                                            <h4 class="font-bold">
                                                {{ donor.user.name }}
                                                
                                            </h4>
                                        </div>
                                
                                </td>

                                <td
                                    class="p-3 text-left "
                                >
                                    <a :href="`mailto:${donor.user.email}`" class="text-blue-500">
                                        {{ donor.user.email }}
                                    </a>
                                </td>
                                <td
                                    class="py-3 pl-6 text-left "
                                >
                                    {{ donor.user.blood_type }}
                                </td>

                                <td
                                    class="p-3 text-left"
                                >
                                    <p>{{ donor.user.ward?.name ?? 'unknown' }}, {{ donor.user.ward?.district?.name ?? 'unknown'}}, {{ donor.user.ward?.district?.region?.name ?? 'unknown' }}</p>
                                </td>

                                <td class="p-3 text-left ">
                                    {{ donor.status }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div>
                    <PaginationLinks :paginator="enrolledDonors" />
                </div>
            </div>

        <div v-else>No Donor Enrolled!!</div>
    </Container>
</template>


