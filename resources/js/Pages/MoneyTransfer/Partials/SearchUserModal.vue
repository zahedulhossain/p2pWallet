<script setup>
import _ from "lodash";
import { computed, ref, watch} from 'vue';
import { usePage } from '@inertiajs/inertia-vue3';
import JetInput from '@/Jetstream/Input.vue';
import JetDialogModal from '@/Jetstream/DialogModal'
import JetSecondaryButton from '@/Jetstream/SecondaryButton'

const emit = defineEmits(['close', 'selectUser']);

const users = ref(null);
const auth = computed(() => usePage().props.value.user);
const searchingUser = ref(false);
const searchInput = ref('');

const searchUsers = () => {
    axios.get(route('search-users.index', {'search': searchInput.value, 'excludeId': auth.value.id}))
        .then(response => users.value = response.data);
};

watch(searchInput,
    _.throttle(() => {
        searchUsers()
    }, 1000)
);

</script>


<template>
    <span>
        <span @click="searchingUser = true">
            <slot/>
        </span>

        <JetDialogModal :show="searchingUser" @close="searchingUser = false">
            <template #title>
                Search users
            </template>

            <template #content>
                <div class="mt-4">
                    <JetInput type="text" class="mt-1 block w-full" placeholder="name or email"
                              ref="search"
                              v-model="searchInput"/>
                </div>

                <ul class="divide-y divide-gray-200">
                    <li v-for="user in users" class="py-4">
                        <div class="flex items-center space-x-4">
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
                            <div>
                                <JetSecondaryButton @click="$emit('selectUser', user)">Select</JetSecondaryButton>
                            </div>
                        </div>
                    </li>
                </ul>
            </template>
    </JetDialogModal>
    </span>
</template>
