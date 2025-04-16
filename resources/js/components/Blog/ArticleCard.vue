<template>
  <div class="article-card-container group">
    <!-- Reading Time Indicator -->
    <div class="article-card-reading-time">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <span>{{ readingTime }} min read</span>
    </div>

    <!-- Card Image -->
    <div class="article-card-image">
      <img :src="heroImageSource" :alt="article.title" />
      
      <!-- Status Indicators -->
      <div class="article-card-gradient-overlay">
        <!-- Featured Badge -->
        <div v-if="article.is_featured" class="article-card-badge article-card-featured-badge">
          Featured
        </div>
        <div v-else class="w-8"></div>
        
        <!-- Publish Status -->
        <div class="article-card-badge article-card-status-badge">
          {{ article.published_at ? 'Published' : 'Draft' }}
        </div>
      </div>

      <!-- Pinned Indicator -->
      <div v-if="article.is_pinned" class="article-card-pin-ribbon">
        <div class="article-card-pin-ribbon-inner">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mt-1 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
          </svg>
        </div>
      </div>
    </div>
    
    <!-- Card Content -->
    <div class="article-card-content">
      <h3 class="article-card-title">
        {{ article.title }}
      </h3>
      
      <p class="article-card-excerpt">
        {{ excerpt }}
      </p>
      
      <!-- Categories and Tags -->
      <div class="article-card-tags-container">
        <div v-if="article.category && article.category.length" class="flex flex-wrap gap-1">
          <span v-for="cat in article.category" :key="cat.id" class="article-card-category">
            {{ cat.name }}
          </span>
        </div>
        <div v-if="article.tags && article.tags.length" class="flex flex-wrap gap-1">
          <span v-for="tag in article.tags" :key="tag.id" class="article-card-tag">
            {{ tag.name }}
          </span>
        </div>
      </div>
      
      <!-- Footer -->
      <div class="article-card-footer">
        <span class="flex items-center gap-1">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
          </svg>
          <span>{{ article.view_count }}</span>
        </span>
        <span>{{ formatDate }}</span>
      </div>

      <!-- View Link -->
      <div class="article-card-action">
        <div class="article-card-button-shine">
          <CyberLink :href="route('root.blog.articles.show', article.slug)" variant="outline" size="sm" full-width>
            Read Article
          </CyberLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import CyberLink from '@/components/theme/CyberLink.vue';
import { type Article } from '@/types';

const props = defineProps<{
  article: Article;
}>();

const excerpt = computed(() => {
  if (!props.article.body) return 'No content available';
  return props.article.body.substring(0, 150) + (props.article.body.length > 150 ? '...' : '');
});

const formatDate = computed(() => {
  if (!props.article.published_at) return 'Draft';
  const date = new Date(props.article.published_at);
  return new Intl.DateTimeFormat('en-US', { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric' 
  }).format(date);
});

const readingTime = computed(() => {
  if (!props.article.body) return 1;
  // Average reading speed: 225 words per minute
  const words = props.article.body.split(/\s+/).length;
  const minutes = Math.ceil(words / 225);
  return minutes;
});

const heroImageSource = computed(() => {
  if (!props.article.hero_image_url) return '/img/logo.png';
  
  // Check if the URL is an absolute URL (starts with http:// or https://)
  // if (props.article.hero_image_url.match(/^https?:\/\//)) {
  //   return props.article.hero_image_url;
  // }
  
  // If it's a relative URL, return as is
  console.log(props.article);
  return props.article.hero_image_url;
});
</script>

<style scoped>
.glass-effect {
  background-color: hsl(var(--card)) !important;
}

.cyber-button-container {
  position: relative;
  overflow: hidden;
  border-radius: 0.375rem;
}

.cyber-button-container::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 50%;
  height: 100%;
  background: linear-gradient(
    90deg,
    transparent,
    rgba(var(--primary-rgb), 0.5),
    transparent
  );
  transition: 0.5s;
  pointer-events: none;
}

.cyber-button-container:hover::before {
  left: 100%;
}
</style> 