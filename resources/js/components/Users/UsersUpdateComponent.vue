<script>
import UserService from "../../services/UserService";

export default {
  props: [
    'user',
    'currentPage',
    'search',
    'currentUserId',
  ],

  emits: [
    'resetCurrentUserId',
    'getResources',
  ],

  data() {
    return {
      currentUserName: '',
      currentUserEmail: '',
      currentUserAge: null,
    }
  },

  methods: {
    async editUser(id) {
      await UserService.updateUser(id, this.currentUserName, this.currentUserEmail, this.currentUserAge);
      this.$emit('resetCurrentUserId');
      this.$emit('getResources', this.currentPage, this.search);
    },
  }
}
</script>

<template>
  <tr v-if="currentUserId === user.id" @keyup.enter="editUser(user.id)">
    <td style="text-align: center;">{{ user.id }}</td>
    <td><input type="text" v-model="currentUserName" placeholder="Name"></td>
    <td><input type="email" v-model="currentUserEmail" placeholder="Email"></td>
    <td style="text-align: center; min-width: 60px;"><input type="number" v-model="currentUserAge" placeholder="Age">
    </td>
    <td colspan="2">
      <button @click="editUser(user.id)" class="btn btn-success">
        Update
      </button>
    </td>
  </tr>
</template>

<style scoped>

</style>
