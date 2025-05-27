<script setup>
import { useForm } from '@inertiajs/vue3';
import Container from '../../Components/Container.vue';
import PrimaryBtn from '../../Components/PrimaryBtn.vue';
import SessionMessage from '../../Components/SessionMessage.vue'

defineProps({
    status: String,
})

const form = useForm({
    email: "",
});

// Method to handle form submission
const submit = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <Head title="| Forgot Password" />
    <Container class="w-1/2">
        <div class="text-center mb-8">
            <p>
                Forgot your password? No problem, Just let us know your email address
                and we will email you a password reset link that will allow you to choose a new one
            </p>
        </div>
        <form @submit.prevent="submit" class="space-y-6">
            <SessionMessage :status="status"/>
            <!-- Email Input -->
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


            <PrimaryBtn :disabled="form.processing">Send Password Reset Link</PrimaryBtn>
        </form>
    </Container>
</template>
