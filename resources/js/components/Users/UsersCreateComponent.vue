<script>
import UserService from "../../services/UserService";

export default {
    data() {
        return {
            name: '',
            email: '',
            age: null,
            message: '',
            isError: false,
            errors: {
                name: false,
                email: false,
                age: false,
            },
        }
    },

    methods: {
        async createUser() {
            if (this.validateUserData()) {
                await UserService.createUser(this.name, this.email, this.age)
                    .then(response => {
                        if (response.status === 200) {
                            this.message = 'User successfully created';
                            this.isError = false;
                            this.clearForm();
                        }
                    })
                    .catch(error => {
                        console.log(error);
                        if (error.response.status === 400) {
                            let errors = error.response.data;

                            for (const key in errors) {
                                if (errors.hasOwnProperty(key) && errors[key].length > 0) {
                                    this.message = errors[key][0];
                                    this.errors[key] = true;
                                    this.isError = true;
                                    break;
                                }
                            }
                        }
                    });
            }
        },

        validateUserData() {
            let isValid = true;
            this.errors.name = !this.name.trim();
            this.errors.email = !this.validateEmail(this.email);
            this.errors.age = !this.age;
            if (this.errors.name || this.errors.email || this.errors.age) {
                isValid = false;
            }
            return isValid;
        },

        handleErrors() {
            this.errors.name = !this.name.trim();
            this.errors.email = !this.validateEmail(this.email);
            this.errors.age = !this.age;
        },

        clearForm() {
            this.name = '';
            this.email = '';
            this.age = null;
        },

        validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        },

        resetMessage(){
            this.message = '';
        },
    },
}
</script>

<template>
    <form class="custom-form" @submit.prevent="createUser">
        <h1 class="custom-form__title">
            Create User
        </h1>

        <div class="custom-form__input" :class="{ 'error': errors.name }">
            <label for="name" hidden>Name</label>
            <input autocomplete="off" type="text" id="name" name="name" placeholder="Name" v-model="name"
                   @input="handleErrors">
        </div>
        <div class="custom-form__input" :class="{ 'error': errors.email }">
            <label for="email" hidden>Email</label>
            <input autocomplete="off" type="email" id="email" name="email" placeholder="Email" v-model="email"
                   @input="handleErrors">
        </div>
        <div class="custom-form__input" :class="{ 'error': errors.age }">
            <label for="age" hidden>Age</label>
            <input autocomplete="off" type="number" id="age" name="age" placeholder="Age" v-model="age"
                   @input="handleErrors">
        </div>

        <button type="submit" class="custom-form__btn btn btn-success">
            Create
        </button>
    </form>

    <div class="popup-message" v-show="message.length > 0">
        <span class="popup-message__content" :class="{ 'error': isError }">{{ message }}</span>
        <button class="popup-message__close btn btn-success" @click="resetMessage">
            Accept
        </button>
    </div>
</template>

<style scoped>
.custom-form {
    margin-top: 50px;
    max-width: 500px;
    margin-inline: auto;
    text-align: center;
}

.custom-form__title {
    margin-bottom: 20px;
    text-align: center;
    font-weight: 500;
    font-size: 20px;
    color: #2d9164;
}

.custom-form__input {
    margin-bottom: 10px;
}

.custom-form__input input {
    text-align: start;
    height: 45px
}

.custom-form__input.error input {
    border: 1px solid #d92c3f;
}

.custom-form__btn {
    margin-top: 20px;
    max-width: 300px;
    margin-inline: auto;
}

.popup-message{
    max-width: 500px;
    position: fixed;
    top: 50%;
    transform: translate(-50%, -50%);
    border-radius: 2px;
    padding: 15px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, .04), 0 1px 2px rgba(0, 0, 0, .06);
    background-color: rgba(28, 27, 27, 0.7);
    text-align: center;
    display: flex;
    flex-direction: column;
    row-gap: 10px;
    left: 50%;
}

.popup-message__content{
    color: #2d9164;
    font-weight: 700;
}

.popup-message__content.error{
    color: #d92c3f;
}
</style>
