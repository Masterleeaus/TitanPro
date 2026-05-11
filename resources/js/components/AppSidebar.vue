<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type AppPageProps, type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, Paintbrush, Smartphone, TrendingUp, UserRound } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const page = usePage<AppPageProps>();
const roles = page.props.auth?.roles ?? [];
const canAccessTitanGo = roles.some((role) => ['owner', 'admin', 'super_admin'].includes(role));
const canAccessTitanStudio = roles.some((role) => ['owner', 'admin'].includes(role));
const canAccessTitanNexus = roles.some((role) => ['owner', 'admin'].includes(role));
const canAccessTitanSolo = roles.includes('owner') && ['starter', 'solo', 'single_operator'].includes(page.props.plan?.current ?? '');

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    ...(canAccessTitanGo
        ? [
              {
                  title: 'TitanGo Panel',
                  href: '/titango',
                  icon: Smartphone,
              },
          ]
        : []),
    ...(canAccessTitanStudio
        ? [
              {
                  title: 'TitanStudio Panel',
                  href: '/titanstudio',
                  icon: Paintbrush,
              },
          ]
        : []),
    ...(canAccessTitanNexus
        ? [
              {
                  title: 'TitanNexus Panel',
                  href: '/titannexus',
                  icon: TrendingUp,
              },
          ]
        : []),
    ...(canAccessTitanSolo
        ? [
              {
                  title: 'TitanSolo Panel',
                  href: '/titansolo',
                  icon: UserRound,
              },
          ]
        : []),
];

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
