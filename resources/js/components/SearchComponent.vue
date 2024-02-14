<script>
export default {
  props: ['getResources', 'search', 'setSearch'],

  emits: [
    'getResources',
    'setSearch'
  ],

  methods:{
    updateSearch(event){
      let searchValue = event.target.value;
      this.$emit('getResources', 1, searchValue)
      this.$emit('setSearch', searchValue);
    }
  },
}
</script>

<template>
  <div class="search-action">
    <input type="search" :value="search" placeholder="Search" @input="updateSearch">
  </div>
</template>

<style scoped>

</style>