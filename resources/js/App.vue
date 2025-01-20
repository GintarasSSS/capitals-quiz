<template>
    <the-header />
    <the-question />
</template>

<script>
    import TheHeader from "@/components/layout/TheHeader.vue";
    import TheQuestion from "@/components/ui/TheQuestion.vue";

    export default {
        name: "App",
        components: {
            TheHeader,
            TheQuestion
        }
    }
</script>
