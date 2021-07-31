<template>
  <div class="container mt-5">
    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-between">
          <span>
            {{ response.table }}
          </span>
          <a
            @click.prevent="creating.active = !creating.active"
            href="#"
            v-if="response.allow.creation"
            class="btn btn-primary pull-right"
            >{{ creating.active ? "Cancel" : "New record" }}
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="card card-body bg-light mb-4" v-if="creating.active">
          <form class="form-horizontal" @submit.prevent="store">
            <div
              class="form-group"
              v-for="(column, index) in response.updateable"
              :key="index"
            >
              <label class="col-md-3 control-label" :for="column">{{
                column
              }}</label>
              <div class="col-md-6">
                <input
                  type="text"
                  class="form-control"
                  :id="column"
                  v-model="creating.form[column]"
                />
                <span v-if="creating.errors[column]">
                  <strong style="color: red">{{
                    creating.errors[column][0]
                  }}</strong>
                </span>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-6 col-md-offset-3">
                <button type="submit" class="btn btn-success">Create</button>
              </div>
            </div>
          </form>
        </div>
        <div class="row">
          <div class="form-group col-md-10">
            <label for="filter">Quick search current result</label>
            <input
              type="text"
              id="filter"
              class="form-control"
              v-model="quickSearchQuery"
            />
          </div>
          <div class="form-group col-md-2">
            <label for="limit">Display Records</label>
            <select
              id="limit"
              class="form-control"
              v-model="limit"
              @change="getRecords"
            >
              <option value="10">10</option>
              <option value="50">50</option>
              <option value="100">100</option>
              <option value="">all</option>
            </select>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table">
            <thead class="thead-dark">
              <tr>
                <th
                  scope="col"
                  v-for="(column, index) in response.displayable"
                  :key="index"
                >
                  <span class="sortable" @click="sortBy(column)">
                    {{ column }}
                  </span>
                  <div
                    class="arrow"
                    @click="sortBy(column)"
                    :class="{
                      'arrow-asc': sort.order === 'asc',
                      'arrow-desc': sort.order === 'desc',
                    }"
                    v-if="sort.key === column"
                  ></div>
                </th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(record, index) in filteredRecords" :key="index">
                <td v-for="(columnValue, column) in record" :key="column">
                  <template
                    v-if="editing.id === record.id && isUpdateable(column)"
                  >
                    <div
                      class="form-group"
                      :class="{ 'has-error': editing.errors[column] }"
                    >
                      <input
                        v-model="editing.form[column]"
                        type="text"
                        class="form-control"
                      />
                      <span
                        class="help-block"
                        v-if="editing.errors[column]"
                        style="color: red"
                      >
                        {{ editing.errors[column][0] }}</span
                      >
                    </div>
                  </template>
                  <template v-else>
                    {{ columnValue }}
                  </template>
                </td>
                <td>
                  <a
                    href="#"
                    @click.prevent="edit(record)"
                    v-if="editing.id !== record.id"
                    >Edit</a
                  >
                  <template v-if="editing.id === record.id">
                    <a href="#" @click.prevent="editing.id = null"> Cancel</a>
                    <br />
                    <a href="#" @click.prevent="update">Update</a>
                  </template>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import queryString from "query-string";
export default {
  name: "NoteDatatable",
  props: {
    endpoint: {
      required: true,
      type: String,
    },
  },
  data() {
    return {
      response: {
        table: "",
        displayable: [],
        updateable: [],
        records: [],
        allow: {},
      },
      sort: {
        key: "id",
        order: "asc",
      },
      quickSearchQuery: "",
      limit: 10,
      editing: {
        id: null,
        form: {},
        errors: [],
      },
      creating: {
        active: false,
        form: {},
        errors: [],
      },
    };
  },
  mounted() {
    this.getRecords();
  },
  methods: {
    getRecords() {
      return axios
        .get(`${this.endpoint}?${this.getQueryParams()}`)
        .then((res) => {
          this.response = res.data.data;
        })
        .catch((err) => {
          console.log(err.response);
        });
    },
    getQueryParams() {
      return queryString.stringify({
        limit: this.limit,
      });
    },
    sortBy(column) {
      this.sort.key = column;
      this.sort.order = this.sort.order === "asc" ? "desc" : "asc";
    },
    edit(record) {
      this.editing.errors = [];
      this.editing.id = record.id;
      this.editing.form = _.pick(record, this.response.updateable);
    },
    isUpdateable(column) {
      return this.response.updateable.includes(column);
    },
    update() {
      axios
        .put(`${this.endpoint}/${this.editing.id}`, this.editing.form)
        .then((res) => {
          this.getRecords().then(() => {
            this.$displaySuccess("Updated Successfully");
            this.editing.id = null;
            this.editing.form = {};
          });
        })
        .catch((e) => {
          if (e.response && e.response.status === 422) {
            this.editing.errors = e.response.data.errors;
          }
        });
    },
    store() {
      return axios
        .post(`${this.endpoint}`, this.creating.form)
        .then((res) => {
          this.$displaySuccess("Created Successfully");
          this.getRecords().then(() => {
            this.creating.active = false;
            this.creating.form = {};
            this.creating.errors = [];
          });
        })
        .catch((e) => {
          if (e.response && e.response.status === 422) {
            this.creating.errors = e.response.data.errors;
          }
        });
    },
  },
  computed: {
    filteredRecords() {
      let data = this.response.records;
      data = data.filter((row) => {
        return Object.keys(row).some((key) => {
          return (
            String(row[key])
              .toLowerCase()
              .indexOf(this.quickSearchQuery.toLowerCase()) > -1
          );
        });
      });

      if (this.sort.key) {
        data = _.orderBy(
          data,
          (i) => {
            let value = i[this.sort.key];
            if (!isNaN(parseFloat(value))) {
              return parseFloat(value);
            }
            return String(i[this.sort.key].toLowerCase());
          },
          this.sort.order
        );
      }

      return data;
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
