<template>
  <div class="container-form">
    <div class="container-input">
      <input
        v-model="serieName"
        type="text"
        placeholder="Nome da série"
        class="form-control"
        required
      />
    </div>
    <div class="container-others">
      <select v-model="selectedCategory">
        <option disabled selected hidden>Categoria</option>
        <option v-bind:value="'Ação'">Ação</option>
        <option v-bind:value="'Comédia'">Comédia</option>
        <option v-bind:value="'Terror'">Terror</option>
      </select>
      <select v-model="selectedStreaming">
        <option disabled selected hidden>Streaming</option>
        <option v-bind:value="'Netflix'">Netflix</option>
        <option v-bind:value="'Prime Video'">Prime Video</option>
        <option v-bind:value="'Disney Plus'">Disney Plus</option>
      </select>
      <button v-if="!isEdit" @click="createSeries" class="btn-register">
        Cadastrar
      </button>
      <button v-else @click="saveEditSerie" class="btn-save-edit">
        Salvar
      </button>
    </div>
    <notifications class="voerro-notification"></notifications>
  </div>
</template>

<script>
export default {
  mounted: function () {
    this.$root.$on("editSerie", (serie) => {
      this.idSerieEdit = serie.id;
      this.buildEditSerieForm(serie);
    });
  },
  data: function () {
    return {
      serieName: "",
      selectedCategory: "Categoria",
      selectedStreaming: "Streaming",
      isEdit: false,
      idSerieEdit: 0,
    };
  },
  methods: {
    createSeries() {
      if (!this.validateFormData()) return;
      axios
        .post("api/v1/serie", {
          nome: this.serieName,
          categoria: this.selectedCategory,
          streaming: this.selectedStreaming,
        })
        .then((response) => {
          if (response.status == "201") {
            notify({
              text: "Série cadastrada com sucesso !",
              theme: "green",
            });
            this.$emit("reloadlist");
            this.clearInputs();
          }
        })
        .catch((error) => {
          notify({
            text: `Erro: ${error}`,
            theme: "red",
          });
        });
    },
    clearInputs() {
      this.idSerieEdit = null;
      this.isEdit = false;

      this.serieName = "";
      this.selectedCategory = "Categoria";
      this.selectedStreaming = "Streaming";
    },
    validateFormData() {
      if (this.serieName == "") {
        notify({
          text: "Nome da série inválido !",
          theme: "red",
        });
        return false;
      }
      if (["", "Categoria"].includes(this.selectedCategory)) {
        notify({
          text: "Categoria inválida !",
          theme: "red",
        });
        return false;
      }
      if (["", "Streaming"].includes(this.selectedStreaming)) {
        notify({
          text: "Streaming inválida !",
          theme: "red",
        });
        return false;
      }
      return true;
    },
    buildEditSerieForm(serie) {
      if (serie.nome) {
        this.serieName = serie.nome;
      } else {
        this.serieName = "";
      }

      if (serie.categoria) {
        this.selectedCategory = serie.categoria;
      } else {
        this.selectedCategory = "Categoria";
      }

      if (serie.streaming) {
        this.selectedStreaming = serie.streaming;
      } else {
        this.selectedStreaming = "Streaming";
      }

      this.isEdit = true;
    },
    saveEditSerie() {
      if (!this.validateFormData()) return;
      axios
        .patch(`api/v1/serie/${this.idSerieEdit}`, {
          nome: this.serieName,
          categoria: this.selectedCategory,
          streaming: this.selectedStreaming,
        })
        .then((response) => {
          if (response.status == "200") {
            notify({
              text: "Edição realizada com sucesso !",
              theme: "green",
            });
            this.$emit("reloadlist");
            this.clearInputs();
          }
        })
        .catch((error) => {
          notify({
            text: `Erro: ${error}`,
            theme: "red",
          });
        });
    },
  },
};
</script>

<style scoped>
.container-form {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;

  width: 100%;
  justify-content: center;
  text-align: center;

  margin-top: 20px;
  margin-bottom: 30px;
}

.container-input input {
  width: 700px;

  padding: 12px 20px;
  margin: 5px 0;

  box-sizing: border-box;
  border-radius: 5px;

  font-family: "Epilogue", sans-serif;
  font-weight: bold;
}

.btn-register {
  padding: 12px 20px;
  cursor: pointer;

  color: white;
  background-color: rgb(185, 33, 33);

  border-radius: 5px;

  border-color: transparent;

  font-family: "Epilogue", sans-serif;
  font-weight: bold;
  letter-spacing: 1px;
}

.btn-register:hover {
  background-color: rgb(112, 22, 22);
}

.btn-save-edit {
  padding: 12px 20px;
  cursor: pointer;

  color: rgb(185, 33, 33);
  background-color: white;

  border-radius: 5px;

  border-color: transparent;

  font-family: "Epilogue", sans-serif;
  font-weight: bold;
  letter-spacing: 1px;
}

.btn-save-edit:hover {
  background-color: rgb(189, 189, 189);
}

.container-others {
  width: 700px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: row;
}

.container-others select {
  width: 100%;

  padding: 12px 20px;
  margin-right: 5px;

  box-sizing: border-box;
  border-radius: 5px;

  font-family: "Epilogue", sans-serif;
  font-weight: bold;

  color: rgb(112, 112, 112);
}

.container-others select option {
  font-family: "Epilogue", sans-serif;
  font-weight: bold;
  font-size: 16px;

  color: rgb(112, 112, 112);
}

.voerro-notification {
  margin: 0.5rem 0;
  padding: 1rem;

  border-radius: 0.3rem;
  filter: opacity(90%);

  font-family: "Epilogue", sans-serif;
  color: white;
}
</style>