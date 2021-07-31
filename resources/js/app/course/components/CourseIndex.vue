<template>
  <div class="mt-4">
    <h3>Courses</h3>
    <div class="row">
      <div class="col-sm-3">
        <Filters endpoint="v1/course/filters"></Filters>
      </div>
      <div class="col-sm-9">
        <div class="card">
          <div class="card-header">Courses</div>
          <div class="card-body">
            <template v-if="courses.length">
              <Course
                v-for="course in courses"
                :course="course"
                :key="course.id"
              ></Course>
              <div class="ml-3">
                <Pagination
                  v-if="meta.current_page && courses.length"
                  :meta="meta"
                  for="coursesIndex"
                  @coursesIndexPageSwitched="switchPage"
                ></Pagination>
              </div>
            </template>
            <template v-else> No results found </template>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Filters from "./partials/Filters.vue";
import Course from "./partials/Course.vue";
import Pagination from "@/app/components/Pagination.vue";
export default {
  name: "CourseIndex",
  data() {
    return {
      courses: [],
      meta: {},
    };
  },
  components: {
    Course,
    Pagination,
    Filters,
  },
  mounted() {
    this.getCourses();
  },
  watch: {
    "$route.query": {
      handler(query) {
        this.getCourses(1, query);
      },
      deep: true,
    },
  },
  methods: {
    getCourses(page = this.$route.query.page, query = this.$route.query) {
      axios
        .get("v1/course", {
          params: {
            page,
            ...query,
          },
        })
        .then((res) => {
          this.courses = res.data.data;
          this.meta = res.data.meta;
        })
        .catch((err) => {
          this.$displayError("Oops some errors occured");
        });
    },
    switchPage(page) {
      this.$router.replace({
        query: Object.assign({}, this.$route.query, { page }),
      });
      // to make vue reactive
    },
  },
};
</script>
