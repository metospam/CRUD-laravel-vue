import axios from "axios";

class UserService {

    API_URL = 'http://0.0.0.0/api/v1/users';

    async deleteUser(id) {
        return await axios.delete(this.API_URL + `/${id}`);
    }

    async fetchUsers(page, search = '') {
        return await axios.post(this.API_URL, {page: page, search: search});
    }

    async updateUser(id, name, email, age) {
        return await axios.patch(this.API_URL + `/${id}`, {
            name: name,
            email: email,
            age: age,
        });
    }

    async createUser(name, email, age){
        return await axios.post(this.API_URL + '/create', {
            name: name,
            email: email,
            age: age,
        });
    }
}

export default new UserService();