<script setup lang="ts">
import { Button } from '@/components/ui/button'
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select'

import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/ui/carousel'

defineProps({
    timelines: Array,
});
</script>

<template>
    <ul role="list" class="grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-3 sm:gap-x-6 lg:grid-cols-3 xl:gap-x-12">
        <li v-for="timeline in timelines" :key="timeline.id" class="relative w-[350px]">
            <Card class="dark:hover:bg-gray-600 cursor-pointer">
                <CardHeader>
                    <CardTitle>
                        {{ timeline.title }}
                    </CardTitle>
                    <CardDescription>
                        {{ timeline.excerpt }}
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <Carousel v-if="false" class="relative w-full max-w-xs">
                        <CarouselContent>
                            <CarouselItem v-for="(_, index) in 5" :key="index">
                                <div class="p-1">
                                    <Card>
                                        <CardContent class="flex aspect-square items-center justify-center p-6">
                                            <span class="text-4xl font-semibold">{{ index + 1 }}</span>
                                        </CardContent>
                                    </Card>
                                </div>
                            </CarouselItem>
                        </CarouselContent>
                        <CarouselPrevious />
                        <CarouselNext />
                    </Carousel>
                </CardContent>
                <CardFooter v-if="false" class="flex justify-between px-6 pb-6">
                    <Button variant="outline">
                        Cancel
                    </Button>
                    <Button>Deploy</Button>
                </CardFooter>
            </Card>
        </li>
    </ul>
</template>

<style scoped>

</style>
