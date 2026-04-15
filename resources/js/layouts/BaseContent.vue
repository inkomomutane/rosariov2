<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { t} from '@/lib/utils';
import { Card } from '@/components/ui/card';
import {
    NavigationMenu, NavigationMenuItem,
    NavigationMenuLink,
    NavigationMenuList,
    navigationMenuTriggerStyle
} from '@/components/ui/navigation-menu';
import { computed, ref } from 'vue';
import { GraduationCap, User,BriefcaseBusiness  } from 'lucide-vue-next';


const props = defineProps({
    title: {
        type: String,
        required: true,
    },
})


const page = usePage();

const isCurrentRoute = computed(() => (routeName: string) => route().current(routeName ?? ''));

const activeItemStyles = computed(() => (routeName: string) => (isCurrentRoute.value(routeName) ? 'text-neutral-900 dark:bg-neutral-800 dark:text-neutral-100' : ''),
);
const mainNavItems: NavItem[] = ref([
    {
        title: t('Profile'),
        href: route('profile.base-info',),
        icon: User,
        routeName: 'profile.base-info',
    },
    {
        title: t('Education'),
        href: route('profile.education.list',),
        icon: GraduationCap,
        routeName: 'profile.education.list',
    },
    {
        title: t('Experience'),
        href: route('profile.experience.list',),
        icon: BriefcaseBusiness ,
        routeName: 'profile.experience.list',
    },
    {
        title: t('Disciplinary Records'),
        href: route('profile.disciplinary-record.list',),
        icon: BriefcaseBusiness ,
        routeName: 'profile.disciplinary-record.list',
    },
    {
        title: t('Member card'),
        href: route('member.card',),
        icon: BriefcaseBusiness ,
        routeName: 'member.card',
    },
]);
</script>

<template>
    <Card class="px-4 pt-6 bg-white dark:bg-zinc-950  mt-0  rounded-none shadow-none">
        <Heading class="mb-2 pb-0" :title="title" :description="`${title}&nbsp;`+ $t('settings management')" />
        <NavigationMenu class=" flex h-full items-stretch ">

            <NavigationMenuList class="flex h-full items-stretch space-x-2">
                <NavigationMenuItem v-for="(item, index) in mainNavItems" :key="index" class="relative flex h-full items-center pb-2">
                    <Link :href="item.href">
                        <NavigationMenuLink
                            :class="[navigationMenuTriggerStyle(), activeItemStyles(item.routeName), 'h-9 cursor-pointer px-3']"
                        >
                            <component v-if="item.icon" :is="item.icon" class="mr-2 h-5 w-5" />
                          <span class="hidden md:flex">  {{ item.title }}</span>
                        </NavigationMenuLink>
                    </Link>
                    <div
                        v-if="isCurrentRoute(item.routeName)"
                        class="absolute bottom-0 left-0 h-0.5 w-full translate-y-px bg-black dark:bg-white"
                    ></div>
                </NavigationMenuItem>
            </NavigationMenuList>
        </NavigationMenu>
    </Card>
    <section class="">
        <slot />
    </section>
</template>
