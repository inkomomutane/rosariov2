
<script setup lang="ts">
import { computed, type PropType } from 'vue';
import {
    ShieldCheck,
    Tag,
    MapPin,
    Mail,
    Phone,
} from 'lucide-vue-next';

import type { UserFrontData } from '@/types/generated';
import { t } from '@/lib/utils';
import { MONEY } from '@/lib/helpers';

const props = defineProps({
    user: {
        type: Object as PropType<UserFrontData>,
        required: true,
    },
});

const isDoctor = computed(() => props.user.type === 'doctor');

const profileDisplay = computed(() => ({
    name: props.user.name || t('Anonymous User'),

    headline: isDoctor.value
        ? props.user.specialization || t('Medical Professional')
        : t('Patient Profile'),

    address: props.user.address || t('Location not specified'),

    email: props.user.email,
    phone: props.user.phone,

    verified: props.user.verified ?? false,

    affiliations: isDoctor.value
        ? [
            props.user.specialization,
            t('Medical Professional'),
        ].filter(Boolean)
        : [t('Patient')],
}));

const stats = computed(() => [
    {
        label: t('Cases'),
        value: props.user.cases ?? 0,
    },
    {
        label: t('Experience'),
        value: isDoctor.value
            ? props.user.year_of_experience
                ? `${props.user.year_of_experience} ${t('Years')}`
                : 'N/A'
            : null,
    },
    {
        label: t('Status'),
        value: props.user.verified
            ? t('Verified')
            : t('Unverified'),
    },
    {
        label: t('Balance'),
        value: MONEY(props.user.balance ?? '0.00').format(),
    },
].filter(stat => stat.value !== null));

const initials = computed(() => {
    const name = profileDisplay.value.name.trim();

    if (!name) return '??';

    const parts = name.split(/\s+/);

    return parts.length >= 2
        ? (parts[0][0] + parts[1][0]).toUpperCase()
        : name.slice(0, 2).toUpperCase();
});
</script>

<template>
    <aside class="hidden md:col-span-3 md:block mt-4 space-y-3">
        <div class="flex items-center justify-center">
            <div class="w-80 max-w-full space-y-3">

                <!-- Profile Card -->
                <div
                    class="overflow-hidden rounded border border-gray-200 bg-white dark:border-zinc-800 dark:bg-zinc-900"
                >
                    <!-- Cover -->
                    <div class="relative h-18 bg-zinc-800">
                        <div
                            class="absolute bottom-0 left-4 flex h-16 w-16 translate-y-1/2 items-center justify-center rounded-full border-2 border-white bg-gray-800 text-lg font-semibold text-white dark:border-zinc-900"
                        >
                            {{ initials }}
                        </div>
                    </div>

                    <!-- Profile Information -->
                    <div class="px-4 pb-4 pt-10">
                        <div class="mb-0.5 flex items-center gap-1.5">
                            <h2
                                class="max-w-50 truncate text-base font-semibold leading-tight text-gray-900 dark:text-zinc-100"
                            >
                                {{ profileDisplay.name }}
                            </h2>

                            <ShieldCheck
                                v-if="profileDisplay.verified"
                                class="h-4 w-4 shrink-0 text-blue-500"
                            />
                        </div>

                        <p
                            class="mb-2 text-sm leading-snug text-gray-600 dark:text-zinc-400"
                        >
                            {{ profileDisplay.headline }}
                        </p>

                        <div class="mb-3 space-y-2">
                            <!-- Address -->
                            <div class="flex items-start gap-2">
                                <MapPin
                                    class="mt-0.5 h-3.5 w-3.5 shrink-0 text-gray-400"
                                />
                                <span
                                    class="text-xs text-gray-500 dark:text-zinc-400"
                                >
                                    {{ profileDisplay.address }}
                                </span>
                            </div>

                            <!-- Email -->
                            <div
                                v-if="profileDisplay.email"
                                class="flex items-start gap-2"
                            >
                                <Mail
                                    class="mt-0.5 h-3.5 w-3.5 shrink-0 text-gray-400"
                                />
                                <span
                                    class="min-w-0 break-all text-xs text-gray-500 dark:text-zinc-400"
                                >
                                    {{ profileDisplay.email }}
                                </span>
                            </div>

                            <!-- Phone -->
                            <div
                                v-if="profileDisplay.phone"
                                class="flex items-center gap-2"
                            >
                                <Phone
                                    class="h-3.5 w-3.5 shrink-0 text-gray-400"
                                />
                                <span
                                    class="text-xs text-gray-500 dark:text-zinc-400"
                                >
                                    {{ profileDisplay.phone }}
                                </span>
                            </div>
                        </div>

                        <!-- Affiliations -->
                        <div class="space-y-1.5">
                            <div
                                v-for="(affiliation, index) in profileDisplay.affiliations"
                                :key="index"
                                class="flex items-center gap-2"
                            >
                                <Tag
                                    class="h-3.5 w-3.5 shrink-0 text-blue-500"
                                />

                                <span
                                    class="text-xs leading-tight text-gray-700 dark:text-zinc-300"
                                >
                                    {{ affiliation }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div
                    class="divide-y divide-gray-100 rounded-md border border-gray-200 bg-white dark:divide-zinc-800 dark:border-zinc-800 dark:bg-zinc-900"
                >
                    <div
                        v-for="stat in stats"
                        :key="stat.label"
                        class="flex items-center justify-between px-4 py-3"
                    >
                        <span
                            class="text-sm text-gray-700 dark:text-zinc-300"
                        >
                            {{ stat.label }}
                        </span>

                        <span
                            class="text-sm  text-gray-700 dark:text-zinc-300 font-mono"
                        >
                            {{ stat.value }}
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </aside>
</template>
