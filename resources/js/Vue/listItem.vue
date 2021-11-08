<template>
  <div class="card-serie">
    <div class="card-content">
      <div class="title-content">
        <p>
          <span class="text-red-small"># {{ serie.id }} &nbsp;&nbsp;</span>
          <strong class="text-bold-medium">{{ serie.nome }}</strong>
        </p>
        <div class="edit-serie" v-on:click="editSerie(serie)">
          <i class="fas fa-edit"></i>
        </div>
      </div>
      <p class="streaming" v-if="serie.streaming">
        <i class="fas fa-tv"></i>
        {{ serie.streaming }}
      </p>
      <p class="categoria" v-if="serie.categoria">
        <i class="fas fa-list"></i>
        {{ serie.categoria }}
      </p>
      <div class="card-serie-status">
        <p class="serie-status">
          <i
            v-if="serie.status == 'não-assistido'"
            class="fas fa-eye-slash"
          ></i>
          <i v-else class="fas fa-eye"></i>
          {{ serie.status }}
        </p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["serie"],
  methods: {
    editSerie(serie) {
      this.$root.$emit("editSerie", serie);
    },
  },
};
</script>

<style scoped>
.card-serie {
  width: 400px;
  height: 200px;
  border-radius: 8px;
}

.card-content {
  height: 90%;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.title-content {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
}

.edit-serie {
  display: flex;
  justify-content: right;

  height: 30px;
  width: 30px;
  border-radius: 10px;
}

.edit-serie:hover {
  cursor: pointer;
}

.text-bold-medium {
  font-weight: bold;
  font-size: 18px;
}

.text-red-small {
  color: rgb(185, 33, 33);
  font-size: 12px;
}

.card-content {
  margin: 10px;
}

.card-serie-status {
  text-align: right;
  color: rgb(175, 175, 175);
  font-weight: bold;
}

.streaming {
  text-align: right;
  color: rgb(175, 175, 175);
  font-weight: bold;
}

.categoria {
  text-align: right;
  color: rgb(175, 175, 175);
  font-weight: bold;
}
</style>