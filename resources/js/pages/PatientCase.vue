
<script setup lang="ts">
import { computed, type PropType, ref } from 'vue';

import { Badge } from '@/components/ui/badge';
import { Card, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';

import {
    LucideVerified,
    Bookmark,
    FileText,
    EyeIcon,
    HandCoins,
    RefreshCw,
} from 'lucide-vue-next';

import type { FullPatientCaseDto } from '@/types/generated';
import { crudManager, t } from '@/lib/utils';
import { useForm } from '@inertiajs/vue3';
import PayCaseInvoice from '@/pages/PayCaseInvoice.vue';

const props = defineProps({
    medicalCase: {
        type: Object as PropType<FullPatientCaseDto>,
        required: true,
    },
});

const formattedDate = computed(() => {
    if (!props.medicalCase.case_date) {
        return t('No date set');
    }

    return new Date(props.medicalCase.case_date).toLocaleDateString(
        'en-GB',
        {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
        },
    );
});

const statusLabels: Record<string, string> = {
    draft: t('Draft'),
    submitted: t('Submitted'),
    pending: t('Pending'),
    in_review: t('In Review'),
    reviewed: t('Reviewed'),
    second_review_submitted: t('Second Review Submitted'),
    completed: t('Completed'),
    approved: t('Approved'),
    rejected: t('Rejected'),
    cancelled: t('Cancelled'),
};

const priorityLabels: Record<string, string> = {
    low: t('Low'),
    normal: t('Normal'),
    medium: t('Medium'),
    high: t('High'),
    urgent: t('Urgent'),
    emergency: t('Emergency'),
    routine: t('Routine'),
};

const caseTypeLabels: Record<string, string> = {
    tac: t('CT Scan'),
    ct: t('CT Scan'),
    mri: t('MRI'),
    xray: t('X-Ray'),
    ultrasound: t('Ultrasound'),
    radiography: t('Radiography'),
    consultation: t('Consultation'),
    laboratory: t('Laboratory'),
    echography: t('Echography'),
    ecography: t('Echography'),
};

const formatLabel = (value?: string | null): string => {
    if (!value) return '—';

    const label = value
        .replace(/[_-]/g, ' ')
        .replace(/\b\w/g, (char) => char.toUpperCase());

    return t(label);
};

const formattedStatus = computed(() => {
    const status = props.medicalCase.status?.toLowerCase();

    if (!status) return t('Pending');

    return statusLabels[status] ?? formatLabel(status);
});

const formattedPriority = computed(() => {
    const priority = props.medicalCase.priority?.toLowerCase();

    if (!priority) return t('Routine');

    return priorityLabels[priority] ?? formatLabel(priority);
});

const formattedCaseType = computed(() => {
    const type = props.medicalCase.case_type?.toLowerCase();

    if (!type) return '—';

    return caseTypeLabels[type] ?? formatLabel(type);
});

const canPay = computed(() =>
    [
        'submitted',
        'second_review_submitted',
    ].includes(props.medicalCase.status ?? ''),
);

const canRequestSecondReview = computed(
    () => props.medicalCase.status === 'reviewed',
);


const payManager = ref(crudManager());
const openPayModal = (medicalCase: FullPatientCaseDto) => {
    payManager.value.open(medicalCase);
};

</script>

<template>
    <Card
        class="mt-2 rounded border border-zinc-200 py-4 shadow-none dark:border-zinc-800"
    >
        <CardContent class="flex flex-col gap-3 px-4">
            <div class="flex gap-3">
                <div
                    class="bg-secondary text-primary/50 flex size-20 shrink-0 items-center justify-center rounded"
                >
                    <FileText class="size-10" />
                </div>

                <div class="flex flex-1 flex-col gap-2">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-1">
                            <p class="text-base font-medium">
                                {{ medicalCase.patient_name }}
                                {{ medicalCase.patient_last_name }}
                            </p>

                            <LucideVerified
                                v-if="medicalCase.patient_verified"
                                class="size-4 shrink-0"
                            />
                        </div>

                        <Button size="sm" variant="secondary">
                            <Bookmark class="size-4" />
                        </Button>
                    </div>

                    <div class="text-muted-foreground text-sm">
                        <span class="font-semibold">
                            {{ formattedDate }}
                        </span>
                    </div>
                </div>
            </div>

            <p
                class="text-muted-foreground text-sm/relaxed first-letter:uppercase"
            >
                {{ medicalCase.title }}
            </p>

            <div class="flex flex-wrap gap-2 text-xs">
                <Badge variant="outline">
                    {{ formattedPriority }}
                </Badge>

                <Badge variant="outline">
                    {{ formattedCaseType }}
                </Badge>

                <Badge variant="outline">
                    {{ formattedStatus }}
                </Badge>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-2">
                <div class="flex flex-wrap gap-2">
                    <!-- Always visible -->
                    <Button
                        variant="outline"
                        as="a"
                        :href="route('case-view', {
                            case: medicalCase.id,
                        })"
                    >
                        <span class="text-sm font-medium">
                            {{ t('View') }}
                        </span>
                        <EyeIcon class="size-4" />
                    </Button>

                    <!-- Submitted or second review submitted -->
                    <Button
                        v-if="canPay"
                        @click="openPayModal(medicalCase)"
                        variant="outline"
                    >
                        <span class="text-sm font-medium">
                            {{ t('Pay') }}
                        </span>
                        <HandCoins class="size-4" />
                    </Button>

                    <!-- Reviewed -->
                    <Button
                        v-if="canRequestSecondReview"
                        variant="outline"
                    >
                        <span class="text-sm font-medium">
                            {{ t('Ask Second Review') }}
                        </span>
                        <RefreshCw class="size-4" />
                    </Button>
                </div>
            </div>
        </CardContent>
    </Card>



    <PayCaseInvoice :v-if="payManager.isModalOpen"  :open-modal="payManager.isModalOpen" :close="payManager.close" :case="payManager.model" />
</template>
