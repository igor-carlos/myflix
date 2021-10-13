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
  justify-content: left;
  flex-wrap: wrap;
}

.serie {
  background-color: rgb(39, 38, 38);

  padding: 5px;
  margin: 10px;

  box-shadow: 0 4px 8px 0 black;
  transition: 0.3s;

  border-top: 3px solid rgb(185, 33, 33);

  color: rgb(243, 243, 243);

  cursor: pointer;
  font-family: "Epilogue", sans-serif;
}

.serie:hover {
  box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
}
</style>