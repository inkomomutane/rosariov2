
<script setup lang="ts">
import { computed, type PropType } from 'vue';

import { Badge } from '@/components/ui/badge';
import { Card, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';

import {
    LucideArrowRight,
    LucideVerified,
    Bookmark,
    FileText,
    EyeIcon,
    HandCoins,
    RefreshCw,
} from 'lucide-vue-next';

import type { FullPatientCaseDto } from '@/types/generated';
import { t } from '@/lib/utils';

const props = defineProps({
    medicalCase: {
        type: Object as PropType<FullPatientCaseDto>,
        required: true,
    },
});

const formattedDate = computed(() => {
    if (!props.medicalCase.case_date) {
        return 'No date set';
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

const canPay = computed(() =>
    [
        'submitted',
        'second_review_submitted',
    ].includes(props.medicalCase.status ?? ''),
);

const canRequestSecondReview = computed(
    () => props.medicalCase.status === 'reviewed',
);
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
                            <span class="text-sm font-medium">
                                Save
                            </span>
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
                    {{ medicalCase.priority || 'Routine' }}
                </Badge>

                <Badge variant="outline">
                    {{ medicalCase.case_type }}
                </Badge>

                <Badge variant="outline">
                    {{
                        medicalCase.status
                            ?.toLowerCase()
                            .replaceAll('_', ' ') || 'Pending'
                    }}
                </Badge>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-2">
                <div class="flex flex-wrap gap-2">
                    <!-- Always visible -->
                    <Button variant="outline"
                             as="a"
                             :href="route('case-view',{
                                  case : medicalCase.id
                             })"
                    >
                        <span class="text-sm font-medium">
                            {{ t('View') }}
                        </span>
                        <EyeIcon class="size-4" />
                    </Button>

                    <!-- Submitted or second review submitted -->
                    <Button
                        variant="outline"
                    >
                        <span class="text-sm font-medium">
                            {{ t('Apply to review') }}
                        </span>
                        <HandCoins class="size-4" />
                    </Button>
                </div>
            </div>
        </CardContent>
    </Card>
</template>
