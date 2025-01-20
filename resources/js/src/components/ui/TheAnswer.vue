<template>
    <div v-if="Object.keys(answer).length" class="pt-3">
        <div v-if="answerCorrect" class="alert alert-success text-center mb-3">
            You are correct. Answer is <span class="fw-bold">{{ answerCapital }}</span>
        </div>
        <div v-else class="alert alert-danger text-center mb-3">
            You are wrong. Answer is <span class="fw-bold">{{ answerCapital }}</span>
        </div>

        <div class="btn-group w-100 d-flex">
            <button @click="nextQuestion" type="button" class="btn btn-success fs-5">Continue</button>
            <button @click="exitQuiz" type="button" class="btn btn-danger fs-5">Finish</button>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'answer',
            'answerCorrect',
            'answerCapital',
            'nextQuestion',
            'nextQuestion',
            'exitQuiz'
        ]
    }
</script>
