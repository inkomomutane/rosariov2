
<script setup lang="ts">
import { computed, type PropType } from 'vue';
import { useForm } from '@inertiajs/vue3';
import {
    ArrowRight,
    CalendarDays,
    CheckCircle2,
    ClipboardCheck,
    FileText,
    Hash,
    LoaderCircle,
    ShieldCheck,
} from 'lucide-vue-next';

import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Textarea } from '@/components/ui/textarea';
import {
    Dialog,
    DialogFooter,
    DialogHeader,
    DialogScrollContent,
    DialogTitle,
    DialogDescription,
} from '@/components/ui/dialog';

import { t } from '@/lib/utils';

interface CaseData {
    id: string;
    case_code: string;
    title: string;
    description: string | null;
    notes?: string | null;
    status: string;
    priority: string;
    case_date: string | null;
}

const props = defineProps({
    case: {
        type: Object as PropType<CaseData>,
        required: true,
    },
    close: {
        type: Function as PropType<() => void>,
        required: true,
    },
    openModal: {
        type: Boolean,
        required: true,
    },
});

const form = useForm({});

const formatDate = (value: string | null | undefined) => {
    if (!value) return '—';

    const date = new Date(`${value.substring(0, 10)}T00:00:00Z`);

    if (Number.isNaN(date.getTime())) return '—';

    return new Intl.DateTimeFormat('en-GB', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        timeZone: 'UTC',
    }).format(date);
};

const formatLabel = (value: string) =>
    value
        .replace(/[_-]/g, ' ')
        .replace(/\b\w/g, (letter) => letter.toUpperCase());

const priorityVariant = computed(() => {
    switch (props.case.priority?.toLowerCase()) {
        case 'critical':
        case 'emergency':
            return 'destructive';
        case 'normal':
            return 'secondary';
        default:
            return 'outline';
    }
});

const statusVariant = computed(() => {
    switch (props.case.status?.toLowerCase()) {
        case 'paid':
        case 'completed':
            return 'default';
        case 'cancelled':
        case 'rejected':
            return 'destructive';
        default:
            return 'secondary';
    }
});

const applyToReviewCase = () => {
    form.post(route('case-apply', props.case.id), {
        onSuccess: () => {
            props.close();
        },
    });
};
</script>

<template>
    <Dialog
        :open="openModal"
        @update:open="(open) => {if (!open && !form.processing) close();
        }"
    >
        <DialogScrollContent class="max-w-lg gap-0 p-0">

            <!-- Header -->
            <DialogHeader class="space-y-2 border-b px-6 py-5">
                <div class="flex items-center gap-2">
                    <div class="flex size-9 items-center justify-center rounded-lg bg-muted">
                        <ClipboardCheck class="size-5 text-foreground" />
                    </div>

                    <div class="min-w-0 flex-1">
                        <DialogTitle class="text-lg font-semibold">
                            {{ t('Apply to review') }}
                        </DialogTitle>
                    </div>
                </div>
            </DialogHeader>

            <div class="space-y-6 px-6 py-5">

                <!-- Case overview -->
                <section class="space-y-3">
                    <div class="flex items-center gap-2 text-xs font-medium uppercase tracking-wide text-muted-foreground">
                        <FileText class="size-3.5" />
                        {{ t('Case overview') }}
                    </div>

                    <div class="space-y-1.5">
                        <h3 class="text-base font-semibold leading-snug">
                            {{ props.case.title }}
                        </h3>

                        <p class="whitespace-pre-line break-words text-sm leading-relaxed text-muted-foreground">
                            {{ props.case.description || '—' }}
                        </p>
                    </div>
                </section>

                <!-- Case information -->
                <section>
                    <div class="divide-y rounded-lg border px-4">

                        <div class="flex items-start justify-between gap-4 py-3">
                            <div class="flex items-center gap-2 text-sm text-muted-foreground">
                                <Hash class="size-4 shrink-0" />
                                {{ t('Reference') }}
                            </div>

                            <span class="max-w-[65%] break-all text-right font-mono text-xs font-medium">
                                {{ props.case.case_code }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4 py-3">
                            <div class="flex items-center gap-2 text-sm text-muted-foreground">
                                <CheckCircle2 class="size-4 shrink-0" />
                                {{ t('Status') }}
                            </div>

                            <Badge :variant="statusVariant">
                                {{ t(formatLabel(props.case.status)) }}
                            </Badge>
                        </div>

                        <div class="flex items-center justify-between gap-4 py-3">
                            <div class="flex items-center gap-2 text-sm text-muted-foreground">
                                <ShieldCheck class="size-4 shrink-0" />
                                {{ t('Urgency') }}
                            </div>

                            <Badge :variant="priorityVariant">
                                {{ t(formatLabel(props.case.priority)) }}
                            </Badge>
                        </div>

                        <div class="flex items-center justify-between gap-4 py-3">
                            <div class="flex items-center gap-2 text-sm text-muted-foreground">
                                <CalendarDays class="size-4 shrink-0" />
                                {{ t('Case date') }}
                            </div>

                            <span class="text-right text-sm font-medium">
                                {{ formatDate(props.case.case_date) }}
                            </span>
                        </div>

                    </div>
                </section>
            </div>

            <!-- Footer -->
            <DialogFooter class="border-t px-6 py-4 sm:justify-end">
                <Button
                    type="button"
                    variant="outline"
                    :disabled="form.processing"
                    @click="close"
                >
                    {{ t('Cancel') }}
                </Button>

                <Button
                    type="button"
                    class="min-w-36 gap-2"
                    :disabled="form.processing"
                    @click="applyToReviewCase"
                >
                    <template v-if="form.processing">
                        <LoaderCircle class="size-4 animate-spin" />
                        {{ t('Processing...') }}
                    </template>

                    <template v-else>
                        {{ t('Apply to review') }}
                        <ArrowRight class="size-4" />
                    </template>
                </Button>
            </DialogFooter>

        </DialogScrollContent>
    </Dialog>
</template>
