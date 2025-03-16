<script setup lang="ts">
import Button from '@/components/theme/Button.vue';
import TimelineContainer from '@/components/theme/TimelineContainer.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

interface TimelineItem {
    id: number;
    title: string;
    excerpt: string;
    description: string;
    content: string;
    created_at: string;
    updated_at: string;
    author: {
        id: number;
        name: string;
        profile_photo_url?: string;
    };
    tags?: {
        id: number;
        name: string;
    }[];
}

const props = defineProps<{
    timelines: {
        data: TimelineItem[];
        links?: any;
        meta?: any;
    } | TimelineItem[];
}>();

const timelineData = ref<TimelineItem[]>([]);

onMounted(() => {
    // Handle both array and paginated data structure
    if (Array.isArray(props.timelines)) {
        timelineData.value = props.timelines;
    } else if (props.timelines && 'data' in props.timelines) {
        timelineData.value = props.timelines.data;
    }
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Timeline',
        href: '/timeline',
    },
];
</script>

<template>
    <Head title="Timeline" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white text-glow-medium">Timeline</h1>
                <Button to="/timeline/create" text="Create new Timeline Post" variant="primary" size="md" />
            </div>

            <!-- Timeline Container -->
            <div v-if="timelineData.length > 0">
                <TimelineContainer
                    :items="timelineData"
                    withScrollbar
                    scrollbarHeight="70vh"
                    :colorCycle="true"
                />

                <!-- Pagination -->
                <div v-if="!Array.isArray(props.timelines) && props.timelines.meta && props.timelines.meta.links" class="mt-8 flex justify-center">
                    <div class="flex space-x-1">
                        <a
                            v-for="(link, i) in props.timelines.meta.links"
                            :key="i"
                            :href="link.url"
                            v-html="link.label"
                            class="px-4 py-2 border rounded-md"
                            :class="[
                                link.active ? 'bg-primary text-white border-primary' : 'bg-gray-800 text-gray-300 border-gray-700 hover:bg-gray-700',
                                !link.url ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
                            ]"
                        ></a>
                    </div>
                </div>
            </div>

            <!-- No data message -->
            <div v-else class="text-center py-10">
                <h3 class="text-xl text-gray-400 mb-4">No timeline entries found</h3>
                <p class="text-gray-500 mb-6">Create your first timeline entry to get started</p>
                <Button to="/timeline/create" text="Create Timeline Entry" variant="primary" size="lg" />
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.text-glow-medium {
    text-shadow: 0 0 10px rgba(var(--color-primary-rgb), 0.7),
                 0 0 20px rgba(var(--color-primary-rgb), 0.5);
}

.timeline-container {
    position: relative;
    max-width: 4xl;
    margin: 0 auto;
    width: 100%;
    padding: 0 1rem;
    box-shadow: 0 0 20px rgba(var(--color-primary-rgb), 0.05);
}

.timeline-scroll-container {
    padding: 2rem 1rem;
    background: linear-gradient(
        to bottom,
        rgba(var(--color-primary-rgb), 0.03),
        rgba(var(--color-secondary-rgb), 0.02),
        rgba(var(--color-accent-rgb), 0.03)
    );
    border-radius: 8px;
}

.timeline-line {
    position: absolute;
    left: 50%;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom,
        rgba(var(--color-primary-rgb), 0.9),
        rgba(var(--color-secondary-rgb), 0.9),
        rgba(var(--color-accent-rgb), 0.9),
        rgba(var(--color-primary-rgb), 0.9)
    );
    box-shadow:
        0 0 10px rgba(var(--color-primary-rgb), 0.7),
        0 0 20px rgba(var(--color-primary-rgb), 0.5),
        0 0 30px rgba(var(--color-primary-rgb), 0.3);
    z-index: 0;
    transform: translateX(-50%);
    animation: neonPulse 3s infinite;
}

@keyframes neonPulse {
    0%, 100% {
        opacity: 0.8;
        box-shadow:
            0 0 10px rgba(var(--color-primary-rgb), 0.5),
            0 0 20px rgba(var(--color-primary-rgb), 0.3),
            0 0 30px rgba(var(--color-primary-rgb), 0.1);
    }
    50% {
        opacity: 1;
        box-shadow:
            0 0 15px rgba(var(--color-primary-rgb), 0.7),
            0 0 25px rgba(var(--color-primary-rgb), 0.5),
            0 0 35px rgba(var(--color-primary-rgb), 0.3);
    }
}

.timeline-items {
    position: relative;
    z-index: 1;
}

.timeline-item {
    position: relative;
    display: flex;
    flex-direction: row;
    margin-bottom: 3rem;
    align-items: flex-start;
}

.timeline-item-even {
    flex-direction: row-reverse;
}

.timeline-date {
    width: calc(50% - 1rem);
    padding-right: 2rem;
    text-align: right;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    position: relative;
    top: 1.5rem;
}

.timeline-item-even .timeline-date {
    padding-right: 0;
    padding-left: 2rem;
    text-align: left;
    justify-content: flex-start;
}

.date-text {
    display: inline-block;
    color: rgb(var(--color-cyan-400-rgb, 56, 189, 248));
    font-size: 0.875rem;
    font-weight: 600;
    padding: 0.25rem 0.75rem;
    background: rgba(var(--color-primary-rgb), 0.1);
    border-radius: 1rem;
    border: 1px solid rgba(var(--color-primary-rgb), 0.2);
    box-shadow: 0 0 10px rgba(var(--color-primary-rgb), 0.2);
}

.timeline-content {
    position: relative;
    width: calc(50% - 1rem);
    padding-left: 2rem;
}

.timeline-item-even .timeline-content {
    padding-left: 0;
    padding-right: 2rem;
}

.timeline-dot {
    position: absolute;
    left: -11px;
    top: 1.5rem;
    height: 22px;
    width: 22px;
    border-radius: 50%;
    border: 2px solid rgb(var(--color-cyan-400-rgb, 56, 189, 248));
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 2;
    animation: dotPulse 3s infinite;
}

.timeline-item-even .timeline-dot {
    left: auto;
    right: -11px;
}

@keyframes dotPulse {
    0%, 100% {
        box-shadow:
            0 0 0 0 rgba(var(--color-primary-rgb), 0.4),
            0 0 0 0 rgba(var(--color-primary-rgb), 0.2);
    }
    50% {
        box-shadow:
            0 0 0 4px rgba(var(--color-primary-rgb), 0.4),
            0 0 0 8px rgba(var(--color-primary-rgb), 0.2);
    }
}

.timeline-dot-inner {
    position: absolute;
    inset: 3px;
    border-radius: 50%;
    background-color: rgb(var(--color-cyan-400-rgb, 56, 189, 248));
    animation: innerDotPulse 3s infinite;
}

@keyframes innerDotPulse {
    0%, 100% { opacity: 0.8; }
    50% { opacity: 1; }
}

.glass-container-neon {
    animation: neonColorCycle 10s infinite;
}

@keyframes neonColorCycle {
    0%, 100% {
        box-shadow:
            0 0 15px rgba(var(--color-primary-rgb), 0.3),
            0 0 30px rgba(var(--color-primary-rgb), 0.2);
    }
    33% {
        box-shadow:
            0 0 15px rgba(var(--color-secondary-rgb), 0.3),
            0 0 30px rgba(var(--color-secondary-rgb), 0.2);
    }
    66% {
        box-shadow:
            0 0 15px rgba(var(--color-accent-rgb), 0.3),
            0 0 30px rgba(var(--color-accent-rgb), 0.2);
    }
}

/* Add a subtle glow to the timeline items on hover */
.timeline-item:hover .glass-container-neon {
    box-shadow:
        0 0 20px rgba(var(--color-primary-rgb), 0.4),
        0 0 40px rgba(var(--color-primary-rgb), 0.2);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .timeline-line {
        left: 20px;
    }

    .timeline-container {
        padding: 0 0.5rem;
    }

    .timeline-scroll-container {
        padding: 2rem 0.5rem;
    }

    .timeline-item,
    .timeline-item-even {
        flex-direction: column;
        margin-left: 20px;
        margin-bottom: 2.5rem;
    }

    .timeline-date,
    .timeline-item-even .timeline-date {
        width: 100%;
        padding: 0 0 0.5rem 2.5rem;
        text-align: left;
        justify-content: flex-start;
        top: 0;
    }

    .timeline-content,
    .timeline-item-even .timeline-content {
        width: calc(100% - 2.5rem);
        padding-left: 2.5rem;
        padding-right: 0;
    }

    .timeline-dot,
    .timeline-item-even .timeline-dot {
        left: -11px;
        right: auto;
        top: 2.5rem;
    }
}
</style>
