<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import JetButton from "@/Jetstream/Button";

defineProps({
    totalConvertedAmount: String,
    thirdHighestTransactionAmount: String,
    mostConversionByUser: Array,
});

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Welcome, User
                    </h2>
                </div>
                <div class="mt-6 flex space-x-3 md:mt-0 md:ml-4">
                    <Link :href="route('money-transfer.show')">
                        <JetButton>Send money</JetButton>
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Overview
                </h3>
                <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3">
                    <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            Total amount converted (for you)
                        </dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900">
                            {{ totalConvertedAmount }}
                        </dd>
                    </div>

                    <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            3rd highest amount of transactions
                        </dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900">
                            {{ thirdHighestTransactionAmount }}
                        </dd>
                    </div>

                    <div v-if="mostConversionByUser" class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            Most conversions by
                        </dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <img class="h-8 w-8 rounded-full" :src="mostConversionByUser.profile_photo_url" alt="profile-photo">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ mostConversionByUser.name }}
                                    </p>
                                    <p class="text-sm text-gray-500 truncate">
                                        {{ mostConversionByUser.email }}
                                    </p>
                                </div>
                            </div>
                        </dd>
                    </div>

                </dl>
            </div>
        </div>
    </AppLayout>
</template>
