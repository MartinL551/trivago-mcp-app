<script setup lang="ts">
    defineProps<{
        amenities: string[];
    }>();
</script>

<template>
    <section aria-label="Amenities">
        <ul class="flex flex-wrap gap-2">
            <li class="border-1 rounded shadow-[var(--card-shadow)] bg-[var(--chip-bg)] border-[var(--chip-border)] py-1 px-2" v-for="amenity in amenities" :key="amenity">
                {{ amenity }}
            </li>
        </ul>
    </section>
</template>
