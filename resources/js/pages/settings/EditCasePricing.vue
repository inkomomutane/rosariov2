<script lang="ts" setup>
import InputError from '@/components/InputError.vue';
import NumberInput from '@/components/NumberInput.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogFooter, DialogHeader, DialogScrollContent, DialogTitle } from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import { t } from '@/lib/utils';
import { useForm } from '@inertiajs/vue3';
import { CasePricingDto } from '@/types/generated';
import { watch } from 'vue';
import VSyncSelect from '@/components/VSyncSelect.vue';

const props = defineProps({
    close: {
        type: Function,
        required: true,
    },
    openModal: {
        type: Boolean,
        required: true,
    },
    case_types: {
        type: Array as () => Array<{ key: string; value: string }>,
        required: true,
    },
    // The existing pricing data passed from the parent crudManager
    pricing: {
        type: Object as () => CasePricingDto | null,
        default: null,
    }
});

const form = useForm({
    case_type: '',
    price: '',
    review_phase: 1,
});

// Sync the form when the pricing prop changes or modal opens
watch(() => props.openModal, (isOpen) => {
    if (isOpen && props.pricing) {
        form.case_type = props.pricing.case_type;
        form.price = props.pricing.price;
        form.review_phase = props.pricing.review_phase;
    }
}, { immediate: true });

const updatePricing = () => {
    if (!props.pricing?.id) return;

    form.put(route('case-pricing-update', { pricing: props.pricing.id }), {
        preserveScroll: true,
        onSuccess: () => props.close(),
    });
};
</script>

<template>
    <Dialog :open="openModal" @update:open="close">
        <DialogScrollContent class="max-w-2xl">
            <DialogHeader>
                <DialogTitle>
                    {{ t('Edit Case Pricing') }}
                </DialogTitle>
            </DialogHeader>

            <form @submit.prevent="updatePricing" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <!-- Case Type -->
                    <div class="col-span-2 sm:col-span-1">
                        <Label for="case_type">{{ t('Case Type') }}</Label>
                        <VSyncSelect
                            id="case_type"
                            v-model="form.case_type"
                            :options="props.case_types"
                            :reduce="(option) => option.key"
                            :get-label="(option) => t(option.value.toString())"
                            :placeholder="t('Select case type')"
                        />
                        <InputError :message="form.errors.case_type" />
                    </div>

                    <!-- Review Phase -->
                    <div class="col-span-2 sm:col-span-1">
                        <Label for="review_phase">{{ t('Review Phase') }}</Label>
                        <NumberInput
                            id="review_phase"
                            v-model="form.review_phase"
                            :min="1"
                            :step="1"
                            name="review_phase"
                        />
                        <InputError :message="form.errors.review_phase" />
                    </div>

                    <!-- Price -->
                    <div class="col-span-2">
                        <Label for="price">{{ t('Price') }}</Label>
                        <NumberInput
                            id="price"
                            v-model="form.price"
                            :placeholder="t('Price')"
                            name="price"
                        />
                        <InputError :message="form.errors.price" />
                    </div>
                </div>
            </form>

            <DialogFooter>
                <Button variant="ghost" @click="close">
                    {{ t('Cancel') }}
                </Button>
                <Button :disabled="form.processing" @click="updatePricing">
                    {{ t('Update Changes') }}
                </Button>
            </DialogFooter>
        </DialogScrollContent>
    </Dialog>
</template>
