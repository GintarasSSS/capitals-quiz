<template>
    <span class="fs-4">Country: </span>
    <strong class="fs-2">{{ questions.country }}</strong>

    <div class="btn-group d-block w-100 d-flex pt-3" role="group">
        <button
            v-for="(question, key) in questions.options"
            :key="key"
            type="button"
            class="btn btn-outline-primary"
            @click="submitAnswer(question.capital)"
        >
            {{ question.capital }}
        </button>
    </div>
</template>

<script>
    export default {
        props: ['questions', 'submitAnswer'],
    }
</script>
