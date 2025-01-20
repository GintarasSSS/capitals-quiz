import { createStore } from "vuex";
import questionsModule from "@/store/modules/questions/index.js";

export default createStore({
    modules: {
        questions: questionsModule
    }
})
