<script>
import UserService from "../../services/UserService";

export default {
  data() {
    return {
      name: '',
      email: '',
      age: null,
      errors: {
        name: false,
        email: false,
        age: false,
      },
    }
  },

  methods: {
    async createUser() {
      if (!this.name || !this.email || !this.age) {
        this.errors.name = !this.name;
        this.errors.email = !this.validateEmail(this.email);
        this.errors.age = !this.age;
        return;
      }

      this.errors.name = false;
      this.errors.email = false;
      this.errors.age = false;

      await UserService.createUser(this.name, this.email, this.age);
      this.name = '';
      this.email = '';
      this.age = null;
    },

    handleErrors() {
      this.errors.name = !this.name.trim();
      this.errors.email = !this.validateEmail(this.email);
      this.errors.age = !this.age;
    },

    validateEmail(email) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(email);
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
      <input autocomplete="off" type="number" id="age" name="age" placeholder="Age" v-model="age" @input="handleErrors">
    </div>

    <button type="submit" class="custom-form__btn btn btn-success">
      Create
    </button>
  </form>
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
</style>
