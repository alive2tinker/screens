import axios from "axios";
import Store from "../index";
import config from '../../config';

export default () => {
    var token = Store.state.auth.user != null ? Store.state.auth.user.accessToken : '';
    return axios.create({
        baseURL: process.env.NODE_ENV === 'production' ? config.productionURL : config.devURL,
        headers: {
            Authorization: "Bearer " + token
        }
    });
};
