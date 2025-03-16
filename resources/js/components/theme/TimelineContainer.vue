<script setup lang="ts">
import NeonScrollbar from '@/components/theme/NeonScrollbar.vue';
import TimelineItemCard from '@/components/theme/TimelineItemCard.vue';
import { TimelineItem } from '@/types/timeline-item';

const props = defineProps<{
  items: TimelineItem[];
  withScrollbar?: boolean;
  scrollbarHeight?: string;
  colorCycle?: boolean;
}>();

// Default values
const withScrollbar = props.withScrollbar !== undefined ? props.withScrollbar : true;
const scrollbarHeight = props.scrollbarHeight || '70vh';
const colorCycle = props.colorCycle !== undefined ? props.colorCycle : true;
</script>

<template>
  <div class="timeline-container">
    <!-- Background effect -->
    <div class="absolute inset-0 bg-gradient-to-t from-background/50 to-transparent">
      <div class="cyberpunk-grid-bg absolute inset-0 opacity-10"></div>
    </div>

    <template v-if="withScrollbar">
      <!-- Timeline wrapper with fixed height and scrolling -->
      <NeonScrollbar :height="scrollbarHeight" class="timeline-scroll-container" :colorCycle="colorCycle">
        <div class="relative pb-10">
          <!-- Timeline line -->
          <div class="timeline-line"></div>

          <!-- Timeline items -->
          <div class="timeline-items">
            <TimelineItemCard
              v-for="(item, index) in items"
              :key="item.id"
              :item="item"
              :position="index % 2 === 0 ? 'left' : 'right'"
            />
          </div>
        </div>
      </NeonScrollbar>
    </template>

    <template v-else>
      <div class="relative pb-10">
        <!-- Timeline line -->
        <div class="timeline-line"></div>

        <!-- Timeline items -->
        <div class="timeline-items">
          <TimelineItemCard
            v-for="(item, index) in items"
            :key="item.id"
            :item="item"
            :position="index % 2 === 0 ? 'left' : 'right'"
          />
        </div>
      </div>
    </template>
  </div>
</template>
