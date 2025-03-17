<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const props = withDefaults(defineProps<{
  title?: string;
  description?: string;
  keywords?: string;
  canonicalUrl?: string;
  ogType?: string;
  ogImage?: string;
  twitterCard?: string;
}>(), {
  title: 'El Arquitecto A.I.',
  description: 'Democratizando I.A. para el beneficio de Latinoamérica',
  keywords: 'inteligencia artificial, IA, machine learning, español, Latinoamérica',
  canonicalUrl: '',
  ogType: 'website',
  ogImage: '/logo.png',
  twitterCard: 'summary_large_image'
});

// Debug received props
onMounted(() => {
  console.log('SeoHead component mounted with props:', {
    title: props.title,
    description: props.description,
    canonicalUrl: props.canonicalUrl,
    ogImage: props.ogImage
  });
});

// Type the page object with the expected structure
const page = usePage<{
  ziggy: {
    location: string;
  };
}>();

// Make sure we have a string title with branding
const fullTitle = typeof props.title === 'string' && props.title.includes('El Arquitecto A.I.')
  ? props.title
  : `${props.title || 'Página'} - El Arquitecto A.I.`;

// Format image URL to be absolute if needed
const formatImageUrl = (url: string | null | undefined) => {
  if (!url) {
    return `${window.location.origin}/logo.png`; // Default fallback image
  }

  if (url.startsWith('http')) {
    return url;
  }

  // Make sure the URL starts with a single slash
  const cleanPath = url.startsWith('/') ? url : `/${url}`;
  const baseUrl = window.location.origin;
  return `${baseUrl}${cleanPath}`;
};

const absoluteImageUrl = formatImageUrl(props.ogImage);
const currentPageUrl = props.canonicalUrl || page.props.ziggy.location;

// Make sure description is a non-empty string and not too long
const safeDescription = typeof props.description === 'string' && props.description.trim().length > 0
  ? props.description.slice(0, 160)
  : 'Democratizando I.A. para el beneficio de Latinoamérica';

console.log('SeoHead rendering with:', {
  fullTitle,
  safeDescription,
  absoluteImageUrl,
  currentPageUrl
});
</script>

<template>
  <!--
    We only update the title element through the Head component
    since the rest of the meta tags are server-rendered with the same data
    This avoids conflicts and ensures search engines see the correct tags
  -->
  <Head title-only>
    <title>{{ fullTitle }}</title>
  </Head>
</template>