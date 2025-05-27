<script setup>
import { useSidebar } from '../../../composables/useSidebar';
import { useForm } from '@inertiajs/vue3'
import Container from '../../../Components/Container.vue'
import Title from '../../../Components/Title.vue'
import DemoHeader from '../../../Layouts/DemoHeader.vue'
import Sidebar from '../../../Layouts/Sidebar.vue'
import PrimaryBtn from '../../../Components/PrimaryBtn.vue'
import SessionMessage from '../../../Components/SessionMessage.vue'

const props = defineProps({
    inventory: Object,
    status: String,
})

const form = useForm({
    blood_type: props.inventory.blood_type,
    quantity: props.inventory.quantity,
    source: props.inventory.source,
    _method: 'PUT',
})

const submit = (id) => {
    form.post(route('inventory.update', id))
}

const { isSidebarOpen } = useSidebar()

</script>

<template>
    <Head title=" | Edit Inventory" />
        <div :class="[isSidebarOpen ? 'ml-72' : 'ml-0', 'transition-all duration-300 ']">

    <DemoHeader />
    <Sidebar />

    <div class="p-8">
        <Container>
            <div class="mb-8">
                <Title>Edit Blood Inventory</Title>
            </div>

            <form @submit.prevent="submit(props.inventory.id)" class="space-y-6">
                <SessionMessage :status="status" v-if="form.recentlySuccessful" class="w-1/2" />

                <!-- Blood Type -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Blood Type</label>
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fa-solid fa-droplet text-slate-400"></i>
                        </div>
                        <select
                            v-model="form.blood_type"
                            :class="{ 'border-red-500 ring-1 ring-red-500': form.errors.blood_type }"
                            class="block w-1/2 rounded-md pl-9 pr-3 text-sm dark:text-slate-900 border-slate-300 focus:ring-blue-400 focus:border-blue-400">
                            <option disabled value="">Select Blood Type</option>
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
                    <p v-if="form.errors.blood_type" class="text-red-500 text-sm mt-2">{{ form.errors.blood_type }}</p>
                </div>

                <!-- Quantity -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Quantity</label>
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fa-solid fa-key text-slate-400"></i>
                        </div>
                        <input
                            type="number"
                            v-model="form.quantity"
                            :class="{ 'border-red-500 ring-1 ring-red-500': form.errors.quantity }"
                            class="block w-1/2 pl-9 rounded-md text-sm dark:text-slate-900 border-slate-300 focus:ring-blue-400 focus:border-blue-400"
                        />
                    </div>
                    <p v-if="form.errors.quantity" class="text-red-500 text-sm mt-2">{{ form.errors.quantity }}</p>
                </div>

                <!-- Source -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Source</label>
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fa-solid fa-droplet text-slate-400"></i>
                        </div>
                        <select
                            v-model="form.source"
                            :class="{ 'border-red-500 ring-1 ring-red-500': form.errors.source }"
                            class="block w-1/2 rounded-md pl-9 pr-3 text-sm dark:text-slate-900 border-slate-300 focus:ring-blue-400 focus:border-blue-400">
                            <option value="donation">Donations</option>
                            <option value="purchase">Purchases</option>
                        </select>
                    </div>
                    <p v-if="form.errors.source" class="text-red-500 text-sm mt-2">{{ form.errors.source }}</p>
                </div>

                <PrimaryBtn :disabled="form.processing">Update Inventory</PrimaryBtn>
            </form>
        </Container>
    </div>
    </div>
</template>
