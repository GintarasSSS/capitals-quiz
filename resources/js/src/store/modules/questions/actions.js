import axios from "axios";

export default {
    async loadQuestions(context) {
        const url = `/random-country`;
        let questions = [];

        context.commit('setAnswer', {});

        try {
            const questionsList = await axios.get(url);

            if (questionsList.data && Object.keys(questionsList.data).length > 0) {
                questions = questionsList.data;
            }
        } catch (err) {
            const message = 'Failed to fetch questions.';

            throw new Error(message);
        }

        context.commit('setQuestions', questions);
    },
    async submitAnswer(context, payload) {
        const url = `/capital-answer`;
        let answer = {};

        context.commit('setQuestions', []);

        try {
            const response = await axios.post(url, payload);

            if (response.status === 200 && response.data && Object.keys(response.data).length > 0) {
                answer = response.data;
            }
        } catch (err) {
            const message = 'Failed to fetch answer.';

            throw new Error(message);
        }

        context.commit('setAnswer', answer);
    },
    async exitQuiz(context, payload) {
        context.commit('setQuestions', []);
        context.commit('setAnswer', {});

        const url = `/exit-quiz`;

        try {
            await axios.delete(url, payload);
        } catch (err) {
            const message = 'Failed to exit quiz.';

            throw new Error(message);
        }
    }
}
