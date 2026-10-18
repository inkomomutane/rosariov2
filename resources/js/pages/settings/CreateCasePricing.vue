<script lang="ts" setup>
import InputError from '@/components/InputError.vue';
import NumberInput from '@/components/NumberInput.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogFooter, DialogHeader, DialogScrollContent, DialogTitle } from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import { t } from '@/lib/utils';
import { useForm } from '@inertiajs/vue3';
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
    }
});

const form = useForm({
    case_type: '',
    price: 0,
    review_phase: 1,
});

const submit = () => {
    form.post(route('case-pricing-store'), {
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
                    {{ t('New Case Pricing') }}
                </DialogTitle>
            </DialogHeader>

            <form @submit.prevent="submit" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <!-- Case Type Selection -->
                    <div class="col-span-2 sm:col-span-1">
                        <Label for="case_type">{{ t('Case Type') }}</Label>
                        <VSyncSelect
                            id="case_type"
                            ref="case_type"
                            v-model="form.case_type"
                            :options="props.case_types"
                            :reduce="(option) => option.key"
                            :get-label="(option) => t(option.value.toString())"
                            :placeholder="t('Select case type')"
                            name="case_type"
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
                            :placeholder="t('Phase number')"
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
                <Button variant="outline" @click="close">
                    {{ t('Cancel') }}
                </Button>
                <Button :disabled="form.processing" @click="submit">
                    {{ t('Save Pricing') }}
                </Button>
            </DialogFooter>
        </DialogScrollContent>
    </Dialog>
</template>
