import axios from "axios";
import { setHttpToken } from "@/helpers";

const auth = {
    namespaced: true,
    state: () => ({
        token: null,
        user: null
    }),
    mutations: {
        SET_TOKEN(state, payload) {
            state.token = payload;
        },
        SET_USER(state, payload) {
            state.user = payload;
        }
    },
    actions: {
        register(_, payload) {
            return new Promise((resolve, reject) => {
                axios
                    .post("v1/auth/register", payload)
                    .then(res => {
                        resolve(res);
                    })
                    .catch(err => {
                        reject(err);
                    });
            });
        },
        async login({ dispatch }, payload) {
            const response = await axios.post("v1/auth/login", payload);
            return dispatch("attempt", response.data.data.auth_token);
        },
        async attempt({ commit, state, dispatch }, payload) {
            if (payload) {
                commit("SET_TOKEN", payload);
            }
            if (!state.token) {
                return;
            }
            setHttpToken(payload);
            return await axios
                .get("v1/auth/me")
                .then(res => {
                    commit("SET_USER", res.data.data);
                })
                .catch(e => {
                    dispatch("clearAuth");
                    throw e;
                });
        },
        async logout({ dispatch }) {
            await axios.post("v1/auth/logout").then(res => {
                dispatch("clearAuth");
            });
        },
        clearAuth({ commit }) {
            commit("SET_TOKEN", null);
            commit("SET_USER", null);
            setHttpToken(null);
        }
    },
    getters: {
        authenticated(state) {
            if (state.token && state.user != null) {
                return true;
            }
            return false;
        },
        user(state) {
            return state.user;
        },
        token(state) {
            return state.token;
        }
    }
};

export default auth;
