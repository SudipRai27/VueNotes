<template>
  <div class="mb-3">
    <div class="card w-100">
      <div class="card-body">
          <ul class="list-inline d-flex" v-if="course.subjects">
                <li class="mr-2 badge badge-dark" v-for="subject in course.subjects" :key="subject.slug + 'id'">{{ subject.name }}</li>
            </ul>
        <h5 class="card-title">{{ course.name }}</h5>
        <p class="card-text">
            <ul class="list-inline">
                <li class="mr-2 badge badge-light">Access: {{ course.free === true ? 'Free' : 'Premium'}}</li>
                <li class="mr-2 badge badge-light">Difficulty: {{ course.formattedDifficulty }}</li>
                <li class="mr-2 badge badge-light">Type: {{ course.formattedType }}</li>
                <li class="mr-2 badge badge-light">Started: {{ course.started === true ? 'Started' : 'Not Started' }}</li>
                <li class="badge badge-light">{{ course.views }} Views</li>
            </ul>
        </p>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "Course",
  props: ["course"],
};
</script>
