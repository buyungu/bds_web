<script setup>
import { useSidebar } from '../../composables/useSidebar';
import { useForm } from '@inertiajs/vue3';
import Container from '../../Components/Container.vue';
import Title from '../../Components/Title.vue';
import PrimaryBtn from '../../Components/PrimaryBtn.vue';
import ImageUpload from '../../Components/ImageUpload.vue';
import SessiomMessage from '../../Components/SessionMessage.vue';
import DemoHeader from '../../Layouts/DemoHeader.vue';
import Sidebar from '../../Layouts/Sidebar.vue';
import { computed } from 'vue';

const props = defineProps({ 
    status: String,
    event: Object,
    regions: Array,
    districts: Array,
    wards: Array,
});

const form = useForm({
    title: props.event.title,
    description: props.event.description,
    event_date: props.event.event_date,
    email: props.event.email,
    image: null,
    region_id: props.event.region_id,
    district_id: props.event.district_id,
    ward_id: props.event.ward_id,
    _method: 'PUT',
});

// Filter districts based on the selected region
const filteredDistricts = computed(() => {
    return props.districts.filter(district => district.region_id === form.region_id);
});

// Filter wards based on the selected district
const filteredWards = computed(() => {
    return props.wards.filter(ward => ward.district_id === form.district_id)
});

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
            <SessiomMessage :status="status" v-if="form.recentlySuccessful"/>

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
                    <!-- Region Select -->
                    <div>
                        <label for="region_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                            Region
                        </label>
                        <div class="relative mt-1 rounded-lg">
                    <div class="pointer-event-non absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="grid place-content-center text-sm text-slate-400">
                            <i class="fa-solid fa-city"></i>
                        </span>
                    </div>
                        <select
                            id="region_id"
                            name="region_id"
                            v-model="form.region_id"
                            :class="{
                                'border-red-500 ring-1 ring-red-500': form.errors.region_id
                            }"
                        class="block w-full rounded-md pr-3 pl-9 text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"                        >
                            <option value="" disabled selected>Select a Region</option>
                            <option v-for="region in props.regions" :key="region.id" :value="region.id">
                                {{ region.name }}
                            </option>
                        </select>
                        </div>
                        <p v-if="form.errors.region_id" class="text-red-500 text-xs mt-2">{{ form.errors.region_id }}</p>
                    </div>

                    <!-- District Select -->
                    <div>
                        <label for="district_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                            District
                        </label>
                        <div class="relative mt-1 rounded-lg">
                    <div class="pointer-event-non absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="grid place-content-center text-sm text-slate-400">
                            <i class="fa-solid fa-tree-city"></i>
                        </span>
                    </div>
                        <select
                            id="district_id"
                            name="district_id"
                            v-model="form.district_id"
                            :class="{
                                'border-red-500 ring-1 ring-red-500': form.errors.district_id
                            }"
                        class="block w-full rounded-md pr-3 pl-9 text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                        :disabled="!form.region_id"
                        >
                            <option value="" disabled selected>Select a District</option>
                            <option v-for="district in filteredDistricts" :key="district.id" :value="district.id">
                                {{ district.name }}
                            </option>
                        </select>
                        </div>
                        <p v-if="form.errors.district_id" class="text-red-500 text-xs mt-2">{{ form.errors.district_id }}</p>
                    </div>

                    <!-- Ward Select -->
                    <div>
                        <label for="ward_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                            Ward
                        </label>
                        <div class="relative mt-1 rounded-lg">
                    <div class="pointer-event-non absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="grid place-content-center text-sm text-slate-400">
                            <i class="fa-solid fa-arrow-right-to-city"></i>
                        </span>
                    </div>
                        <select
                            id="ward_id"
                            name="ward_id"
                            v-model="form.ward_id"
                            :class="{
                                'border-red-500 ring-1 ring-red-500': form.errors.ward_id
                            }"
                        class="block w-full rounded-md pr-3 pl-9 text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                        :disabled="!form.district_id"
                        >
                            <option value="" disabled selected>Select a Ward</option>
                            <option v-for="ward in filteredWards" :key="ward.id" :value="ward.id">
                                {{ ward.name }}
                            </option>
                        </select>
                        </div>
                        <p v-if="form.errors.ward_id" class="text-red-500 text-xs mt-2">{{ form.errors.ward_id }}</p>
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
