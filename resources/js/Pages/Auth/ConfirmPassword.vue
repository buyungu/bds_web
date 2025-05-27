<script setup>
import { useForm } from '@inertiajs/vue3';
import Container from '../../Components/Container.vue';
import PrimaryBtn from '../../Components/PrimaryBtn.vue';



const form = useForm({
    password: "",
});

// Method to handle form submission
const submit = () => {
    form.post(route("password.confirm"), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Head title="| Password Confirmation" />
    <Container class="w-1/2">
        <div class="text-center mb-8">
            <p>
                This is a secure area of the application. Please confirm your password before continuing.
            </p>
        </div>
        <form @submit.prevent="submit" class="space-y-6">

            <!-- Password Input -->
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Password
                </label>
                <div class="relative mt-1 rounded-md">
                    <div class="pointer-event-non absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="grid place-content-center text-sm text-slate-400">
                            <i class="fa-solid fa-key"></i>
                        </span>
                    </div>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        v-model="form.password"
                        :class="{
                            'border-red-500 ring-1 ring-red-500': form.errors.password
                        }"
                        class="block w-full pl-9 rounded-md text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                    />
                </div>
                <!-- Displaying error for password -->
                <p v-if="form.errors.password" class="text-red-500 text-sm font-medium mt-2">{{ form.errors.password }}</p>
            </div>


            <PrimaryBtn :disabled="form.processing">Confirm</PrimaryBtn>
        </form>
    </Container>
</template>
