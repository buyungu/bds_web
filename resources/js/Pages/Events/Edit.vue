<script setup>
import { useSidebar } from '../../composables/useSidebar';
import { useForm } from '@inertiajs/vue3';
import Container from '../../Components/Container.vue';
import Title from '../../Components/Title.vue';
import PrimaryBtn from '../../Components/PrimaryBtn.vue';
import ImageUpload from '../../Components/ImageUpload.vue';
import SessionMessage from '../../Components/SessionMessage.vue';
import DemoHeader from '../../Layouts/DemoHeader.vue';
import Sidebar from '../../Layouts/Sidebar.vue';
import { computed } from 'vue';

const props = defineProps({ 
    status: String,
    event: Object,
    
});

const form = useForm({
    title: props.event.title,
    description: props.event.description,
    event_date: props.event.event_date,
    email: props.event.email,
    image: null,
    location: props.event.location,
    _method: 'PUT',
});

const setLocation = (e) => {
        console.log('setLocation', e)
        form.location = {
            lat: e.geometry.location.lat(),
            lng: e.geometry.location.lng(),
            address: e.formatted_address,
            name: e.name,
            url: e.url,
            district: e.address_components.find(c => c.types.includes('administrative_area_level_2'))?.long_name || '',
            region: e.address_components.find(c => c.types.includes('administrative_area_level_1'))?.long_name || '',
            country: e.address_components.find(c => c.types.includes('country'))?.long_name || '',
        };
    }


// Method to handle form submission
const submit = (id) => {
    form.post(route("events.update", id), {
        onFinish: () => form.reset(),
        onError: () => form.reset(),
    });
};

const { isSidebarOpen } = useSidebar()

</script>

<template>
    <Head title="| Edit Event" />
        <div :class="[isSidebarOpen ? 'ml-72' : 'ml-0', 'transition-all duration-300 ']">

    <DemoHeader />
    <Sidebar />
    <div class="p-8">
       
        <Container>
        <div class="text-center mb-8">
            <Title>Edit Your Event</Title>
        </div>
        <form @submit.prevent="submit(event.id)" >
            <SessionMessage :status="status" v-if="form.recentlySuccessful" class="w-full"/>

            <div class="grid grid-cols-2 gap-6">
                <!-- Title Input -->
                <div class="space-y-5">
                <div>
                    <label for="title" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        Title
                    </label>
                    <div class="relative mt-1 rounded-md">
                        <div class="pointer-event-non absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="grid place-content-center text-sm text-slate-400">
                                <i class="fa-solid fa-heading"></i>
                            </span>
                        </div>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            placeholder="New Event"
                            v-model="form.title"
                            :class="{
                                'border-red-500 ring-1 ring-red-500': form.errors.title
                            }"
                            class="block w-full pl-9 rounded-md text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                        />
                    </div>
                    <!-- Displaying error for title -->
                    <p v-if="form.errors.title" class="text-red-500 text-sm font-medium mt-2">{{ form.errors.title }}</p>
                </div>
                <div>
                    <label for="event_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        Event Date
                    </label>
                    <div class="relative mt-1 rounded-md">
                        <div class="pointer-event-non absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="grid place-content-center text-sm text-slate-400">
                                <i class="fa-solid fa-calendar"></i>
                            </span>
                        </div>
                        <input
                            type="date"
                            id="event_date"
                            name="event_date"
                            v-model="form.event_date"
                            :class="{
                                'border-red-500 ring-1 ring-red-500': form.errors.event_date
                            }"
                            class="block w-full pl-9 rounded-md text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                        />
                    </div>
                    <!-- Displaying error for event_date -->
                    <p v-if="form.errors.event_date" class="text-red-500 text-sm font-medium mt-2">{{ form.errors.event_date }}</p>
                </div>

                
                <!-- Password Input -->
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        Description
                    </label>
                    <div class="relative mt-1 rounded-md">
                        <div class="pointer-event-non absolute top-3 left-0 flex items-center pl-3">
                            <span class="grid place-content-center text-sm text-slate-400">
                                <i class="fa-solid fa-newspaper"></i>
                            </span>
                        </div>
                        <textarea
                            rows="6"
                            id="description"
                            name="description"
                            v-model="form.description"
                            :class="{
                                'border-red-500 ring-1 ring-red-500': form.errors.description
                            }"
                            class="block w-full pl-9 rounded-md text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                        ></textarea>
                    </div>
                    <!-- Displaying error for description -->
                    <p v-if="form.errors.description" class="text-red-500 text-sm font-medium mt-2">{{ form.errors.description }}</p>
                </div>

                
                </div>
                <div class="space-y-5">
                    <!-- Location Input -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                Location
                            </label>
                            <div class="relative mt-1 rounded-md">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="grid place-content-center text-sm text-slate-400">
                                        <i class="fa-solid fa-map-marker-alt"></i>
                                    </span>
                                </div>
                                <GMapAutocomplete
                                    @place_changed="setPlace"
                                    id="location"
                                    name="location"
                                    v-model="form.location"
                                    :class="{
                                        'border-red-500 ring-1 ring-red-500': form.errors.location
                                    }"
                                    class="block w-full pl-9 rounded-md text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                                >
                                </GMapAutocomplete> 
                            </div>
                            <p v-if="form.errors.location" class="text-red-500 text-xs mt-2">{{ form.errors.location }}</p>
                        </div>

                        <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                            Email
                        </label>
                        <div class="relative mt-1 rounded-md">
                            <div class="pointer-event-non absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="grid place-content-center text-sm text-slate-400">
                                    <i class="fa-solid fa-at"></i>
                                </span>
                            </div>
                            <input
                                type="text"
                                id="email"
                                name="email"
                                placeholder="New Event"
                                v-model="form.email"
                                :class="{
                                    'border-red-500 ring-1 ring-red-500': form.errors.email
                                }"
                                class="block w-full pl-9 rounded-md text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                            />
                        </div>
                        <!-- Displaying error for email -->
                        <p v-if="form.errors.email" class="text-red-500 text-sm font-medium mt-2">{{ form.errors.email }}</p>
                    </div>

                    

                    <ImageUpload 
                        @image=" (e) => (form.image = e)"
                        :eventImage="event.image"    
                    />
                </div>

                <div>
                    <PrimaryBtn :disabled="form.processing">Update</PrimaryBtn>
                </div>
            </div>
            
            
        </form>
    </Container>
</div>
</div>
</template>
