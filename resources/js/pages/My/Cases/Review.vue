
<script setup lang="ts">
import { computed, watch } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';

import AppLayout from '@/layouts/AppLayout.vue';

import {
    ArrowDownToLine,
    CalendarDays,
    CheckCheck,
    ClipboardList,
    File,
    FileImage,
    FileText,
    FolderOpen,
    LoaderCircle,
    Save,
} from 'lucide-vue-next';

import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import { Textarea } from '@/components/ui/textarea';

import { t } from '@/lib/utils';

interface Person {
    id: string;
    name: string | null;
    middle_name?: string | null;
    last_name: string | null;
}

interface CaseAttachment {
    id?: string | number;
    name?: string;
    file_name?: string;
    original_name?: string;
    mime_type?: string;
    size?: number;
    url?: string;
    original_url?: string;
    download_url?: string;
    preview_url?: string;
}

interface CaseReview {
    id: string;
    case_id: string;
    reviewer_doctor_id: string;
    review_notes: string | null;
    review_date: string | null;
    review_conclusion: string | null;
    status: string;
}

interface PatientCase {
    id: string;
    case_code: string;
    person_id: string;
    priority: string;
    title: string;
    description: string | null;
    case_type: string;
    status: string;
    case_date: string | null;
    created_at: string;
    updated_at: string;
    patient: Person | null;
    assigned_doctor: Person | null;
    attachments?: CaseAttachment[];
    uploaded_attachments?: CaseAttachment[];
}

const props = defineProps<{
    case: PatientCase;
    review: CaseReview | null;
}>();

const breadcrumbs = computed(() => [
    {
        title: t('Medical Cases'),
        href: '#',
    },
    {
        title: props.case.case_code,
        href: '#',
    },
]);

// -------------------------------------------
// Helpers
// -------------------------------------------

function formatLabel(value?: string | null): string {
    if (!value) return '—';

    return t(
        value
            .replace(/[_-]/g, ' ')
            .replace(/\b\w/g, (letter) => letter.toUpperCase()),
    );
}

function formatDate(value?: string | null): string {
    if (!value) return '—';

    const date = new Date(
        `${value.substring(0, 10)}T12:00:00Z`,
    );

    if (Number.isNaN(date.getTime())) return '—';

    return new Intl.DateTimeFormat('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        timeZone: 'UTC',
    }).format(date);
}

function fullName(person?: Person | null): string {
    if (!person) return t('Not assigned');

    return [
        person.name,
        person.middle_name,
        person.last_name,
    ].filter(Boolean).join(' ').trim() || t('Unknown');
}

function fileName(file: CaseAttachment): string {
    return file.name
        || file.original_name
        || file.file_name
        || t('Document');
}

function fileUrl(file: CaseAttachment): string | null {
    return file.download_url
        || file.original_url
        || file.url
        || file.preview_url
        || null;
}

function fileSize(bytes?: number): string {
    if (bytes == null) return '';

    if (bytes < 1024) return `${bytes} B`;
    if (bytes < 1048576) {
        return `${(bytes / 1024).toFixed(1)} KB`;
    }

    return `${(bytes / 1048576).toFixed(1)} MB`;
}

function fileIcon(file: CaseAttachment) {
    const mime = file.mime_type ?? '';

    if (mime.startsWith('image/')) return FileImage;

    if (mime.includes('pdf') || mime.startsWith('text/')) {
        return FileText;
    }

    return File;
}

// -------------------------------------------
// Status and priority
// -------------------------------------------

const statusClasses: Record<string, string> = {
    paid: 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950 dark:text-emerald-300',
    completed: 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950 dark:text-emerald-300',
    under_review: 'border-blue-200 bg-blue-50 text-blue-700 dark:border-blue-800 dark:bg-blue-950 dark:text-blue-300',
    submitted: 'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-800 dark:bg-amber-950 dark:text-amber-300',
    cancelled: 'border-red-200 bg-red-50 text-red-700 dark:border-red-800 dark:bg-red-950 dark:text-red-300',
};

const priorityClasses: Record<string, string> = {
    low: 'border-slate-200 bg-slate-50 text-slate-700 dark:bg-slate-900 dark:text-slate-300',
    normal: 'border-blue-200 bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-300',
    emergency: 'border-red-200 bg-red-50 text-red-700 dark:bg-red-950 dark:text-red-300',
    critical: 'border-red-200 bg-red-50 text-red-700 dark:bg-red-950 dark:text-red-300',
};

const currentStatus = computed(() => ({
    label: formatLabel(props.case.status),
    classes: statusClasses[props.case.status] || '',
}));

const currentPriority = computed(() => ({
    label: formatLabel(props.case.priority),
    classes: priorityClasses[props.case.priority] || '',
}));

// -------------------------------------------
// Review
// -------------------------------------------

const reviewForm = useForm({
    review_notes: props.review?.review_notes ?? '',
});

watch(
    () => props.review,
    (review) => {
        if (reviewForm.processing) return;

        reviewForm.review_notes = review?.review_notes ?? '';
        reviewForm.defaults();
        reviewForm.clearErrors();
    },
);

const canEditReview = computed(() =>
    !!props.review &&
    ['pending', 'in_progress'].includes(props.review.status),
);

const canUpdateReview = computed(() =>
    canEditReview.value &&
    reviewForm.isDirty &&
    !reviewForm.processing,
);

const canCompleteReview = computed(() =>
    canEditReview.value &&
    reviewForm.review_notes.trim().length > 0 &&
    !reviewForm.processing,
);

const isCompleted = computed(() =>
    props.review?.status === 'completed',
);

function updateReview() {
    if (
        !props.review ||
        !canUpdateReview.value
    ) return;

    reviewForm.post(
        route('case-review-update', {
            review: props.review.id,
        }),
        {
            preserveScroll: true,
            onSuccess: () => {
                reviewForm.defaults();
            },
        },
    );
}

function completeReview() {
    if (
        !props.review ||
        !canCompleteReview.value
    ) return;

    reviewForm.post(
        route('case-review-complete', {
            review: props.review.id,
        }),
        {
            preserveScroll: true,
        },
    );
}

// -------------------------------------------
// Attachments
// -------------------------------------------

const attachments = computed(() => {
    const all = [
        ...(props.case.media ?? []),
    ];

    const seen = new Set<string>();

    return all.filter((file) => {
        const key = file.id != null
            ? `id:${file.id}`
            : `${fileName(file)}:${fileUrl(file) ?? ''}:${file.size ?? ''}`;

        if (seen.has(key)) return false;

        seen.add(key);
        return true;
    });
});
</script>

<template>
    <Head :title="`${t('Review')} - ${props.case.case_code}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex min-w-0 flex-1 flex-col gap-5 p-4 md:p-6">

            <!-- PAGE HEADER -->
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="min-w-0 space-y-2">
                    <h1 class="text-xl font-semibold tracking-tight md:text-2xl">
                        {{ props.case.title }}
                    </h1>

                    <p class="break-all font-mono text-xs text-muted-foreground">
                        {{ props.case.case_code }}
                    </p>
                </div>

                <Badge
                    variant="outline"
                    :class="currentStatus.classes"
                >
                    {{ currentStatus.label }}
                </Badge>
            </div>

            <!-- MAIN GRID -->
            <div class="grid min-w-0 grid-cols-1 items-start gap-5 xl:grid-cols-12">

                <!-- LEFT SIDEBAR -->
                <div class="min-w-0 space-y-5 xl:col-span-4">

                    <!-- CASE SUMMARY -->
                    <Card class="shadow-none">
                        <CardHeader class="pb-3">
                            <CardTitle class="flex items-center gap-2 text-sm">
                                <ClipboardList class="size-4" />
                                {{ t('Summary') }}
                            </CardTitle>
                        </CardHeader>

                        <CardContent class="space-y-4">

                            <div class="space-y-1">
                                <p class="text-xs text-muted-foreground">
                                    {{ t('Patient') }}
                                </p>
                                <p class="text-sm font-medium">
                                    {{ fullName(props.case.patient) }}
                                </p>
                            </div>

                            <Separator />

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <p class="text-xs text-muted-foreground">
                                        {{ t('Type') }}
                                    </p>
                                    <p class="text-sm font-medium">
                                        {{ formatLabel(props.case.case_type) }}
                                    </p>
                                </div>

                                <div class="space-y-1">
                                    <p class="text-xs text-muted-foreground">
                                        {{ t('Case Date') }}
                                    </p>
                                    <p class="text-sm font-medium">
                                        {{ formatDate(props.case.case_date) }}
                                    </p>
                                </div>
                            </div>

                            <Separator />

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <p class="text-xs text-muted-foreground">
                                        {{ t('Status') }}
                                    </p>

                                    <Badge
                                        variant="outline"
                                        :class="currentStatus.classes"
                                    >
                                        {{ currentStatus.label }}
                                    </Badge>
                                </div>

                                <div class="space-y-1">
                                    <p class="text-xs text-muted-foreground">
                                        {{ t('Urgency') }}
                                    </p>

                                    <Badge
                                        variant="outline"
                                        :class="currentPriority.classes"
                                    >
                                        {{ currentPriority.label }}
                                    </Badge>
                                </div>
                            </div>

                            <Separator />

                            <div class="space-y-1">
                                <p class="text-xs text-muted-foreground">
                                    {{ t('Assigned Doctor') }}
                                </p>
                                <p class="text-sm font-medium">
                                    {{ fullName(props.case.assigned_doctor) }}
                                </p>
                            </div>

                        </CardContent>
                    </Card>

                    <!-- DOCUMENTS -->
                    <Card class="shadow-none">
                        <CardHeader class="pb-3">
                            <div class="flex items-center justify-between gap-3">
                                <CardTitle class="flex items-center gap-2 text-sm">
                                    <FolderOpen class="size-4" />
                                    {{ t('Files / Documents') }}
                                </CardTitle>

                                <Badge variant="secondary">
                                    {{ attachments.length }}
                                </Badge>
                            </div>
                        </CardHeader>

                        <CardContent>
                            <div
                                v-if="attachments.length"
                                class="divide-y rounded-lg border"
                            >
                                <div
                                    v-for="(file, index) in attachments"
                                    :key="file.id ?? index"
                                    class="flex items-center gap-3 p-3"
                                >
                                    <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-muted">
                                        <component
                                            :is="fileIcon(file)"
                                            class="size-5 text-muted-foreground"
                                        />
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-medium">
                                            {{ fileName(file) }}
                                        </p>

                                        <p class="mt-1 text-xs text-muted-foreground">
                                            {{ fileSize(file.size) || t('Document') }}
                                        </p>
                                    </div>

                                    <Button
                                        v-if="fileUrl(file)"
                                        variant="ghost"
                                        size="icon"
                                        class="size-8 shrink-0"
                                        as-child
                                    >
                                        <a
                                            :href="fileUrl(file)!"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            :aria-label="`${t('Open')} ${fileName(file)}`"
                                        >
                                            <ArrowDownToLine class="size-4" />
                                        </a>
                                    </Button>
                                </div>
                            </div>

                            <div
                                v-else
                                class="flex min-h-40 flex-col items-center justify-center rounded-lg border border-dashed p-6 text-center"
                            >
                                <FolderOpen class="mb-3 size-8 text-muted-foreground" />

                                <p class="text-sm font-medium">
                                    {{ t('No documents available') }}
                                </p>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- ================================= -->
                <!-- REVIEWS & CASE DETAILS -->
                <!-- ================================= -->

                <Card class="min-w-0 shadow-none xl:col-span-8">

                    <CardHeader class="border-b pb-4">
                        <div class="flex items-center justify-between gap-3">
                            <div class="space-y-1">
                                <CardTitle class="flex items-center gap-2 text-base">
                                    <ClipboardList class="size-4" />
                                    {{ t('Review') }}
                                </CardTitle>

                                <p class="text-xs text-muted-foreground">
                                    {{ t('Document and manage your case review.') }}
                                </p>
                            </div>

                            <Badge
                                v-if="review"
                                variant="outline"
                                :class="isCompleted
                                    ? statusClasses.completed
                                    : ''"
                            >
                                {{ formatLabel(review.status) }}
                            </Badge>
                        </div>
                    </CardHeader>

                    <CardContent class="space-y-6 pt-5">

                        <!-- CASE DETAILS -->
                        <div class="space-y-3">
                            <h3 class="text-sm font-semibold">
                                {{ t('Case Details') }}
                            </h3>

                            <h4 class="text-base font-medium">
                                {{ props.case.title }}
                            </h4>

                            <p class="whitespace-pre-line break-words text-sm leading-relaxed text-muted-foreground">
                                {{ props.case.description || '—' }}
                            </p>
                        </div>

                        <Separator />

                        <!-- REVIEW INFORMATION -->
                        <div class="grid gap-4 sm:grid-cols-2">

                            <div class="space-y-1">
                                <p class="text-xs text-muted-foreground">
                                    {{ t('Review Status') }}
                                </p>

                                <p class="text-sm font-medium">
                                    {{ formatLabel(review?.status) }}
                                </p>
                            </div>

                            <div class="space-y-1">
                                <p class="text-xs text-muted-foreground">
                                    {{ t('Review Date') }}
                                </p>

                                <p class="flex items-center gap-2 text-sm font-medium">
                                    <CalendarDays class="size-4 text-muted-foreground" />
                                    {{ formatDate(review?.review_date) }}
                                </p>
                            </div>
                        </div>

                        <Separator />

                        <!-- REVIEW EDITOR -->
                        <div class="space-y-3">

                            <Label
                                for="case-review"
                                class="text-sm font-semibold"
                            >
                                {{ t('Review') }}
                            </Label>

                            <Textarea
                                id="case-review"
                                v-model="reviewForm.review_notes"
                                :placeholder="t('Write your review here...')"
                                :disabled="!canEditReview || reviewForm.processing"
                                class="min-h-72 resize-y text-sm leading-relaxed"
                            />

                            <p
                                v-if="reviewForm.errors.review_notes"
                                class="text-xs text-destructive"
                            >
                                {{ reviewForm.errors.review_notes }}
                            </p>

                            <p
                                v-else-if="isCompleted"
                                class="text-xs text-muted-foreground"
                            >
                                {{ t('This review has been completed and can no longer be edited.') }}
                            </p>

                            <p
                                v-else-if="!review"
                                class="text-xs text-muted-foreground"
                            >
                                {{ t('No review has been assigned to you.') }}
                            </p>

                            <p
                                v-else
                                class="text-xs text-muted-foreground"
                            >
                                {{ t('Save your progress or complete your review when ready.') }}
                            </p>
                        </div>

                        <!-- REVIEW ACTIONS -->
                        <div
                            v-if="canEditReview"
                            class="flex flex-wrap items-center justify-end gap-3 border-t pt-5"
                        >
                            <Button
                                type="button"
                                variant="outline"
                                class="gap-2"
                                :disabled="!canUpdateReview"
                                @click="updateReview"
                            >
                                <LoaderCircle
                                    v-if="reviewForm.processing"
                                    class="size-4 animate-spin"
                                />
                                <Save v-else class="size-4" />

                                {{ t('Update') }}
                            </Button>

                            <Button
                                type="button"
                                class="gap-2"
                                :disabled="!canCompleteReview"
                                @click="completeReview"
                            >
                                <LoaderCircle
                                    v-if="reviewForm.processing"
                                    class="size-4 animate-spin"
                                />
                                <CheckCheck v-else class="size-4" />

                                {{ t('Complete Review') }}
                            </Button>
                        </div>

                        <!-- COMPLETED STATE -->
                        <div
                            v-else-if="isCompleted"
                            class="flex items-center gap-3 rounded-lg border bg-muted/30 p-4"
                        >
                            <CheckCheck class="size-5 shrink-0 text-emerald-600" />

                            <div class="space-y-1">
                                <p class="text-sm font-semibold">
                                    {{ t('Review Completed') }}
                                </p>

                                <p class="text-xs text-muted-foreground">
                                    {{ t('Completed on') }}
                                    {{ formatDate(review?.review_date) }}
                                </p>
                            </div>
                        </div>

                    </CardContent>
                </Card>

            </div>
        </div>
    </AppLayout>
</template>
