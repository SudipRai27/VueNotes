<template>
  <nav aria-label="Page navigation example">
    <ul class="pagination">
      <!-- Previous link -->
      <li class="page-item" :class="{ disabled: meta.current_page === 1 }">
        <a
          class="page-link"
          href="#"
          aria-label="Previous"
          @click.prevent="switchPage(meta.current_page - 1)"
        >
          <span aria-hidden="true">&laquo;</span>
          <span class="sr-only">Previous</span>
        </a>
      </li>
      <!-- Previous link -->
      <!-- Switch previous section link and page 1 link -->
      <template v-if="section > 1">
        <li class="page-item">
          <a class="page-link" href="#" @click.prevent="switchPage(1)">1</a>
        </li>
        <li class="page-item">
          <a class="page-link" href="#" @click.prevent="goOneSectionBackward"
            >...</a
          >
        </li>
      </template>
      <!-- Page 1 and switch previous section link -->
      <!-- main links according to numbers per section -->
      <li
        class="page-item"
        v-for="(page, index) in pages"
        :key="index"
        :class="{ active: meta.current_page === page }"
      >
        <a class="page-link" href="#" @click.prevent="switchPage(page)">
          {{ page }}
        </a>
      </li>
      <!-- main links according to numbers per section -->
      <!-- Switch next section link and page last link -->
      <template v-if="section < sections">
        <li class="page-item">
          <a class="page-link" href="#" @click.prevent="goOneSectionForward"
            >...</a
          >
        </li>
        <li class="page-item">
          <a
            class="page-link"
            href="#"
            @click.prevent="switchPage(meta.last_page)"
            >{{ meta.last_page }}</a
          >
        </li>
      </template>
      <!-- switch next section link and page last link -->
      <!-- Next Link -->
      <li
        class="page-item"
        :class="{ disabled: meta.current_page === meta.last_page }"
      >
        <a
          class="page-link"
          href="#"
          aria-label="Next"
          @click.prevent="switchPage(meta.current_page + 1)"
        >
          <span aria-hidden="true">&raquo;</span>
          <span class="sr-only">Next</span>
        </a>
      </li>
      <!-- Next Link -->
    </ul>
  </nav>
</template>
<script>
export default {
  name: "Pagination",
  props: {
    meta: {
      type: Object,
      required: true,
    },
    for: {
      type: String,
      default: "default",
    },
  },
  data() {
    return {
      numbersPerSection: 7,
    };
  },
  computed: {
    sections() {
      return Math.ceil(this.meta.last_page / this.numbersPerSection);
    },
    section() {
      return Math.ceil(this.meta.current_page / this.numbersPerSection);
    },
    lastPage() {
      let lastPage =
        (this.section - 1) * this.numbersPerSection + this.numbersPerSection;
      if (this.section === this.sections) {
        lastPage = this.meta.last_page;
      }
      return lastPage;
    },
    pages() {
      return _.range(
        (this.section - 1) * this.numbersPerSection + 1,
        this.lastPage + 1
      );
    },
  },
  mounted() {
    if (this.meta.current_page > this.meta.last_page) {
      this.switchPage(this.meta.last_page);
    }
  },
  methods: {
    switchPage(page) {
      if (this.pageIsOutOfBounds(page) || page === this.meta.current_page) {
        return;
      }
      this.$emit(`${this.for}PageSwitched`, page);
    },
    pageIsOutOfBounds(page) {
      //useful when using with vue router urls
      return page <= 0 || page > this.meta.last_page;
    },
    goOneSectionForward() {
      this.switchPage(this.firstPageOfSection(this.section + 1));
    },
    goOneSectionBackward() {
      this.switchPage(this.firstPageOfSection(this.section - 1));
    },
    firstPageOfSection(section) {
      return (section - 1) * this.numbersPerSection + 1;
    },
  },
};
</script>
