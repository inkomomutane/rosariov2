<script setup lang="ts">
import { NumberField, NumberFieldContent, NumberFieldDecrement, NumberFieldIncrement, NumberFieldInput } from '@/components/ui/number-field';
import { ulid } from 'ulidx';
import { ref,watch } from 'vue';
const props = defineProps({
    min: {
        type: Number,
        default: -999999999,
    },
    max: {
        type: Number,
        default: 999999999,
    },
    step: {
        type: Number,
        default: null,
    },
    placeholder: {
        type: String,
        default: '',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    defaultValue: {
        type: String,
        default: '0',
    },
    id: {
        type: String,
        default: () => `number-field-${ulid()}`,
    },
    hasIncrementer: {
        type: Boolean,
        default: true,
    },

    hasDecrementer: {
        type: Boolean,
        default: true,
    },
    formatOptions: {
        type: Object,
        default: null
    },
});
import { usePage } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const model = defineModel({
    default: '0',
});

const handleBlur = (event: FocusEvent) => {
    if (event) {
        model.value = `${event}`;
    } else {
        model.value = props.defaultValue;
    }
};
const page = usePage();




const formatOptions = ref(props.formatOptions || {
    style: 'decimal',
    currency: 'EUR',
    minimumFractionDigits:  page.props.setup && page.props.setup.decimal_places ? page.props.setup.decimal_places : 2,
    maximumFractionDigits:  page.props.setup && page.props.setup.decimal_places ? page.props.setup.decimal_places : 2,
});
const getStepValue = () => {
    if (props.step) {
        return props.step;
    }
    if (formatOptions.value && formatOptions.value.minimumFractionDigits) {
        return Math.pow(10, -formatOptions.value.minimumFractionDigits);
    }
    return 0.01;
};


watch(() => props.formatOptions, (newVal) => {
    if (newVal) {
        formatOptions.value = newVal;
    }
});
</script>

<template>
    <NumberField
        :id="id"
        :format-options="formatOptions"
        :model-value="model"
        @update:model-value="handleBlur"
        :min="min"
        :max="max"
        :step="getStepValue()"
        :default-value="defaultValue"
        :disabled="disabled"
    >
        <NumberFieldContent>
            <NumberFieldDecrement v-if="hasDecrementer" />
            <NumberFieldInput />
            <NumberFieldIncrement v-if="hasIncrementer" />
        </NumberFieldContent>
    </NumberField>
</template>
