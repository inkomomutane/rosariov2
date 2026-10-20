<script setup lang="ts">
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';

import AppLayout from '@/layouts/AppLayout.vue';

import {
    Activity,
    AlertCircle,
    ArrowDownToLine,
    CalendarDays,
    CheckCircle2,
    ClipboardList,
    Clock3,
    File,
    FileCheck2,
    FileImage,
    FileText,
    FolderOpen,
    HeartPulse,
    MapPin,
    Phone,
    ShieldAlert,
    ShieldCheck,
    Stethoscope,
    User,
    UserCheck,
    UserRound,
    Users,
} from 'lucide-vue-next';

import {
    Avatar,
    AvatarFallback,
    AvatarImage,
} from '@/components/ui/avatar';

import {
    Badge,
} from '@/components/ui/badge';

import {
    Button,
} from '@/components/ui/button';

import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';

import {
    Separator,
} from '@/components/ui/separator';

import {
    Tabs,
    TabsContent,
    TabsList,
    TabsTrigger,
} from '@/components/ui/tabs';
import { t } from '@/lib/utils';

// -------------------------------------------
// Types
// -------------------------------------------

interface Person {
    id: string;
    name: string | null;
    middle_name: string | null;
    last_name: string | null;
    birth_date: string | null;
    gender: string | null;
    marital_status: string | null;
    nationality: string | null;
    province: string | null;
    city: string | null;
    postal_code: string | null;
    address_one: string | null;
    address_two: string | null;
    phone_one: string | null;
    phone_two: string | null;
    registration_date: string | null;
    verified: boolean;
    verified_date: string | null;
    hospital: string | null;
    specialization: string | null;
    license_number: string | null;
    avatar_url?: string | null;
}

interface CaseReview {
    id: string;
    case_id: string;
    reviewer_doctor_id: string | null;
    review_notes: string | null;
    review_date: string | null;
    review_conclusion: string | null;
    status: string;
    doctor?: Person | null;
    attachments?: CaseAttachment[];
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
    created_at?: string | null;
}

interface PatientCase {
    id: string;
    case_code: string;
    person_id: string;
    priority: string;
    title: string;
    description: string | null;
    case_type: string;
    first_review: string | null;
    final_review: string | null;
    case_date: string | null;
    first_review_date: string | null;
    last_review_date: string | null;
    requester_doctor_id: string | null;
    assigned_doctor_id: string | null;
    last_reviewer_doctor_id: string | null;
    status: string;
    created_at: string;
    updated_at: string;
    patient: Person | null;
    requester_doctor: Person | null;
    assigned_doctor: Person | null;
    last_reviewer_doctor: Person | null;
    reviews: CaseReview[];
    attachments?: CaseAttachment[];
    uploaded_attachments?: CaseAttachment[];
}

const props = defineProps<{
    case: PatientCase;
}>();

// -------------------------------------------
// Helpers
// -------------------------------------------

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

const patient = computed(() => props.case.patient);

function fullName(person?: Person | null): string {
    if (!person) return t('Not assigned');

    const name = [
        person.name,
        person.middle_name,
        person.last_name,
    ]
        .filter(Boolean)
        .join(' ')
        .trim();

    return name || t('Unknown');
}

function initials(person?: Person | null): string {
    if (!person) return '?';

    const parts = [
        person.name,
        person.last_name,
    ].filter(Boolean) as string[];

    return (
        parts.map((name) => name.charAt(0))
            .join('')
            .toUpperCase() || '?'
    );
}

function formatDate(
    value?: string | null,
    options?: Intl.DateTimeFormatOptions,
): string {
    if (!value) return '—';

    // Treat date-only strings as calendar dates,
    // avoiding accidental timezone shifts.
    const date = /^\d{4}-\d{2}-\d{2}$/.test(value)
        ? new Date(`${value}T12:00:00`)
        : new Date(value);

    if (Number.isNaN(date.getTime())) return '—';

    return new Intl.DateTimeFormat('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        ...options,
    }).format(date);
}

function formatLabel(value?: string | null): string {
    if (!value) return '—';

    const label = value
        .replace(/[_-]/g, ' ')
        .replace(/\b\w/g, (char) => char.toUpperCase());

    return t(label);
}

function formatAge(birthDate?: string | null): string {
    if (!birthDate) return '—';

    const date = new Date(`${birthDate.slice(0, 10)}T12:00:00`);

    if (Number.isNaN(date.getTime())) return '—';

    const today = new Date();

    let age = today.getFullYear() - date.getFullYear();

    const monthDifference = today.getMonth() - date.getMonth();

    if (
        monthDifference < 0 ||
        (
            monthDifference === 0 &&
            today.getDate() < date.getDate()
        )
    ) {
        age--;
    }

    return age >= 0 ? `${age} ${t('years')}` : '—';
}

function fileSize(bytes?: number): string {
    if (bytes === undefined || bytes === null) return '';

    if (bytes < 1024) return `${bytes} B`;
    if (bytes < 1024 * 1024) {
        return `${(bytes / 1024).toFixed(1)} KB`;
    }

    return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
}

function fileName(file: CaseAttachment): string {
    return (
        file.name ||
        file.file_name ||
        file.original_name ||
        t('Unnamed document')
    );
}

function fileUrl(file: CaseAttachment): string | null {
    return file.download_url ||
        file.original_url ||
        file.url ||
        null;
}

function fileIcon(file: CaseAttachment) {
    const mime = file.mime_type ?? '';
    const name = fileName(file).toLowerCase();

    if (mime.startsWith('image/') ||
        /\.(png|jpe?g|webp|gif)$/.test(name)) {
        return FileImage;
    }

    if (mime.includes('pdf') || name.endsWith('.pdf')) {
        return FileText;
    }

    return File;
}

// -------------------------------------------
// Display data
// -------------------------------------------

const statusConfig: Record<
    string,
    {
        label: string;
        classes: string;
    }
> = {
    draft: {
        label: t('Draft'),
        classes:
            'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300',
    },
    submitted: {
        label: t('Submitted'),
        classes:
            'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-950 dark:text-blue-300 dark:border-blue-900',
    },
    pending: {
        label: t('Pending'),
        classes:
            'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950 dark:text-amber-300 dark:border-amber-900',
    },
    in_review: {
        label: t('In Review'),
        classes:
            'bg-violet-50 text-violet-700 border-violet-200 dark:bg-violet-950 dark:text-violet-300 dark:border-violet-900',
    },
    completed: {
        label: t('Completed'),
        classes:
            'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950 dark:text-emerald-300 dark:border-emerald-900',
    },
    approved: {
        label: t('Approved'),
        classes:
            'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950 dark:text-emerald-300 dark:border-emerald-900',
    },
    rejected: {
        label: t('Rejected'),
        classes:
            'bg-red-50 text-red-700 border-red-200 dark:bg-red-950 dark:text-red-300 dark:border-red-900',
    },
    cancelled: {
        label: t('Cancelled'),
        classes:
            'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-800 dark:text-slate-300',
    },
};

const priorityConfig: Record<
    string,
    {
        label: string;
        classes: string;
    }
> = {
    low: {
        label: t('Low Priority'),
        classes:
            'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300',
    },
    normal: {
        label: t('Normal Priority'),
        classes:
            'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-950 dark:text-blue-300',
    },
    medium: {
        label: t('Medium Priority'),
        classes:
            'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950 dark:text-amber-300',
    },
    high: {
        label: t('High Priority'),
        classes:
            'bg-orange-50 text-orange-700 border-orange-200 dark:bg-orange-950 dark:text-orange-300',
    },
    urgent: {
        label: t('Urgent'),
        classes:
            'bg-red-50 text-red-700 border-red-200 dark:bg-red-950 dark:text-red-300',
    },
    emergency: {
        label: t('Emergency'),
        classes:
            'bg-red-50 text-red-700 border-red-200 dark:bg-red-950 dark:text-red-300',
    },
};

const currentStatus = computed(() =>
        statusConfig[props.case.status] ?? {
            label: formatLabel(props.case.status),
            classes: 'bg-muted text-muted-foreground',
        },
);

const currentPriority = computed(() =>
        priorityConfig[props.case.priority] ?? {
            label: formatLabel(props.case.priority),
            classes: 'bg-muted text-muted-foreground',
        },
);

const sortedReviews = computed(() =>
    [...(props.case.reviews ?? [])].sort((a, b) =>
        (b.review_date ?? '').localeCompare(a.review_date ?? ''),
    ),
);

const attachments = computed(() => {
    const all = [
        ...(props.case.media ?? []),
    ];

    const seen = new Set<string>();

    return all.filter((file) => {
        const key = file.id != null
            ? `id:${file.id}`
            : [
                fileName(file),
                fileUrl(file) ?? '',
                file.size ?? '',
            ].join(':');

        if (seen.has(key)) return false;

        seen.add(key);

        return true;
    });
});

const completedReviews = computed(() =>
    sortedReviews.value.filter((review) =>
        ['completed', 'approved', 'finalised', 'finalized']
            .includes(review.status),
    ).length,
);

const patientAddress = computed(() =>
    [
        patient.value?.address_one,
        patient.value?.address_two,
        patient.value?.city,
        patient.value?.province,
    ]
        .filter(Boolean)
        .join(', ') || '—',
);

const clinicalTeam = computed(() => [
    {
        role: t('Requesting Doctor'),
        person: props.case.requester_doctor,
        icon: Stethoscope,
    },
    {
        role: t('Assigned Doctor'),
        person: props.case.assigned_doctor,
        icon: UserCheck,
    },
    {
        role: t('Last Reviewing Doctor'),
        person: props.case.last_reviewer_doctor,
        icon: ClipboardList,
    },
]);

</script>

<template>
    <Head :title="`${t('Case')} - ${props.case.case_code}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex min-w-0 flex-1 flex-col gap-5 p-4 md:p-6">

            <!-- ================================= -->
            <!-- PAGE HEADER -->
            <!-- ================================= -->

            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                <div class="min-w-0 space-y-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-xl font-semibold tracking-tight md:text-2xl">
                            {{ t('Case Overview') }}
                        </h1>

                        <Badge
                            variant="outline"
                            :class="currentStatus.classes"
                        >
                            {{ currentStatus.label }}
                        </Badge>
                    </div>

                    <p class="break-all text-xs text-muted-foreground sm:text-sm">
                        {{ props.case.case_code }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <Badge
                        variant="outline"
                        class="gap-1.5 px-3 py-1.5"
                        :class="currentPriority.classes"
                    >
                        <ShieldAlert class="size-3.5" />
                        {{ currentPriority.label }}
                    </Badge>

                    <Badge variant="secondary" class="px-3 py-1.5">
                        {{ formatLabel(props.case.case_type) }}
                    </Badge>
                </div>
            </div>

            <!-- ================================= -->
            <!-- MAIN GRID -->
            <!-- ================================= -->

            <div class="grid min-w-0 grid-cols-1 items-start gap-5 xl:grid-cols-12">

                <!-- ================================= -->
                <!-- PATIENT PROFILE -->
                <!-- ================================= -->

                <Card class="min-w-0 overflow-hidden shadow-none xl:col-span-8">
                    <CardContent class="p-0">
                        <div class="grid grid-cols-1 md:grid-cols-[230px_minmax(0,1fr)]">

                            <!-- Patient identity -->

                            <div
                                class="flex flex-col items-center border-b p-6 text-center md:border-r md:border-b-0"
                            >
                                <Avatar class="size-20 border-4 border-background shadow-none">
                                    <AvatarImage
                                        v-if="patient?.avatar_url"
                                        :src="patient.avatar_url"
                                        :alt="fullName(patient)"
                                    />
                                    <AvatarFallback
                                        class="bg-blue-50 text-xl font-semibold text-blue-700 dark:bg-blue-950 dark:text-blue-300"
                                    >
                                        {{ initials(patient) }}
                                    </AvatarFallback>
                                </Avatar>

                                <h2 class="mt-4 max-w-full break-words text-lg font-semibold">
                                    {{ fullName(patient) }}
                                </h2>

                                <p class="mt-1 text-xs text-muted-foreground">
                                    {{ t('Patient') }}
                                </p>

                                <div class="mt-3">
                                    <Badge
                                        v-if="patient?.verified"
                                        variant="outline"
                                        class="gap-1 border-emerald-200 bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300"
                                    >
                                        <ShieldCheck class="size-3" />
                                        {{ t('Verified') }}
                                    </Badge>

                                    <Badge
                                        v-else
                                        variant="secondary"
                                        class="gap-1"
                                    >
                                        <AlertCircle class="size-3" />
                                        {{ t('Not verified') }}
                                    </Badge>
                                </div>

                                <Separator class="my-5" />

                                <div class="grid w-full grid-cols-2 divide-x">
                                    <div class="px-2 text-center">
                                        <p class="text-sm font-semibold">
                                            {{ formatAge(patient?.birth_date) }}
                                        </p>
                                        <p class="mt-1 text-xs text-muted-foreground">
                                            {{ t('Age') }}
                                        </p>
                                    </div>

                                    <div class="px-2 text-center">
                                        <p class="text-sm font-semibold">
                                            {{ formatLabel(patient?.gender) }}
                                        </p>
                                        <p class="mt-1 text-xs text-muted-foreground">
                                            {{ t('Gender') }}
                                        </p>
                                    </div>
                                </div>

                                <div
                                    v-if="patient?.phone_one"
                                    class="mt-5 w-full"
                                >
                                    <Button
                                        variant="outline"
                                        class="w-full gap-2"
                                        as-child
                                    >
                                        <a :href="`tel:${patient.phone_one}`">
                                            <Phone class="size-4" />
                                            {{ t('Call Patient') }}
                                        </a>
                                    </Button>
                                </div>
                            </div>

                            <!-- Patient details -->

                            <div class="min-w-0 p-5 md:p-6">
                                <div class="mb-5 flex items-center gap-2">
                                    <UserRound class="size-4 text-primary" />
                                    <h3 class="text-sm font-semibold">
                                        {{ t('Patient Information') }}
                                    </h3>
                                </div>

                                <div class="grid grid-cols-2 gap-x-5 gap-y-6 lg:grid-cols-3">

                                    <div class="min-w-0 space-y-1.5">
                                        <p class="text-xs text-muted-foreground">
                                            {{ t('Date of Birth') }}
                                        </p>
                                        <p class="text-sm font-medium">
                                            {{ formatDate(patient?.birth_date) }}
                                        </p>
                                    </div>

                                    <div class="min-w-0 space-y-1.5">
                                        <p class="text-xs text-muted-foreground">
                                            {{ t('Gender') }}
                                        </p>
                                        <p class="text-sm font-medium">
                                            {{ formatLabel(patient?.gender) }}
                                        </p>
                                    </div>

                                    <div class="min-w-0 space-y-1.5">
                                        <p class="text-xs text-muted-foreground">
                                            {{ t('Marital Status') }}
                                        </p>
                                        <p class="text-sm font-medium">
                                            {{ formatLabel(patient?.marital_status) }}
                                        </p>
                                    </div>

                                    <div class="min-w-0 space-y-1.5">
                                        <p class="text-xs text-muted-foreground">
                                            {{ t('Phone Number') }}
                                        </p>
                                        <p class="break-words text-sm font-medium">
                                            {{ patient?.phone_one || '—' }}
                                        </p>
                                    </div>

                                    <div class="min-w-0 space-y-1.5">
                                        <p class="text-xs text-muted-foreground">
                                            {{ t('Alternative Phone') }}
                                        </p>
                                        <p class="break-words text-sm font-medium">
                                            {{ patient?.phone_two || '—' }}
                                        </p>
                                    </div>

                                    <div class="min-w-0 space-y-1.5">
                                        <p class="text-xs text-muted-foreground">
                                            {{ t('Nationality') }}
                                        </p>
                                        <p class="break-words text-sm font-medium">
                                            {{ patient?.nationality || '—' }}
                                        </p>
                                    </div>

                                    <div class="min-w-0 space-y-1.5">
                                        <p class="text-xs text-muted-foreground">
                                            {{ t('Province') }}
                                        </p>
                                        <p class="break-words text-sm font-medium">
                                            {{ patient?.province || '—' }}
                                        </p>
                                    </div>

                                    <div class="min-w-0 space-y-1.5">
                                        <p class="text-xs text-muted-foreground">
                                            {{ t('City') }}
                                        </p>
                                        <p class="break-words text-sm font-medium">
                                            {{ patient?.city || '—' }}
                                        </p>
                                    </div>

                                    <div class="min-w-0 space-y-1.5">
                                        <p class="text-xs text-muted-foreground">
                                            {{ t('Postal Code') }}
                                        </p>
                                        <p class="break-words text-sm font-medium">
                                            {{ patient?.postal_code || '—' }}
                                        </p>
                                    </div>

                                </div>

                                <Separator class="my-5" />

                                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                    <div class="min-w-0 space-y-1.5">
                                        <p class="flex items-center gap-1.5 text-xs text-muted-foreground">
                                            <MapPin class="size-3.5" />
                                            {{ t('Address') }}
                                        </p>
                                        <p class="text-sm font-medium">
                                            {{ patientAddress }}
                                        </p>
                                    </div>

                                    <div class="min-w-0 space-y-1.5">
                                        <p class="flex items-center gap-1.5 text-xs text-muted-foreground">
                                            <CalendarDays class="size-3.5" />
                                            {{ t('Registration Date') }}
                                        </p>
                                        <p class="text-sm font-medium">
                                            {{ formatDate(patient?.registration_date) }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </CardContent>
                </Card>

                <!-- ================================= -->
                <!-- CASE SUMMARY -->
                <!-- ================================= -->

                <Card class="min-w-0 shadow-none xl:col-span-4">
                    <CardHeader class="pb-3">
                        <div class="flex items-center justify-between gap-3">
                            <CardTitle class="flex items-center gap-2 text-sm font-semibold">
                                <HeartPulse class="size-4 text-primary" />
                                {{ t('Case Information') }}
                            </CardTitle>
                            <Badge
                                variant="outline"
                                :class="currentPriority.classes"
                            >
                                {{ currentPriority.label }}
                            </Badge>
                        </div>
                    </CardHeader>

                    <CardContent class="space-y-5">

                        <div class="rounded-lg bg-muted/60 p-4">
                            <p class="mb-1.5 text-xs font-medium text-muted-foreground">
                                {{ t('Case Title') }}
                            </p>

                            <h3 class="text-base font-semibold">
                                {{ props.case.title }}
                            </h3>

                            <p class="mt-3 text-xs font-medium text-muted-foreground">
                                {{ t('Description') }}
                            </p>

                            <p class="mt-1 whitespace-pre-wrap break-words text-sm leading-6 text-foreground/80">
                                {{ props.case.description || t('No description provided.') }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <p class="text-xs text-muted-foreground">
                                    {{ t('Case Date') }}
                                </p>
                                <p class="flex items-center gap-1.5 text-sm font-medium">
                                    <CalendarDays class="size-3.5 text-muted-foreground" />
                                    {{ formatDate(props.case.case_date) }}
                                </p>
                            </div>

                            <div class="space-y-1.5">
                                <p class="text-xs text-muted-foreground">
                                    {{ t('Case Type') }}
                                </p>
                                <p class="text-sm font-medium">
                                    {{ formatLabel(props.case.case_type) }}
                                </p>
                            </div>

                            <div class="space-y-1.5">
                                <p class="text-xs text-muted-foreground">
                                    {{ t('First Review') }}
                                </p>
                                <p class="text-sm font-medium">
                                    {{ formatDate(props.case.first_review_date) }}
                                </p>
                            </div>

                            <div class="space-y-1.5">
                                <p class="text-xs text-muted-foreground">
                                    {{ t('Last Review') }}
                                </p>
                                <p class="text-sm font-medium">
                                    {{ formatDate(props.case.last_review_date) }}
                                </p>
                            </div>
                        </div>

                        <Separator />

                        <div class="space-y-3">
                            <p class="text-xs font-medium text-muted-foreground">
                                {{ t('Assigned Doctor') }}
                            </p>

                            <div class="flex items-center gap-3">
                                <Avatar class="size-9">
                                    <AvatarFallback class="bg-muted text-xs">
                                        {{ initials(props.case.assigned_doctor) }}
                                    </AvatarFallback>
                                </Avatar>

                                <div class="min-w-0">
                                    <p class="truncate text-sm font-medium">
                                        {{ fullName(props.case.assigned_doctor) }}
                                    </p>

                                    <p class="text-xs text-muted-foreground">
                                        {{ props.case.assigned_doctor?.specialization || t('Medical Reviewer') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                    </CardContent>
                </Card>

                <!-- ================================= -->
                <!-- REVIEWS & CASE DETAILS -->
                <!-- ================================= -->

                <Card class="min-w-0 shadow-none xl:col-span-8">
                    <CardContent class="p-4 md:p-5">

                        <Tabs default-value="reviews" class="w-full">

                            <div class="overflow-x-auto pb-1">
                                <TabsList class="inline-flex w-max justify-start">
                                    <TabsTrigger value="reviews" class="gap-2 px-4">
                                        <ClipboardList class="size-4" />
                                        {{ t('Medical Reviews') }}
                                        <span class="text-xs opacity-60">
                                            ({{ sortedReviews.length }})
                                        </span>
                                    </TabsTrigger>

                                    <TabsTrigger value="details" class="gap-2 px-4">
                                        <Activity class="size-4" />
                                        {{ t('Case Details') }}
                                    </TabsTrigger>

                                    <TabsTrigger value="doctors" class="gap-2 px-4">
                                        <Users class="size-4" />
                                        {{ t('Clinical Team') }}
                                    </TabsTrigger>
                                </TabsList>
                            </div>

                            <!-- ============================= -->
                            <!-- REVIEWS TAB -->
                            <!-- ============================= -->

                            <TabsContent value="reviews" class="mt-5">
                                <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                                    <div>
                                        <h3 class="text-sm font-semibold">
                                            {{ t('Medical Review History') }}
                                        </h3>
                                        <p class="mt-1 text-xs text-muted-foreground">
                                            {{ t('Chronological history of clinical assessments.') }}
                                        </p>
                                    </div>

                                    <Badge variant="secondary" class="gap-1.5">
                                        <CheckCircle2 class="size-3.5" />
                                        {{ completedReviews }} Completed
                                    </Badge>
                                </div>

                                <!-- Review timeline -->

                                <div
                                    v-if="sortedReviews.length"
                                    class="relative ml-3 space-y-5 border-l-2 border-border pb-2 pl-6"
                                >
                                    <div
                                        v-for="(review, index) in sortedReviews"
                                        :key="review.id"
                                        class="relative"
                                    >
                                        <!-- Timeline marker -->

                                        <span
                                            class="absolute -left-[34px] top-5 flex size-4 items-center justify-center rounded-full border-2 border-primary bg-background ring-4 ring-background"
                                        >
                                            <span
                                                v-if="index === 0"
                                                class="size-1.5 rounded-full bg-primary"
                                            />
                                        </span>

                                        <div class="rounded-lg border bg-card transition-colors hover:bg-muted/20">

                                            <div
                                                class="flex flex-wrap items-center justify-between gap-3 border-b bg-muted/30 px-4 py-3"
                                            >
                                                <div class="flex items-center gap-2">
                                                    <CalendarDays class="size-4 text-muted-foreground" />

                                                    <span class="text-sm font-semibold">
                                                        {{ formatDate(review.review_date) }}
                                                    </span>
                                                </div>

                                                <Badge variant="outline">
                                                    {{ formatLabel(review.status) }}
                                                </Badge>
                                            </div>

                                            <div class="space-y-4 p-4">

                                                <div class="flex items-center gap-3">
                                                    <Avatar class="size-9">
                                                        <AvatarFallback class="text-xs">
                                                            {{ initials(review.doctor) }}
                                                        </AvatarFallback>
                                                    </Avatar>

                                                    <div class="min-w-0">
                                                        <p class="truncate text-sm font-medium">
                                                            {{ fullName(review.doctor) }}
                                                        </p>
                                                        <p class="text-xs text-muted-foreground">
                                                            {{ t('Reviewing Doctor') }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div v-if="review.review_notes">
                                                    <p class="mb-1.5 text-xs font-medium text-muted-foreground">
                                                        {{ t('Review Notes') }}
                                                    </p>

                                                    <p class="whitespace-pre-wrap break-words text-sm leading-6">
                                                        {{ review.review_notes }}
                                                    </p>
                                                </div>

                                                <div
                                                    v-if="review.review_conclusion"
                                                    class="rounded-lg border border-blue-100 bg-blue-50/60 p-3 dark:border-blue-900 dark:bg-blue-950/30"
                                                >
                                                    <p class="mb-1.5 flex items-center gap-2 text-xs font-semibold text-blue-700 dark:text-blue-300">
                                                        <FileCheck2 class="size-3.5" />
                                                        {{ t('Medical Conclusion') }}
                                                    </p>

                                                    <p class="whitespace-pre-wrap break-words text-sm leading-6">
                                                        {{ review.review_conclusion }}
                                                    </p>
                                                </div>

                                                <div
                                                    v-if="!review.review_notes && !review.review_conclusion"
                                                    class="text-sm text-muted-foreground"
                                                >
                                                    {{ t('No review notes or conclusion recorded.') }}
                                                </div>

                                                <!-- Review attachments -->

                                                <div
                                                    v-if="review.attachments?.length"
                                                    class="space-y-2"
                                                >
                                                    <Separator />

                                                    <p class="text-xs font-medium text-muted-foreground">
                                                        {{ t('Review Attachments') }}
                                                    </p>

                                                    <div
                                                        v-for="(file, fileIndex) in review.attachments"
                                                        :key="file.id ?? fileIndex"
                                                        class="flex items-center justify-between gap-3 rounded-md border p-2.5"
                                                    >
                                                        <div class="flex min-w-0 items-center gap-2">
                                                            <component
                                                                :is="fileIcon(file)"
                                                                class="size-4 shrink-0 text-muted-foreground"
                                                            />
                                                            <span class="truncate text-xs">
                                                                {{ fileName(file) }}
                                                            </span>
                                                        </div>

                                                        <Button
                                                            v-if="fileUrl(file)"
                                                            variant="ghost"
                                                            size="icon"
                                                            class="size-7 shrink-0"
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

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Empty reviews -->

                                <div
                                    v-else
                                    class="flex min-h-64 flex-col items-center justify-center rounded-lg border border-dashed bg-muted/20 px-6 py-10 text-center"
                                >
                                    <div class="mb-4 flex size-14 items-center justify-center rounded-full bg-muted">
                                        <ClipboardList class="size-6 text-muted-foreground" />
                                    </div>

                                    <h3 class="text-sm font-semibold">
                                        {{ t('No medical reviews yet') }}
                                    </h3>

                                    <p class="mt-2 max-w-sm text-sm leading-6 text-muted-foreground">
                                        {{ t('This case has not received any medical reviews.') }}
                                        {{ t('Clinical assessments will appear here when recorded.') }}
                                    </p>

                                    <Badge variant="outline" class="mt-4">
                                        {{ currentStatus.label }}
                                    </Badge>
                                </div>

                            </TabsContent>

                            <!-- ============================= -->
                            <!-- CASE DETAILS TAB -->
                            <!-- ============================= -->

                            <TabsContent value="details" class="mt-5">
                                <div class="space-y-6">

                                    <div>
                                        <h3 class="text-sm font-semibold">
                                            {{ t('Case Details') }}
                                        </h3>
                                        <p class="mt-1 text-xs text-muted-foreground">
                                            {{ t('General clinical and administrative information.') }}
                                        </p>
                                    </div>

                                    <div class="grid grid-cols-1 gap-x-8 gap-y-6 rounded-lg border p-5 sm:grid-cols-2">

                                        <div class="space-y-1.5">
                                            <p class="text-xs text-muted-foreground">
                                                {{ t('Case Reference') }}
                                            </p>
                                            <p class="break-all text-sm font-medium">
                                                {{ props.case.case_code }}
                                            </p>
                                        </div>

                                        <div class="space-y-1.5">
                                            <p class="text-xs text-muted-foreground">
                                                {{ t('Case Type') }}
                                            </p>
                                            <p class="text-sm font-medium">
                                                {{ formatLabel(props.case.case_type) }}
                                            </p>
                                        </div>

                                        <div class="space-y-1.5">
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

                                        <div class="space-y-1.5">
                                            <p class="text-xs text-muted-foreground">
                                                {{ t('Priority') }}
                                            </p>
                                            <Badge
                                                variant="outline"
                                                :class="currentPriority.classes"
                                            >
                                                {{ currentPriority.label }}
                                            </Badge>
                                        </div>

                                        <div class="space-y-1.5">
                                            <p class="text-xs text-muted-foreground">
                                                {{ t('Case Date') }}
                                            </p>
                                            <p class="text-sm font-medium">
                                                {{ formatDate(props.case.case_date) }}
                                            </p>
                                        </div>

                                        <div class="space-y-1.5">
                                            <p class="text-xs text-muted-foreground">
                                                {{ t('Created At') }}
                                            </p>
                                            <p class="text-sm font-medium">
                                                {{ formatDate(props.case.created_at) }}
                                            </p>
                                        </div>

                                        <div class="space-y-1.5">
                                            <p class="text-xs text-muted-foreground">
                                                {{ t('First Review Date') }}
                                            </p>
                                            <p class="text-sm font-medium">
                                                {{ formatDate(props.case.first_review_date) }}
                                            </p>
                                        </div>

                                        <div class="space-y-1.5">
                                            <p class="text-xs text-muted-foreground">
                                                {{ t('Last Review Date') }}
                                            </p>
                                            <p class="text-sm font-medium">
                                                {{ formatDate(props.case.last_review_date) }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="space-y-3">
                                        <h4 class="text-sm font-semibold">
                                            {{ t('First Review') }}
                                        </h4>

                                        <div class="min-h-20 rounded-lg bg-muted/40 p-4">
                                            <p class="whitespace-pre-wrap break-words text-sm leading-6">
                                                {{ props.case.first_review || t('No first review recorded.') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="space-y-3">
                                        <h4 class="text-sm font-semibold">
                                            {{ t('Final Review') }}
                                        </h4>

                                        <div class="min-h-20 rounded-lg bg-muted/40 p-4">
                                            <p class="whitespace-pre-wrap break-words text-sm leading-6">
                                                {{ props.case.final_review || t('No final review recorded.') }}
                                            </p>
                                        </div>
                                    </div>

                                </div>
                            </TabsContent>

                            <!-- ============================= -->
                            <!-- CLINICAL TEAM TAB -->
                            <!-- ============================= -->

                            <TabsContent value="doctors" class="mt-5">
                                <div class="space-y-4">
                                    <div>
                                        <h3 class="text-sm font-semibold">
                                            {{ t('Clinical Team') }}
                                        </h3>
                                        <p class="mt-1 text-xs text-muted-foreground">
                                            {{ t('Medical professionals associated with this case.') }}
                                        </p>
                                    </div>

                                    <div
                                        v-for="member in clinicalTeam"
                                        :key="member.role"
                                        class="flex items-center gap-4 rounded-lg border p-4"
                                    >
                                        <Avatar class="size-11">
                                            <AvatarFallback class="bg-muted text-sm">
                                                {{ initials(member.person) }}
                                            </AvatarFallback>
                                        </Avatar>

                                        <div class="min-w-0 flex-1">
                                            <div class="mb-1 flex items-center gap-1.5 text-xs text-muted-foreground">
                                                <component :is="member.icon" class="size-3.5" />
                                                {{ member.role }}
                                            </div>

                                            <p class="truncate text-sm font-semibold">
                                                {{ fullName(member.person) }}
                                            </p>

                                            <p
                                                v-if="member.person?.specialization"
                                                class="mt-1 text-xs text-muted-foreground"
                                            >
                                                {{ member.person.specialization }}
                                            </p>
                                        </div>

                                        <Badge
                                            v-if="member.person"
                                            variant="outline"
                                            class="shrink-0"
                                        >
                                            {{ t('Assigned') }}
                                        </Badge>

                                        <Badge
                                            v-else
                                            variant="secondary"
                                            class="shrink-0"
                                        >
                                            {{ t('Unassigned') }}
                                        </Badge>
                                    </div>
                                </div>
                            </TabsContent>

                        </Tabs>

                    </CardContent>
                </Card>

                <!-- ================================= -->
                <!-- DOCUMENTS -->
                <!-- ================================= -->

                <Card class="min-w-0 shadow-none xl:col-span-4">
                    <CardHeader class="pb-3">
                        <div class="flex items-center justify-between gap-3">
                            <CardTitle class="flex items-center gap-2 text-sm font-semibold">
                                <FolderOpen class="size-4 text-primary" />
                                {{ t('Files / Documents') }}
                            </CardTitle>

                            <Badge variant="secondary">
                                {{ attachments.length }}
                            </Badge>
                        </div>

                        <p class="text-xs text-muted-foreground">
                            {{ t('Supporting medical documents and case files.') }}
                        </p>
                    </CardHeader>

                    <CardContent>
                        <div v-if="attachments.length" class="divide-y rounded-lg border">
                            <div
                                v-for="(file, index) in attachments"
                                :key="file.id ?? index"
                                class="flex items-center gap-3 p-3 transition-colors hover:bg-muted/40"
                            >
                                <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-muted">
                                    <component
                                        :is="fileIcon(file)"
                                        class="size-5 text-muted-foreground"
                                    />
                                </div>

                                <div class="min-w-0 flex-1">
                                    <p
                                        class="truncate text-sm font-medium"
                                        :title="fileName(file)"
                                    >
                                        {{ fileName(file) }}
                                    </p>

                                    <p class="mt-1 text-xs text-muted-foreground">
                                        {{ fileSize(file.size) || t('Medical document') }}
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

                        <!-- Empty documents -->

                        <div
                            v-else
                            class="flex min-h-56 flex-col items-center justify-center rounded-lg border border-dashed bg-muted/20 p-6 text-center"
                        >
                            <div class="mb-4 flex size-12 items-center justify-center rounded-full bg-muted">
                                <FolderOpen class="size-5 text-muted-foreground" />
                            </div>

                            <h4 class="text-sm font-semibold">
                                {{ t('No documents available') }}
                            </h4>

                            <p class="mt-2 max-w-xs text-xs leading-5 text-muted-foreground">
                                {{ t('No supporting files have been attached to this case.') }}
                            </p>
                        </div>

                        <Separator class="my-5" />

                        <!-- Case activity summary -->

                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold">
                                {{ t('Case Activity') }}
                            </h4>

                            <div class="space-y-4">

                                <div class="flex items-start gap-3">
                                    <div class="flex size-8 shrink-0 items-center justify-center rounded-full bg-blue-50 dark:bg-blue-950">
                                        <ClipboardList class="size-4 text-blue-600 dark:text-blue-300" />
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium">
                                            {{ t('Case Created') }}
                                        </p>

                                        <p class="mt-1 text-xs text-muted-foreground">
                                            {{ formatDate(props.case.created_at) }}
                                        </p>
                                    </div>
                                </div>

                                <div
                                    v-if="props.case.first_review_date"
                                    class="flex items-start gap-3"
                                >
                                    <div class="flex size-8 shrink-0 items-center justify-center rounded-full bg-amber-50 dark:bg-amber-950">
                                        <Stethoscope class="size-4 text-amber-600 dark:text-amber-300" />
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium">
                                            {{ t('First Review') }}
                                        </p>

                                        <p class="mt-1 text-xs text-muted-foreground">
                                            {{ formatDate(props.case.first_review_date) }}
                                        </p>
                                    </div>
                                </div>

                                <div
                                    v-if="props.case.last_review_date"
                                    class="flex items-start gap-3"
                                >
                                    <div class="flex size-8 shrink-0 items-center justify-center rounded-full bg-emerald-50 dark:bg-emerald-950">
                                        <CheckCircle2 class="size-4 text-emerald-600 dark:text-emerald-300" />
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium">
                                            {{ t('Latest Review') }}
                                        </p>

                                        <p class="mt-1 text-xs text-muted-foreground">
                                            {{ formatDate(props.case.last_review_date) }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="flex size-8 shrink-0 items-center justify-center rounded-full bg-muted">
                                        <Clock3 class="size-4 text-muted-foreground" />
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium">
                                            {{ t('Last Updated') }}
                                        </p>

                                        <p class="mt-1 text-xs text-muted-foreground">
                                            {{ formatDate(props.case.updated_at) }}
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </CardContent>
                </Card>

            </div>

        </div>
    </AppLayout>
</template>

