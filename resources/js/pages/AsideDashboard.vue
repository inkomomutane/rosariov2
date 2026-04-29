<script setup lang="ts">
import { computed, PropType } from 'vue';
import { ShieldCheck, Tag } from 'lucide-vue-next';
import { ProfileDto, FullPatientCaseDto } from '@/types/generated';
import { t } from '@/lib/utils';
import { MONEY } from '@/lib/helpers';

const props = defineProps({
    user: {
        type: Object as PropType<ProfileDto>,
        required: true
    },
    casesLength: {
        type: Number,
        default: () => 0
    },
})

// Derive profile information from the DTO
const profileDisplay = computed(() => ({
    name: props.user?.full_name || 'Anonymous User',
    headline: props.user?.is_doctor
        ? `${props.user.specialization || t('Medical Professional')} at ${props.user.hospital || t('Hospital')}`
        : t('Patient Profile'),
    location: [props.user?.city, props.user?.province].filter(Boolean).join(', ') || t('Location not specified'),
    avatarUrl: '', // DTO doesn't have an avatar field, keeping as empty for initials fallback
    verified: props.user?.verified ?? false,
    affiliations: props.user?.is_doctor
        ? [props.user.specialization, t('Medical Professional')].filter(Boolean)
        : [t('Patient Member')]
}))

const stats = computed(() => [
    {
        label: t('Cases'),
        value: props.casesLength || 0
    },
    {
        label: t('Experience'),
        value: props.user?.years_of_experience ? `${props.user.years_of_experience} Yrs` : 'N/A'
    },
    {
        label: t('Status'),
        value: props.user?.verified ? 'Verified' : 'Pending'
    },
    {
        label: t('Balance'),
        value:  props.user?.balance ?  MONEY(props.user.balance).format() : MONEY(0).format()
    }
])

const initials = computed(() => {
    const name = profileDisplay.value.name.trim();
    const parts = name.split(' ');
    return parts.length >= 2
        ? (parts[0][0] + parts[1][0]).toUpperCase()
        : name.slice(0, 2).toUpperCase();
})
</script>

<template>
    <aside class="hidden md:block md:col-span-3 space-y-3">
        <div class="flex items-center justify-center">
            <div class="w-80 space-y-3">
                <!-- Profile Card -->
                <div class="rounded border border-gray-200 overflow-hidden bg-white dark:bg-zinc-900 dark:border-zinc-800">
                    <div class="h-18 bg-zinc-800 relative">
                        <div class="absolute bottom-0 left-4 translate-y-1/2 w-16 h-16 rounded-full border-2 border-white dark:border-zinc-900 overflow-hidden bg-gray-800">
                            <img
                                v-if="profileDisplay.avatarUrl"
                                :src="profileDisplay.avatarUrl"
                                :alt="profileDisplay.name"
                                class="w-full h-full object-cover"
                            />
                            <div
                                v-else
                                class="w-full h-full flex items-center justify-center text-white text-lg font-semibold"
                            >
                                {{ initials }}
                            </div>
                        </div>
                    </div>

                    <div class="pt-10 pb-4 px-4">
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <h2 class="text-base font-semibold text-gray-900 dark:text-zinc-100 leading-tight truncate max-w-50">
                                {{ profileDisplay.name }}
                            </h2>
                            <span v-if="profileDisplay.verified" class="shrink-0">
                                <ShieldCheck class="w-4 h-4 text-blue-500" />
                            </span>
                        </div>

                        <p class="text-sm text-gray-600 dark:text-zinc-400 leading-snug mb-1">
                            {{ profileDisplay.headline }}
                        </p>

                        <p class="text-xs text-gray-500 dark:text-zinc-500 mb-3">
                            {{ profileDisplay.location }}
                        </p>

                        <div class="space-y-1.5">
                            <div
                                v-for="(aff, i) in profileDisplay.affiliations"
                                :key="i"
                                class="flex items-center gap-2"
                            >
                                <Tag class="w-3.5 h-3.5 text-blue-500 shrink-0" />
                                <span class="text-xs text-gray-700 dark:text-zinc-300 leading-tight">
                                    {{ aff }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats List -->
                <div class="bg-white dark:bg-zinc-900 rounded-md border border-gray-200 dark:border-zinc-800 divide-y divide-gray-100 dark:divide-zinc-800">
                    <div
                        v-for="stat in stats"
                        :key="stat.label"
                        class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 dark:hover:bg-zinc-800/50 cursor-pointer transition-colors"
                    >
                        <span class="text-sm text-gray-700 dark:text-zinc-300">{{ stat.label }}</span>
                        <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">
                            {{ stat.value }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </aside>
</template>
