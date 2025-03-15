<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarGroup, SidebarGroupLabel, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Calendar, FileText, Folder, LayoutGrid, Settings } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const page = usePage();
// Check if the current user is a root user by using type assertions
const auth = page.props.auth as { user: { email: string }, is_root: boolean } | undefined;
const isRootUser = auth?.is_root;

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Timeline',
        href: '/timeline',
        icon: Calendar,
    },
];

const rootNavItems: NavItem[] = [
    {
        title: 'Blog',
        href: route('root.articles.index'),
        icon: FileText,
    },
    {
        title: 'Prompts',
        href: route('root.prompts.index'),
        icon: Settings,
    },
    {
        title: 'Categories',
        href: '#',
        icon: Settings,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits',
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
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <!-- Platform Section -->
            <SidebarGroup class="px-2 py-2">
                <SidebarGroupLabel>Plataforma</SidebarGroupLabel>
                <SidebarMenu>
                    <SidebarMenuItem v-for="item in mainNavItems" :key="item.title">
                        <SidebarMenuButton as-child :is-active="page.url.startsWith(item.href)">
                            <Link :href="item.href">
                                <component :is="item.icon" />
                                <span>{{ item.title }}</span>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarGroup>

            <!-- Root Section -->
            <SidebarGroup v-if="isRootUser" class="px-2 py-2">
                <SidebarGroupLabel>Root</SidebarGroupLabel>
                <SidebarMenu>
                    <SidebarMenuItem v-for="item in rootNavItems" :key="item.title">
                        <SidebarMenuButton as-child :is-active="page.url.startsWith(item.href)">
                            <Link :href="item.href">
                                <component :is="item.icon" />
                                <span>{{ item.title }}</span>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarGroup>
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
