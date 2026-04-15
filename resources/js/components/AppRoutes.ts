import type { NavItem } from '@/types';
import { GraduationCap, LayoutGrid, Users,ListCheck,ListChecks } from 'lucide-vue-next';
import {t} from "@/lib/utils"

export const AppRoutes: NavItem[]  = [
    {
        title: t('Profile'),
        href: route('profile.base-info'),
        icon: GraduationCap,
        routeName: 'profile.base-info',
        adminOnly: false,
    },
    {
        title: t('Members'),
        href: route('prm-members'),
        icon: Users,
        routeName: 'prm-members',
        adminOnly: true,
    },
    {
        title: t('Mapa de efectividade'),
        href: route('members.presence-register.list'),
        icon: ListCheck,
        routeName: 'members.presence-register.list',
        adminOnly: true,
    },
];
