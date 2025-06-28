<script setup>
import { useForm } from '@inertiajs/vue3';
import Container from '../../Components/Container.vue';
import PrimaryBtn from '../../Components/PrimaryBtn.vue';
import SessionMessage from '../../Components/SessionMessage.vue';

    const form = useForm({})

    defineProps({
        status: String,
    })

    const submit = () => {
        form.post(route('verification.send'))
    }
</script>

<template>
    <Head title=" | Email Verification" />

    <Container class="w-full max-w-md mx-auto p-6 bg-white dark:bg-slate-800 rounded-lg shadow-md">
        <div class="mb-8">
            <p>
                Thanks for signing up! Before getting started,
                could you verify your email address by clicking on
                the link we just emaild to you?
                If you didn't receive the email, we will gladly send
                you another
            </p>
        </div>

        <SessionMessage :status="status"/>
        <form @submit.prevent="submit">
            <PrimaryBtn :disabled="form.processing">Resend Verification Email</PrimaryBtn>
        </form>

    </Container>
</template>
