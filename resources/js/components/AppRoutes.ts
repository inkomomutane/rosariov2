import type { NavItem } from '@/types';
import { GraduationCap, LayoutGrid, Users,ListCheck,ListChecks, FileText, DollarSign, CheckSquare } from 'lucide-vue-next';
import {t} from "@/lib/utils"

export const AppRoutes: NavItem[]  = [
    {
        title: t('Dashboard'),
        href: route('dashboard'),
        icon: LayoutGrid,
        routeName: 'dashboard',
        adminOnly: false,
    },
    {
        title: t('Details'),
        href: route('dashboard.user-person-edit'),
        icon: LayoutGrid,
        routeName: 'dashboard.user-person-edit',
        adminOnly: false,
    },
    {
        title: t('Patient Cases'),
        href: route('all-patient-cases'),
        icon: FileText,
        routeName: 'all-patient-cases',
        adminOnly: false,
    },
    {
        title: t('My Cases'),
        href: route('my-cases'),
        icon: ListCheck,
        routeName: 'my-cases',
        adminOnly: false,
    },
    {
        title: t('Assigned Cases'),
        href: route('assigned-cases'),
        icon: CheckSquare,
        routeName: 'assigned-cases',
        adminOnly: false,
    },
    {
        title: t('Unassigned Cases'),
        href: route('unassigned-cases'),
        icon: FileText,
        routeName: 'unassigned-cases',
        adminOnly: false,
    },
    {
        title: t('Balance'),
        href: route('balance'),
        icon: DollarSign,
        routeName: 'balance',
        adminOnly: false,
    }
];
