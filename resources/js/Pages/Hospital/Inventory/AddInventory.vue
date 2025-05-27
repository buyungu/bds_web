<script setup>
import { useSidebar } from '../../../composables/useSidebar';
import { useForm } from '@inertiajs/vue3';
import Container from '../../../Components/Container.vue';
import Title from '../../../Components/Title.vue';
import DemoHeader from '../../../Layouts/DemoHeader.vue';
import Sidebar from '../../../Layouts/Sidebar.vue';
import PrimaryBtn from '../../../Components/PrimaryBtn.vue';
import SessionMessage from '../../../Components/SessionMessage.vue';

const props = defineProps({
    status: String,
})

const form = useForm({
    blood_type: '',
    quantity: '',
    source: '',
});

const submit = () => {
    form.post(route("inventory.store"), {
        onFinish: () => form.reset(),
    });
};

const { isSidebarOpen } = useSidebar()

</script>

<template>
    <Head title=" | Add Inventory" />
        <div :class="[isSidebarOpen ? 'ml-72' : 'ml-0', 'transition-all duration-300 ']">

    <DemoHeader />
    <Sidebar />
    <div class=" p-8">
        <Container class="">
        <div class=" mb-8">
            <Title>Add blood Inventory </Title>
        </div>
        <form @submit.prevent="submit" class="space-y-6">
            
            <SessionMessage :status="status" v-if="form.recentlySuccessful" class="w-1/2" />

            <!-- Blood Type Select -->
            <div class="mb-4">
                <label for="blood_type" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Blood Type
                </label>
                <div class="relative mt-1 rounded-lg">
                    <div class="pointer-event-non absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="grid place-content-center text-sm text-slate-400">
                            <i class="fa-solid fa-droplet"></i>
                        </span>
                    </div>
                    <select
                        id="blood_type"
                        name="blood_type"
                        v-model="form.blood_type"
                        :class="{
                            'border-red-500 ring-1 ring-red-500': form.errors.blood_type
                        }"
                        class="block w-1/2 rounded-md pr-3 pl-9 text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                    >
                        <option></option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                    </select>
                </div>
                <p v-if="form.errors.blood_type" class="text-red-500 text-sm font-medium mt-2">{{ form.errors.blood_type }}</p>

            </div>


            <!-- Quantity Input -->
            <div>
                <label for="quantity" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Quantity
                </label>
                <div class="relative mt-1 rounded-md">
                    <div class="pointer-event-non absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="grid place-content-center text-sm text-slate-400">
                            <i class="fa-solid fa-key"></i>
                        </span>
                    </div>
                    <input
                        type="number"
                        id="quantity"
                        name="quantity"
                        v-model="form.quantity"
                        :class="{
                            'border-red-500 ring-1 ring-red-500': form.errors.quantity
                        }"
                        class="block w-1/2 pl-9 rounded-md text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                    />
                </div>
                <p v-if="form.errors.quantity" class="text-red-500 text-sm font-medium mt-2">{{ form.errors.quantity }}</p>

            </div>

            <!-- Urgency Select -->
            <div class="mb-4">
                <label for="blood_type" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Source
                </label>
                <div class="relative mt-1 rounded-lg">
                    <div class="pointer-event-non absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="grid place-content-center text-sm text-slate-400">
                            <i class="fa-solid fa-droplet"></i>
                        </span>
                    </div>
                    <select
                        id="source"
                        name="source"
                        v-model="form.source"
                        :class="{
                            'border-red-500 ring-1 ring-red-500': form.errors.source
                        }"
                        class="block w-1/2 rounded-md pr-3 pl-9 text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                    >
                        <option value="donation">Donations</option>
                        <option value="purchase">Purchases</option>
                    </select>
                </div>
                <p v-if="form.errors.source" class="text-red-500 text-sm font-medium mt-2">{{ form.errors.source }}</p>

            </div>


            <PrimaryBtn :disabled="form.processing">Add Inventory</PrimaryBtn>
        </form>
    </Container>
    </div>
    </div>
</template>
