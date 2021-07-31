<template>
  <div class="container mt-5">
    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-between">
          <span> Notes </span>
        </div>
      </div>
      <div class="mt-4 container row">
        <div class="col-sm-3">
          <label>Select per page</label>
          <select v-model="params.perPage" class="form-control">
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
        </div>
        <div class="col-sm-6 offset-sm-3">
          <label>Search </label>
          <input type="text" class="form-control" v-model="search" />
        </div>
      </div>
      <div class="card-body">
        <button
          class="btn btn-danger"
          v-if="selectedIds.length"
          @click.prevent="removeNotes"
        >
          Delete Selected
        </button>
        <div class="table-responsive mt-2">
          <table class="table">
            <thead class="thead-dark">
              <tr>
                <th>
                  <input type="checkbox" v-model="bulkDelete" />
                  <span class="sortable" @click="sortBy('id')">ID</span>
                  <div
                    class="arrow"
                    @click="sortBy('id')"
                    :class="{
                      'arrow-asc': params.sortDirection === 'asc',
                      'arrow-desc': params.sortDirection === 'desc',
                    }"
                    v-if="params.sortBy === 'id'"
                  ></div>
                </th>
                <th>
                  <span class="sortable" @click="sortBy('title')">Title</span>
                  <div
                    class="arrow"
                    @click="sortBy('title')"
                    :class="{
                      'arrow-asc': params.sortDirection === 'asc',
                      'arrow-desc': params.sortDirection === 'desc',
                    }"
                    v-if="params.sortBy === 'title'"
                  ></div>
                </th>
                <th>
                  <span class="sortable" @click="sortBy('body')">Body</span>
                  <div
                    class="arrow"
                    @click="sortBy('body')"
                    :class="{
                      'arrow-asc': params.sortDirection === 'asc',
                      'arrow-desc': params.sortDirection === 'desc',
                    }"
                    v-if="params.sortBy === 'body'"
                  ></div>
                </th>
                <th>
                  <span class="sortable" @click="sortBy('created_at')"
                    >Created At</span
                  >
                  <div
                    class="arrow"
                    @click="sortBy('created_at')"
                    :class="{
                      'arrow-asc': params.sortDirection === 'asc',
                      'arrow-desc': params.sortDirection === 'desc',
                    }"
                    v-if="params.sortBy === 'created_at'"
                  ></div>
                </th>
              </tr>
            </thead>
            <tbody>
              <template v-if="notes.length">
                <tr v-for="record in notes" :key="record.id">
                  <td>
                    <input
                      type="checkbox"
                      v-model="selectedIds"
                      :value="record.id"
                    />
                    {{ record.id }}
                  </td>
                  <td>{{ record.title }}</td>
                  <td>{{ record.body }}</td>
                  <td>{{ record.created_at }}</td>
                </tr>
              </template>
              <template v-else>
                <tr>
                  <td colspan="5">No records available</td>
                </tr>
              </template>
            </tbody>
          </table>
          <!-- Without using query in urls -->
          <!-- <Pagination
            :meta="meta"
            for="notesTable"
            @notesTablePageSwitched="getRecords"
          ></Pagination> -->
          <Pagination
            v-if="meta.current_page && notes.length"
            :meta="meta"
            for="notesTable"
            @notesTablePageSwitched="switchPage"
          ></Pagination>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Pagination from "@/app/components/Pagination";
import _ from "lodash";

export default {
  name: "NoteDataTable",
  components: {
    Pagination,
  },
  data() {
    return {
      notes: [],
      meta: {},
      params: {
        page: 1,
        // perPage : this.$route.query.perPage ?? 10 (If want to get perPage from url , similar with others as well)
        perPage: 10,
        sortBy: "created_at",
        sortDirection: "desc",
      },
      search: "", // can put the search inside params if needed
      selectedIds: [],
      bulkDelete: false,
    };
  },
  watch: {
    "$route.query"(query) {
      this.getRecords(query.page);
    },
    params: {
      handler() {
        this.getRecords();
      },
      deep: true,
    },
    search: _.debounce(function () {
      if (this.params.page > 1) {
        this.params.page = 1;
      }
      this.getRecords();
    }, 600),
    bulkDelete(val) {
      if (val) {
        this.notes.forEach((note) => {
          this.selectedIds.push(note.id);
        });
      } else {
        this.selectedIds = [];
      }
    },
  },
  mounted() {
    this.getRecords();
  },
  methods: {
    sortBy(column) {
      this.params.sortBy = column;
      this.params.sortDirection =
        this.params.sortDirection === "asc" ? "desc" : "asc";
    },
    // getRecords(page = this.$route.query.page) { for using the url's page number
    getRecords() {
      //remove object keys having empty values
      const params = {
        search: this.search,
        ...this.params,
      };
      Object.keys(params).forEach((key) => {
        if (params[key] === "") {
          delete params[key];
        }
      });
      return axios
        .get(`v1/note`, {
          params: params,
        })
        .then((res) => {
          this.notes = res.data.data;
          this.meta = res.data.meta;
        })
        .catch((err) => {});
    },
    switchPage(page) {
      this.params.page = page;
      this.getRecords();
      // to push query string to the url
      //   this.params.page = page;
      //   this.$router.replace({
      //     name: "note",
      //     query: this.params,
      //   });
    },
    removeNotes() {
      axios
        .delete(`v1/note/${this.selectedIds}`)
        .then((res) => {
          this.$displaySuccess(res.data.message);
          this.selectedIds = [];
          if (this.bulkDelete) {
            this.bulkDelete = false;
          }
          this.getRecords();
        })
        .catch((err) => {
          this.$displayError("Some errors occured");
        });
    },
  },
};
</script>
<style scoped>
.sortable {
  cursor: pointer;
}
.arrow {
  display: inline-block;
  vertical-align: middle;
  cursor: pointer;
  width: 0;
  height: 0;
  margin-left: 2px;
  opacity: 0.6;
}

.arrow-asc {
  border-left: 4px solid transparent;
  border-right: 4px solid transparent;
  border-bottom: 4px solid rgb(250, 250, 250);
}

.arrow-desc {
  border-left: 4px solid transparent;
  border-right: 4px solid transparent;
  border-top: 4px solid rgb(250, 250, 250);
}
</style>

