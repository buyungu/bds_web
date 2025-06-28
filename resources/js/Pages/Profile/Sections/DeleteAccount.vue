<script setup>
import { useForm } from '@inertiajs/vue3';
import Container from '../../../Components/Container.vue';
import PrimaryBtn from '../../../Components/PrimaryBtn.vue';
import Title from '../../../Components/Title.vue';
import SessionMessage from '../../../Components/SessionMessage.vue';
import { ref } from 'vue';

const showPassword = ref(false);


const form = useForm({
    password: "",
});

const submit = () => {
    form.delete(route("profile.destroy"), {
        preserveScroll: true,
    });
}
</script>

<template>
    <Container class="mb-8">
        <div class="mb-6">
            <Title>Delete Account</Title>
            <p>
                Once your account is deleted, all of its resources and data will be permanently. This action can not be  undone.
            </p>
        </div>

        <div v-if="showPassword">
            <form @submit.prevent="submit" class="space-y-6">
                <!-- Confirm Password -->
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
                            class="block w-full sm:w-1/2 pl-9 rounded-md text-sm dark:text-slate-900 border-slate-300 outline-0 focus:ring-1 focus:ring-inset focus:ring-blue-400 focus:border-blue-400 placeholder:text-slate-400"
                        />
                    </div>
                    <!-- Displaying error for password -->
                    <p v-if="form.errors.password" class="text-red-500 text-sm font-medium mt-2">{{ form.errors.password }}</p>
                </div>


                <div class="flex items-center space-x-6">
                    <PrimaryBtn :disabled="form.processing">Confirm Password</PrimaryBtn>
                    <button
                        @click="showPassword = false "
                        class="text-blue-500 font-medium underline dark:text-blue-400">
                        Cancel
                    </button>
                </div>

            </form>
        </div>

        <button
            v-if="!showPassword"
            @click="showPassword = true"
            class="px-6 py-2 rounded-lg bg-red-500 text-white">
            <i class="fa-solid fa-triangle-exclamation mr-2"></i>
            Delete Account
        </button>
    </Container>
</template>
