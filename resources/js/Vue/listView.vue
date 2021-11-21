<template>
  <div class="card-container">
    <div v-for="(serie, index) in series" :key="index">
      <list-item :serie="serie" class="serie" />
    </div>
  </div>
</template>

<script>
import listItem from "./listItem";

export default {
  props: ["series"],
  components: {
    listItem,
  },
};
</script>

<style scoped>
.card-container {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
}

.serie {
  background-color: rgb(39, 38, 38);
  padding: 5px;
  margin: 10px;
  border-top: 3px solid rgb(185, 33, 33);
  color: rgb(243, 243, 243);
  font-family: "Epilogue", sans-serif;
}

.serie:hover {
  box-shadow: 0 8px 16px 0 rgba(109, 76, 76, 0.2);
}
</style>