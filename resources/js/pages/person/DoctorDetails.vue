<script setup lang="ts">
import { PropType } from 'vue';
import { DoctorDto } from '@/types/generated';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { t } from '@/lib/utils';
import ContainerBox from '@/components/Box/ContainerBox.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import VTabs from '@/components/VTabs.vue';
// Import the datepicker component
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

const props = defineProps({
    doctor: {
        type: Object as PropType<DoctorDto>,
        required: true
    }
})

const breadcrumbs = [
    {
        title: t('Dashboard'),
        href: '/dashboard',
    },
];

const form = useForm({ ...props.doctor });

const updateDoctorInfo = () => {
    form.post(route('dashboard.user-doctor-update'))
}

</script>

<template>
    <Head :title="$t('Doctor Details')" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">

            <ContainerBox>
                <template #tabs>
                    <VTabs :tabs="[
                  {
                    title: t('Personal Details'),
                    url: route('dashboard.user-person-edit'),
                    isActive: route().current('dashboard.user-person-edit'),
                  },
                  {
                    title: t('Doctor Details'),
                    url: route('dashboard.user-doctor-edit'),
                    isActive: route().current('dashboard.user-doctor-edit'),
                  },
                ]"
                    />
                </template>

                <template #header-left>
                    <h2 class="text-lg font-bold">
                        {{ t('Doctor Details') }}
                    </h2>
                </template>


                <template #header-right>
                    <Button variant="outline" :disabled="form.processing" @click="updateDoctorInfo">
                        {{ t('Update') }}
                    </Button>
                </template>

                <template #content-table>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <Label for="name">{{ t('First Name') }}</Label>
                            <Input id="name" v-model="form.name" :placeholder="t('First Name')" type="text" />
                            <InputError :message="form.errors.name" />
                        </div>

                        <div>
                            <Label for="last_name">{{ t('Last Name') }}</Label>
                            <Input id="last_name" v-model="form.last_name" :placeholder="t('Last Name')" type="text" />
                            <InputError :message="form.errors.last_name" />
                        </div>

                        <div>
                            <Label for="hospital">{{ t('Hospital') }}</Label>
                            <Input id="hospital" v-model="form.hospital" :placeholder="t('Hospital Name')" type="text" />
                            <InputError :message="form.errors.hospital" />
                        </div>

                        <div>
                            <Label for="specialization">{{ t('Specialization') }}</Label>
                            <Input id="specialization" v-model="form.specialization" :placeholder="t('Specialization')" type="text" />
                            <InputError :message="form.errors.specialization" />
                        </div>

                        <div>
                            <Label for="license_number">{{ t('License Number') }}</Label>
                            <Input id="license_number" v-model="form.license_number" :placeholder="t('License Number')" type="text" />
                            <InputError :message="form.errors.license_number" />
                        </div>

                        <div>
                            <Label for="years_of_experience">{{ t('Years of Experience') }}</Label>
                            <Input id="years_of_experience" v-model="form.years_of_experience" type="number" />
                            <InputError :message="form.errors.years_of_experience" />
                        </div>

                        <div>
                            <Label for="medical_school">{{ t('Medical School') }}</Label>
                            <Input id="medical_school" v-model="form.medical_school" :placeholder="t('Medical School')" type="text" />
                            <InputError :message="form.errors.medical_school" />
                        </div>

                        <!-- License Issue Date -->
                        <div>
                            <Label for="license_issue_date">{{ t('License Issue Date') }}</Label>
                            <VueDatePicker
                                v-model="form.license_issue_date"
                                :enable-time-picker="false"
                                :format="'dd/MM/yyyy'"
                                :placeholder="t('License Issue Date')"
                                :text-input="{ format: 'dd.MM.yyyy' }"
                                auto-apply
                                teleport
                            />
                            <InputError :message="form.errors.license_issue_date" />
                        </div>

                        <!-- License Expiry Date -->
                        <div>
                            <Label for="license_expiry_date">{{ t('License Expiry Date') }}</Label>
                            <VueDatePicker
                                v-model="form.license_expiry_date"
                                :enable-time-picker="false"
                                :format="'dd/MM/yyyy'"
                                :placeholder="t('License Expiry Date')"
                                :text-input="{ format: 'dd.MM.yyyy' }"
                                auto-apply
                                teleport
                            />
                            <InputError :message="form.errors.license_expiry_date" />
                        </div>

                        <!-- Graduation Date -->
                        <div>
                            <Label for="graduation_date">{{ t('Graduation Date') }}</Label>
                            <VueDatePicker
                                v-model="form.graduation_date"
                                :enable-time-picker="false"
                                :format="'dd/MM/yyyy'"
                                :placeholder="t('Graduation Date')"
                                :text-input="{ format: 'dd.MM.yyyy' }"
                                auto-apply
                                teleport
                            />
                            <InputError :message="form.errors.graduation_date" />
                        </div>
                    </div>
                </template>
            </ContainerBox>
        </div>
    </AppLayout>
</template>
