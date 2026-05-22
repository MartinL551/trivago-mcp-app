<script setup lang="ts">
    defineProps<{
        googleLoginUrl: string;
    }>();
</script>

<template>
    <a :href="googleLoginUrl">
        Login with Google
    </a>
</template>