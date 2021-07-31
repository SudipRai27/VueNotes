<template>
  <div class="filters">
    <p v-if="filtersInUse">
      <a href="#" @click.prevent="clearFilters">Clear all filters</a>
    </p>
    <div class="list-group" v-for="(map, key) in filters" :key="key">
      <div class="mb-4">
        <a
          href="#"
          class="list-group-item"
          :class="{ active: selectedFilters[key] === value }"
          v-for="(filter, value) in map"
          :key="value"
          @click.prevent="activateFilter(key, value)"
          disabled
        >
          {{ filter }}
        </a>
        <a
          href="#"
          class="list-group-item list-group-item-info"
          v-if="selectedFilters[key]"
          @click.prevent="clearFilter(key)"
          >&times;Clear this filter</a
        >
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "Filters",
  props: ["endpoint"],
  data() {
    return {
      filters: {},
      selectedFilters: _.omit(this.$route.query, ["page"]), //lodash to remove page from query string object
    };
  },
  mounted() {
    this.getFilters();
  },
  computed: {
    filtersInUse() {
      return !_.isEmpty(this.selectedFilters);
    },
  },
  methods: {
    getFilters() {
      axios
        .get(`${this.endpoint}`)
        .then((res) => {
          this.filters = res.data.data;
        })
        .catch((err) => {
          this.$displayError("Oops some errors occured");
        });
    },
    activateFilter(key, value) {
      //lodash check to see if the selected filters already has same kwy value pair
      //   [key] for setting dynamic keys in es6
      if (_.some([this.selectedFilters], { [key]: value })) {
        return;
      }
      // to make vue reactive we need to change the whole object instead of doing this.selectedFilters[key]=value
      this.selectedFilters = Object.assign({}, this.selectedFilters, {
        [key]: value,
      });
      this.updateQueryString();
    },
    clearFilter(key) {
      this.selectedFilters = _.omit(this.selectedFilters, key);
      this.updateQueryString();
    },
    clearFilters() {
      this.selectedFilters = {};
      this.updateQueryString();
    },
    updateQueryString() {
      this.$router.replace({
        query: {
          page: 1,
          ...this.selectedFilters,
        },
      });
    },
  },
};
</script>
