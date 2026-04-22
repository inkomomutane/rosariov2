<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Card, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import {ShieldCheck,Tag } from 'lucide-vue-next'

import { computed } from 'vue'

const profile = {
    name: 'Doctor Mário Chissano',
    headline: 'Urologist at Hospital Central de Beira',
    location: 'Sofala Province',
    avatarUrl: '',
    affiliations: ['Urologist','Health Advocate'],
}

const stats = [
    { label: 'Cases', value: 3 },
    { label: 'Balance', value: 25800.93 },
    { label: 'Reviews', value: 120 },
]

const initials = computed(() => {
    const parts = profile.name.trim().split(' ')
    return parts.length >= 2
        ? (parts[0][0] + parts[1][0]).toUpperCase()
        : parts[0].slice(0, 2).toUpperCase()
})
</script>

<template>
    <aside class="hidden md:block md:col-span-3 space-y-3">
        <div class="flex items-center justify-center">
            <div class="w-80 space-y-3">

                <div class="rounded border border-gray-200 overflow-hidden bg-white dark:bg-zinc-900 dark:border-zinc-800">

                    <div class="h-18 bg-zinc-800 relative">
                        <div
                            class="absolute bottom-0 left-4 translate-y-1/2 w-16 h-16 rounded-full border-2 border-white dark:border-zinc-900 overflow-hidden bg-gray-800"
                        >
                            <img
                                v-if="profile.avatarUrl"
                                :src="profile.avatarUrl"
                                :alt="profile.name"
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
                                {{ profile.name }}
                            </h2>
                            <span class="shrink-0">

                                <ShieldCheck class="w-4 h-4 font-bold font-600" />

                    </span>
                        </div>

                        <p class="text-sm text-gray-600 dark:text-zinc-400 leading-snug mb-1">
                            {{ profile.headline }}
                        </p>

                        <p class="text-xs text-gray-500 dark:text-zinc-500 mb-3">{{ profile.location }}</p>

                        <div class="space-y-1.5">
                            <div
                                v-for="(aff, i) in profile.affiliations"
                                :key="i"
                                class="flex items-center gap-2"
                            >
                                <Tag class="w-3.5 h-3.5 text-blue-500 shrink-0" />
                                <span class="text-xs text-gray-700 dark:text-zinc-300 leading-tight">{{ aff }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-zinc-900 rounded-md  border border-gray-200 dark:border-zinc-800 divide-y divide-gray-100 dark:divide-zinc-800">
                    <div
                        v-for="stat in stats"
                        :key="stat.label"
                        class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 dark:hover:bg-zinc-800/50 cursor-pointer transition-colors"
                    >
                        <span class="text-sm text-gray-700 dark:text-zinc-300">{{ stat.label }}</span>
                        <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">
                    {{ stat.value.toLocaleString() }}
                </span>
                    </div>
                </div>

            </div>
        </div>
    </aside>
</template>
