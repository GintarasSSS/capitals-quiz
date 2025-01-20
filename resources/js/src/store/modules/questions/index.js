import mutations from "@/store/modules/questions/mutations";
import actions from "@/store/modules/questions/actions";
import getters from "@/store/modules/questions/getters";

export default {
    namespaced: true,
    state() {
        return {
            questions: [],
            answer: {}
        }
    },
    mutations,
    actions,
    getters
}
