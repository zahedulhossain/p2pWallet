<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import JetSectionBorder from '@/Jetstream/SectionBorder.vue';
import SendMoneyForm from "./Partials/SendMoneyForm";

defineProps({
    balance: String,
    currencySymbol: String,
});

</script>

<template>
    <AppLayout title="Money Transfer">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Send money
            </h2>
        </template>

        <div class="py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <SendMoneyForm class="mt-10 sm:mt-0"
                               :balance="balance"
                               :currency-symbol="currencySymbol"
                />

                <JetSectionBorder/>
            </div>
        </div>
    </AppLayout>
</template>
