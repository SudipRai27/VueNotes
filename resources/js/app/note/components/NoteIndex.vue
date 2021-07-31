<template>
  <div class="container mt-5">
    <h4>Welcome to your notes</h4>
    <!-- <Datatable endpoint="v1/datatable/note"></Datatable> -->
    <NoteDataTable></NoteDataTable>
  </div>
</template>
<script>
import Datatable from "@/app/datatable/components/Datatable";
import NoteDataTable from "./NoteDataTable.vue";
export default {
  name: "NoteIndex",
  components: {
    Datatable,
    NoteDataTable,
  },
};
</script>
