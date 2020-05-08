import axios from "axios";
import Store from "../index";

export default () => {
    var token = Store.state.auth.user != null ? Store.state.auth.user.accessToken : '';
    return axios.create({
        baseURL: "https://vue_screens.test/",
        headers: {
            Authorization: "Bearer " + token
        }
    });
};
