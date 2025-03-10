<script setup lang="ts">
import { ArticleType } from '@/types/article';

defineProps({
    article: ArticleType,
});
</script>

<template>
    <div>
        <h1>Edit Article</h1>
        <form>
            <input type="text" v-model="article.title" />
            <textarea v-model="article.body"></textarea>
            <button type="submit">Save</button>
        </form>
    </div>
</template>

<style scoped>

</style>
