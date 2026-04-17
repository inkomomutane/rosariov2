<script setup lang="ts">
import { PropType } from 'vue';
import { PersonDto } from '@/types/generated';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { t } from '@/lib/utils';
import ContainerBox from '@/components/Box/ContainerBox.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';

const props = defineProps({
    person : {
        type: Object as PropType<PersonDto>,
        required: true
    }
})

const breadcrumbs = [
    {
        title: t('Dashboard'),
        href: '/dashboard',
    },
];
const form = useForm(props.person)


const updatePersonInfo = () => {
    form.post(route('dashboard.user-person-update'))
}

</script>

<template>
    <Head :title="$t('Details')" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
             <ContainerBox>

                 <template #header-left>
                        <h2 class="text-lg font-bold">
                            {{ t('Details') }}
                        </h2>
                 </template>


                 <template #header-right>
                     <Button variant="outline" @click="updatePersonInfo">
                         {{ t('Update') }}
                     </Button>
                 </template>
                  <template #content-table>
                      <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                          <div>
                              <Label for="name">{{ t('Name') }}</Label>
                              <Input id="name" ref="name" v-model="form.name" :placeholder="t('Name')" name="name" type="text" />
                              <InputError :message="form.errors.name" />
                          </div>

                          <div>
                                <Label for="last_name">{{ t('Last Name') }}</Label>
                                <Input id="last_name" ref="last_name" v-model="form.last_name" :placeholder="t('Last Name')" name="last_name" type="text" />
                                <InputError :message="form.errors.last_name" />
                          </div>

                          <div>
                                <Label for="middle_name">{{ t('Middle Name') }}</Label>
                                <Input id="middle_name" ref="middle_name" v-model="form.middle_name" :placeholder="t('Middle Name')" name="middle_name" type="text" />
                                <InputError :message="form.errors.middle_name" />
                          </div>

                          <div>
                                <Label for="gender">{{ t('Gender') }}</Label>
                                <Input id="gender" v-model="form.gender" :placeholder="t('Gender')" name="gender" type="text" />
                                <InputError :message="form.errors.gender" />
                          </div>

                          <div>
                                <Label for="marital_status">{{ t('Marital Status') }}</Label>
                                <Input id="marital_status" v-model="form.marital_status" :placeholder="t('Marital Status')" name="marital_status" type="text" />
                                <InputError :message="form.errors.marital_status" />
                          </div>

                          <div>
                                <Label for="birth_date">{{ t('Birth Date') }}</Label>
                                <Input id="birth_date" v-model="form.birth_date" name="birth_date" type="date" />
                                <InputError :message="form.errors.birth_date" />
                          </div>

                          <div>
                                <Label for="nationality">{{ t('Nationality') }}</Label>
                                <Input id="nationality" v-model="form.nationality" :placeholder="t('Nationality')" name="nationality" type="text" />
                                <InputError :message="form.errors.nationality" />
                          </div>

                          <div>
                                <Label for="province">{{ t('Province') }}</Label>
                                <Input id="province" v-model="form.province" :placeholder="t('Province')" name="province" type="text" />
                                <InputError :message="form.errors.province" />
                          </div>

                          <div>
                                <Label for="city">{{ t('City') }}</Label>
                                <Input id="city" v-model="form.city" :placeholder="t('City')" name="city" type="text" />
                                <InputError :message="form.errors.city" />
                          </div>

                          <div>
                                <Label for="postal_code">{{ t('Postal Code') }}</Label>
                                <Input id="postal_code" v-model="form.postal_code" :placeholder="t('Postal Code')" name="postal_code" type="text" />
                                <InputError :message="form.errors.postal_code" />
                          </div>

                          <div>
                                <Label for="address_one">{{ t('Address 1') }}</Label>
                                <Input id="address_one" v-model="form.address_one" :placeholder="t('Address 1')" name="address_one" type="text" />
                                <InputError :message="form.errors.address_one" />
                          </div>

                          <div>
                                <Label for="address_two">{{ t('Address 2') }}</Label>
                                <Input id="address_two" v-model="form.address_two" :placeholder="t('Address 2')" name="address_two" type="text" />
                                <InputError :message="form.errors.address_two" />
                          </div>

                          <div>
                                <Label for="address_three">{{ t('Address 3') }}</Label>
                                <Input id="address_three" v-model="form.address_three" :placeholder="t('Address 3')" name="address_three" type="text" />
                                <InputError :message="form.errors.address_three" />
                          </div>

                          <div>
                                <Label for="address_four">{{ t('Address 4') }}</Label>
                                <Input id="address_four" v-model="form.address_four" :placeholder="t('Address 4')" name="address_four" type="text" />
                                <InputError :message="form.errors.address_four" />
                          </div>

                          <div>
                                <Label for="phone_one">{{ t('Phone 1') }}</Label>
                                <Input id="phone_one" v-model="form.phone_one" :placeholder="t('Phone 1')" name="phone_one" type="text" />
                                <InputError :message="form.errors.phone_one" />
                          </div>

                          <div>
                                <Label for="phone_two">{{ t('Phone 2') }}</Label>
                                <Input id="phone_two" v-model="form.phone_two" :placeholder="t('Phone 2')" name="phone_two" type="text" />
                                <InputError :message="form.errors.phone_two" />
                          </div>

                          <div>
                                <Label for="phone_three">{{ t('Phone 3') }}</Label>
                                <Input id="phone_three" v-model="form.phone_three" :placeholder="t('Phone 3')" name="phone_three" type="text" />
                                <InputError :message="form.errors.phone_three" />
                          </div>

                          <div>
                                <Label for="phone_four">{{ t('Phone 4') }}</Label>
                                <Input id="phone_four" v-model="form.phone_four" :placeholder="t('Phone 4')" name="phone_four" type="text" />
                                <InputError :message="form.errors.phone_four" />
                          </div>

<!--                          <div>-->
<!--                                <Label for="death_date">{{ t('Death Date') }}</Label>-->
<!--                                <Input id="death_date" v-model="form.death_date" name="death_date" type="date" />-->
<!--                                <InputError :message="form.errors.death_date" />-->
<!--                          </div>-->

<!--                          <div class="md:col-span-2 lg:col-span-3">-->
<!--                                <Label for="cause_of_death">{{ t('Cause of Death') }}</Label>-->
<!--                                <Textarea id="cause_of_death" v-model="form.cause_of_death" :placeholder="t('Cause of Death')" name="cause_of_death" type="text" />-->
<!--                                <InputError :message="form.errors.cause_of_death" />-->
<!--                          </div>-->
                      </div>
                  </template>


             </ContainerBox>
        </div>
     </AppLayout>
</template>
