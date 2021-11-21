<template>
  <div class="card-serie">
    <div class="card-content">
      <div class="title-content">
        <p>
          <span class="text-red-small"># {{ serie.id }} &nbsp;&nbsp;</span>
          <strong class="text-bold-medium">{{ serie.nome }}</strong>
        </p>
        <div class="title-itens">
          <div class="edit-serie-status" v-on:click="editSerieStatus(serie)">
            <i class="fas fa-check-square"></i>
          </div>
          <div class="edit-serie" v-on:click="editSerie(serie)">
            <i class="fas fa-edit"></i>
          </div>
          <div class="delete-serie" v-on:click="deleteSerie(serie)">
            <i class="fas fa-trash"></i>
          </div>
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
    deleteSerie(serie) {
      this.$root.$emit("deleteSerie", serie);
    },
    editSerieStatus(serie) {
      this.$root.$emit("editSerieStatus", serie);
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

.title-itens {
  display: flex;
  flex-direction: row;
}

.edit-serie-status {
  margin-right: 10px;
}

.edit-serie-status:hover {
  cursor: pointer;
  color: rgb(137, 187, 137);
}

.edit-serie {
  margin-right: 10px;
}

.edit-serie:hover {
  cursor: pointer;
  color: #70a0ff;
}

.delete-serie:hover {
  cursor: pointer;
  color: #ff8387;
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