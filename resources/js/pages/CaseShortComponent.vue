<script setup lang="ts">
import { computed, PropType } from 'vue'
import { Badge } from '@/components/ui/badge';
import {
    MessageSquare,
    Bookmark,
    MoreVertical,
    CheckCircle2,
    Paperclip
} from 'lucide-vue-next'

// Define the prop as an object with the medicalCase key
const props = defineProps({
    medicalCase: {
        type: Object as PropType<FullPatientCaseDto>,
        required: true
    }
})

// Helper to format date
const formattedDate = computed(() => {
    if (!props.medicalCase.case_date) return 'No date set'
    return new Date(props.medicalCase.case_date).toLocaleDateString(undefined, {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
    })
})

// Extract initials for the avatar
const initials = computed(() => {
    const first = props.medicalCase.patient_name?.[0] || ''
    const last = props.medicalCase.patient_last_name?.[0] || ''
    return (first + last).toUpperCase() || '??'
})

// Highlight hashtags in description
function renderDescription(text: string | null): string {
    if (!text) return ''
    return text.replace(/#(\w+)/g, '<span class="text-blue-500 dark:text-blue-400 cursor-pointer hover:underline font-medium">#$1</span>')
}

// Map urgency to colors
const urgencyStyles = computed(() => {
    const priority = props.medicalCase.priority?.toLowerCase()
    if (priority === 'urgent') return 'text-red-500 bg-red-50 dark:bg-red-500/10'
    if (priority === 'high') return 'text-orange-500 bg-orange-50 dark:bg-orange-500/10'
    return 'text-zinc-500 bg-zinc-100 dark:bg-zinc-800'
})

const actions = [
    { label: 'Review', icon: MessageSquare },
    { label: 'Save', icon: Bookmark },
]
</script>

<template>
    <div class="dark:bg-zinc-950 flex items-center justify-center transition-colors duration-300 p-4">
        <div class="w-full max-w-2xl">
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden shadow-sm">

                <!-- Header -->
                <div class="flex items-start justify-between px-4 pt-4 pb-3">
                    <div class="flex items-center gap-3">
                        <!-- Patient Avatar -->
                        <div class="w-11 h-11 rounded-full bg-zinc-800 border border-zinc-700 shrink-0 flex items-center justify-center text-white font-semibold text-sm">
                            {{ initials }}
                        </div>

                        <div>
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 leading-tight">
                                   {{ medicalCase.patient_name }} {{ medicalCase.patient_last_name }}
                                </span>
                                <CheckCircle2 v-if="medicalCase.patient_verified" class="w-3.5 h-3.5 text-blue-500" />
                                <span class="text-xs text-zinc-400 dark:text-zinc-500">•</span>
                                <span class="text-xs font-mono text-zinc-400 uppercase">{{ medicalCase.case_code }}</span>
                            </div>

                            <div class="flex items-center gap-2 mt-1">
                                <span :class="['text-[10px] px-1.5 py-0.5 rounded font-bold uppercase tracking-wider', urgencyStyles]">
                                    {{ medicalCase.priority || 'Routine' }}
                                </span>
                                <span class="text-[10px] text-zinc-500 dark:text-zinc-400 bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded uppercase font-medium">
                                    {{ medicalCase.case_type }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <button class="text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 p-1.5 rounded-lg transition-colors">
                        <MoreVertical class="w-4 h-4" />
                    </button>
                </div>

                <!-- Post Body -->
                <div class="px-4 pb-4">
                    <div class="flex items-center justify-between mb-1">
                        <h3 class="text-sm font-bold text-zinc-900 dark:text-zinc-100">
                            {{ medicalCase.title }}
                        </h3>
                        <span class="text-[11px] text-zinc-400">{{ formattedDate }}</span>
                    </div>
                    <p
                        class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed whitespace-pre-wrap"
                        v-html="renderDescription(medicalCase.description)"
                    />
                </div>

                <!-- Medical Personnel Info -->
                <div class="flex items-center gap-6 px-4 pb-4 border-b border-zinc-50 dark:border-zinc-800/50">
                    <div class="flex flex-col">
                        <span class="text-[10px] uppercase text-zinc-400 font-bold tracking-tight">Assigned Doctor</span>
                        <span class="text-xs font-medium text-zinc-700 dark:text-zinc-300">
                            {{ medicalCase.assigned_doctor_name || 'TBD' }}
                        </span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] uppercase text-zinc-400 font-bold tracking-tight">Requester</span>
                        <span class="text-xs text-zinc-500">{{ medicalCase.requester_doctor_name }}</span>
                    </div>
                </div>

                <!-- Status & Files -->
                <div class="px-4 py-3 flex items-center justify-between bg-zinc-50/30 dark:bg-zinc-800/20">
                    <div class="flex items-center gap-3">
                        <Badge variant="secondary" class="capitalize text-[11px] px-2 py-0">
                            {{ medicalCase.status?.toLowerCase().replace('_', ' ') || 'Pending' }}
                        </Badge>
                        <div v-if="medicalCase.attachments?.length" class="flex items-center gap-1 text-zinc-400">
                            <Paperclip class="w-3 h-3" />
                            <span class="text-[11px] font-medium">{{ medicalCase.attachments.length }}</span>
                        </div>
                    </div>

                    <div v-if="medicalCase.last_reviewer_doctor_name" class="text-right">
                        <span class="text-[10px] text-zinc-400 block italic">Last reviewed by {{ medicalCase.last_reviewer_doctor_name }}</span>
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="flex items-center px-2 py-1 border-t border-zinc-100 dark:border-zinc-800">
                    <button
                        v-for="action in actions"
                        :key="action.label"
                        class="flex-1 flex items-center justify-center gap-1.5 py-2.5 rounded-lg text-xs font-semibold text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-all"
                    >
                        <component :is="action.icon" class="w-4 h-4" />
                        {{ action.label }}
                    </button>
                </div>

            </div>
        </div>
    </div>
</template>
