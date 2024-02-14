<script>
import UsersUpdateComponent from "./UsersUpdateComponent.vue";
import UsersDeleteComponent from "./UsersDeleteComponent.vue";
import PaginationComponent from "../PaginationComponent.vue";
import NotFoundComponent from "../NotFoundComponent.vue";
import SearchComponent from "../SearchComponent.vue";
import UserService from "../../services/UserService";

export default {
  components: {SearchComponent, NotFoundComponent, PaginationComponent, UsersDeleteComponent, UsersUpdateComponent},
  data() {
    return {
      users: [],
      totalPages: 1,
      currentPage: 1,
      currentUserId: null,
      search: '',
    };
  },

  mounted() {
    this.getResources(this.currentPage);
  },

  methods: {
    updateUsersData(response) {
      this.users = response.data.items;
      this.totalPages = response.data.total;
    },

    async getResources(page, search = '') {
        UserService.fetchUsers(page, search).then(response => {
        this.updateUsersData(response);
      })
      this.currentPage = page;
    },

    isEditUser(id) {
      return this.currentUserId === id
    },

    resetCurrentUserId() {
        console.log('test');
      this.currentUserId = null
    },

    changeCurrentData(id, name, email, age) {
      this.currentUserId = id;
      let currentName = `edit_${id}`;
      let fullCurrentName = this.$refs[currentName][0];
      fullCurrentName.currentUserName = name;
      fullCurrentName.currentUserEmail = email;
      fullCurrentName.currentUserAge = age;
    },

    setSearch(search) {
      this.search = search;
    },
  },

};
</script>

<template>
  <div class="container">
    <div class="table-wrapper">
      <div class="table-wrapper__header">
        <SearchComponent @set-search="setSearch" :search="search" @get-resources="getResources"
                         :currentPage="currentPage"/>
      </div>

      <template v-if="this.users.length > 0">
        <table>
          <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Mail</th>
            <th>Age</th>
            <th>Edit</th>
            <th>Delete</th>
          </tr>
          </thead>
          <tbody>
          <template v-for="user in users">
            <tr v-if="!isEditUser(user.id)">
              <td style="text-align: center;">{{ user.id }}</td>
              <td>{{ user.name }}</td>
              <td>{{ user.email }}</td>
              <td style="text-align: center; min-width: 70px;">{{ user.age }}</td>
              <td>
                <button @click="changeCurrentData(user.id, user.name, user.email, user.age)" class="btn btn-success">
                  Edit
                </button>
              </td>
              <td>
                <UsersDeleteComponent @get-resources="getResources" :search="search" :currentPage="currentPage"
                                      :user="user"/>
              </td>
            </tr>

            <UsersUpdateComponent @reset-current-user-id="resetCurrentUserId" @get-resources="getResources"
                                  :currentUserId="currentUserId" :user="user" :ref="`edit_${user.id}`" :search="search"
                                  :currentPage="currentPage"/>
          </template>
          </tbody>
        </table>
      </template>

      <template v-else>
        <NotFoundComponent @set-search="setSearch" @get-resources="getResources"/>
      </template>
    </div>

    <PaginationComponent @get-resources="getResources" :search="search" :current-page="currentPage"
                         :total-pages="totalPages"/>
  </div>
</template>
