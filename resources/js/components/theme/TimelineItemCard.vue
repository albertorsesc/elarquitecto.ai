<script setup lang="ts">
import Button from '@/components/theme/Button.vue';
import GlassContainer from '@/components/theme/GlassContainer.vue';
import NeonBorders from '@/components/theme/NeonBorders.vue';
import NeonCorners from '@/components/theme/NeonCorners.vue';
import { TimelineItem } from '@/types/timeline-item';
import { format, formatDistanceToNow } from 'date-fns';
import { es } from 'date-fns/locale';

const props = defineProps<{
  item: TimelineItem;
  position?: 'left' | 'right';
  showActions?: boolean;
  showTags?: boolean;
  variant?: 'default' | 'compact';
}>();

// Default values for optional props
const position = props.position || 'left';
const showActions = props.showActions !== undefined ? props.showActions : true;
const showTags = props.showTags !== undefined ? props.showTags : true;
const variant = props.variant || 'default';

// Format date as relative time (e.g., "2 days ago")
const formatDate = (date: string) => {
  try {
    return formatDistanceToNow(new Date(date), { addSuffix: true, locale: es });
  } catch (error) {
    console.error('Error formatting date:', error);
    return date;
  }
};

// Format date as short date (e.g., "15 Mar 2023")
const formatDateShort = (date: string) => {
  try {
    return format(new Date(date), 'dd MMM yyyy', { locale: es });
  } catch {
    return date.split('T')[0];
  }
};
</script>

<template>
  <div
    class="timeline-item"
    :class="{
      'timeline-item-right': position === 'right',
      'timeline-item-compact': variant === 'compact'
    }"
  >
    <!-- Date -->
    <div class="timeline-date">
      <span class="date-text">{{ formatDateShort(item.created_at) }}</span>
    </div>

    <!-- Content -->
    <div class="timeline-content">
      <GlassContainer
        variant="dark"
        withBorders
        withCorners
        rounded="xl"
        :padding="variant === 'compact' ? 'sm' : 'md'"
        class="relative glass-container-neon overflow-hidden"
      >
        <NeonCorners position="all" color="primary" size="md" class="absolute -inset-1" />
        <NeonBorders position="all" color="primary" :opacity="0.3" class="absolute -inset-1" />

        <div :class="variant === 'compact' ? 'p-2' : 'p-3'">
          <!-- Relative time -->
          <div class="mb-1">
            <p class="text-xs text-gray-400">{{ formatDate(item.created_at) }}</p>
          </div>

          <!-- Content -->
          <h2 :class="variant === 'compact' ? 'text-base' : 'text-lg'" class="font-bold text-white mb-2">{{ item.title }}</h2>

          <p v-if="item.excerpt" class="text-xs text-gray-300 mb-2">{{ item.excerpt }}</p>

          <div v-if="variant !== 'compact'" class="text-xs text-gray-300 mb-3">{{ item.description }}</div>

          <!-- Tags -->
          <div v-if="showTags && item.tags && item.tags.length > 0" class="flex flex-wrap gap-1 mt-2">
            <span
              v-for="tag in item.tags"
              :key="tag.id"
              class="px-2 py-0.5 text-xs rounded-full bg-primary/20 text-primary border border-primary/30"
            >
              #{{ tag.name }}
            </span>
          </div>

          <!-- Actions -->
          <div v-if="showActions" class="flex justify-between mt-3 pt-2 border-t border-gray-700">
            <Button
              :to="`/timeline/${item.id}`"
              text="View Details"
              variant="secondary"
              size="sm"
            />
            <Button
              :to="`/timeline/${item.id}/edit`"
              text="Edit"
              variant="secondary"
              size="sm"
            />
          </div>
        </div>
      </GlassContainer>

      <!-- Timeline dot -->
      <div class="timeline-dot">
        <div class="timeline-dot-inner"></div>
      </div>
    </div>
  </div>
</template>