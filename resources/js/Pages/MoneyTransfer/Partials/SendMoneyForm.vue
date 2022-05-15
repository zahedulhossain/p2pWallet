<script setup>
import {ref} from 'vue';
import {useForm} from '@inertiajs/inertia-vue3';
import JetActionMessage from '@/Jetstream/ActionMessage.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetFormSection from '@/Jetstream/FormSection.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import JetLabel from '@/Jetstream/Label.vue';
import SearchUserModal from './SearchUserModal';

const props = defineProps({
    balance: String,
    currencySymbol: String,
});

const amountInput = ref(0);
const noteInput = ref('');
const user = ref(null);

const form = useForm({
    amount: '',
    note: '',
    user_id: null,
});

const clearUser = () => {
    user.value = null;
    form.user_id = null;
}

const setUser = (selectedUser) => {
    user.value = selectedUser;
    form.user_id = selectedUser.id;
}

const sendMoney = () => {
    form.post(route('money-transfer.store'), {
        errorBag: 'transferMoney',
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.amount) {
                form.reset('amount');
                amountInput.value.focus();
            }
            if (form.errors.user_id) {
                form.reset('user_id');
                amountInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <JetFormSection @submitted="sendMoney">
        <template #title>
            Sending money is easy
        </template>

        <template #description>
            Search the user you want to send money to, enter the amount and click send.
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <JetLabel value="To"/>

                <div class="flex items-center space-x-4" v-if="user">
                    <div class="flex-shrink-0">
                        <img class="h-8 w-8 rounded-full" :src="user.profile_photo_url" alt="profile-photo">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            {{ user.name }}
                        </p>
                        <p class="text-sm text-gray-500 truncate">
                            {{ user.email }}
                        </p>
                    </div>
                    <span @click="clearUser"
                          class="cursor-pointer inline-flex justify-center px-3.5 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="sr-only">Remove</span>
                    </span>
                </div>

                <SearchUserModal @select-user="(item) => setUser(item)" v-else>
                    <span
                        class="cursor-pointer inline-flex justify-center px-3.5 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        <span class="sr-only">Search</span>
                    </span>
                </SearchUserModal>
                <JetInputError :message="form.errors.user_id" class="mt-2"/>
            </div>

            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="amount" value="Amount"/>
                <JetInput
                    id="amount"
                    ref="amountInput"
                    v-model="form.amount"
                    type="number"
                    min="0"
                    class="mt-1 block w-full"
                />
                <JetInputError :message="form.errors.amount" class="mt-2"/>
                <p class="mt-2 text-sm text-gray-500">Available Balance {{ currencySymbol + ' ' + balance }}</p>
            </div>

            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="note" value="Note"/>
                <JetInput
                    id="note"
                    ref="noteInput"
                    v-model="form.note"
                    type="text"
                    class="mt-1 block w-full"
                />
                <JetInputError :message="form.errors.note" class="mt-2"/>
            </div>

        </template>

        <template #actions>
            <JetActionMessage :on="form.recentlySuccessful" class="mr-3">
                Money Sent.
            </JetActionMessage>

            <JetButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Send
            </JetButton>
        </template>
    </JetFormSection>
</template>
