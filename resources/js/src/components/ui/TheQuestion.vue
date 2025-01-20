<template>
    <div class="container-xl pt-3">
        <base-card>
            <div v-if="Object.keys(questions).length">
                <the-quiz
                    :questions="questions"
                    :submitAnswer="submitAnswer"
                />
            </div>
            <div v-else>
                <the-warning>Questions list is empty....</the-warning>
            </div>

            <the-answer
                :answer="answer"
                :answerCorrect="answerCorrect"
                :answerCapital="answerCapital"
                :nextQuestion="nextQuestion"
                :exitQuiz="exitQuiz"
            />
        </base-card>
    </div>
</template>

<script>
    import { computed, ref, onMounted } from "vue";
    import { useStore } from "vuex";
    import TheAnswer from "@/components/ui/TheAnswer.vue";
    import TheWarning from "@/components/ui/TheWarning.vue";
    import TheQuiz from "@/components/ui/TheQuiz.vue";

    export default {
        components: {
            TheAnswer,
            TheWarning,
            TheQuiz
        },
        setup() {
            const store = useStore();
            const token = ref('');

            onMounted(() => {
                const tokenInput = document.querySelector('#token input');
                if (tokenInput) {
                    token.value = tokenInput.value;
                }
            });

            const questions = computed(() => store.getters['questions/getQuestions']);
            const answer = computed(() => store.getters['questions/getAnswer']);
            const answerCorrect = computed(() => answer.value.correct);
            const answerCapital = computed(() => answer.value.capital);

            const loadQuestions = async () => {
                try {
                    await store.dispatch("questions/loadQuestions");
                } catch (error) {
                    console.error(error);
                }
            };

            const submitAnswer = async (newCapital) => {
                try {
                    await store.dispatch('questions/submitAnswer', {
                        capital: newCapital,
                        _token: token.value,
                    });
                } catch (error) {
                    console.error(error);
                }
            };

            const nextQuestion = async () => {
                await loadQuestions();
            };

            const exitQuiz = async () => {
                try {
                    await store.dispatch("questions/exitQuiz", {
                        _token: token.value,
                    });
                } catch (error) {
                    console.error(error);
                }
            }

            loadQuestions();

            return {
                questions,
                answer,
                answerCorrect,
                answerCapital,
                nextQuestion,
                submitAnswer,
                exitQuiz
            };
        }
    }
</script>
