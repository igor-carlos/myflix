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
    <template v-if="showModalDeleteSerie" name="modalDeleteSerie">
      <transition name="modal">
        <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
              <div class="modal-body">
                <slot name="body">
                  Você tem certeza que deseja remover a série
                  {{ serieToDeleteName }} ?</slot
                >
              </div>
              <div class="modal-footer">
                <button
                  class="modal-button-confirm"
                  @click="confirmSerieDeletion"
                >
                  Confirmar
                </button>
                <button
                  class="modal-button-cancel"
                  @click="cancelSerieDeletion"
                >
                  Cancelar
                </button>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </template>
  </div>
</template>
  </div>
</template>

<script>
export default {
  mounted: function () {
    this.$root.$on("editSerie", (serie) => {
      this.idSerieEdit = serie.id;
      this.buildEditSerieForm(serie);
    });
    this.$root.$on("deleteSerie", (serie) => {
      this.showModalDeleteSerie = true;
      this.serieToDeleteId = serie.id;
      this.serieToDeleteName = serie.nome;
    });
  },
  data: function () {
    return {
      serieName: "",
      selectedCategory: "Categoria",
      selectedStreaming: "Streaming",
      isEdit: false,
      idSerieEdit: 0,
      showModalDeleteSerie: false,
      serieToDeleteId: null,
      serieToDeleteName: "",
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
          if (erro.response.data == "422") {
            notify({
              text: "Necessário no minímo 4 letras para o nome da série",
              theme: "red",
            });
          }
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
      if (this.serieName == "" || this.serieName.length < 4) {
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
          if (response.status == 200) {
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
    cancelSerieDeletion() {
      this.showModalDeleteSerie = false;
    },
    confirmSerieDeletion() {
      if (!this.serieToDeleteId) {
        notify({
          text: "Não foi possível indentificar a série a ser deletada",
          theme: "red",
        });
        return;
      }
      axios
        .delete(`api/v1/serie/${this.serieToDeleteId}`)
        .then((response) => {
          this.showModalDeleteSerie = false;
          if (response.status == 200) {
            notify({
              text: "Exclusão realizada com sucesso !",
              theme: "green",
            });
            this.$emit("reloadlist");
          }
        })
        .catch((error) => {
          this.showModalDeleteSerie = false;
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

.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.8);
  display: table;
  transition: opacity 0.3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.modal-container {
  width: 300px;
  margin: 0px auto;
  padding: 20px 30px;
  background-color: #333;
  color: white;
  border-radius: 8px;
  border: 2px solid rgb(185, 33, 33);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
  transition: all 0.3s ease;
  font-family: "Epilogue", sans-serif;
}

.modal-body {
  margin: 20px 0;
}

.modal-enter {
  opacity: 0;
}

.modal-leave-active {
  opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}

.modal-button-confirm {
  background-color: rgb(68, 163, 68);
  color: white;
  font-family: "Epilogue", sans-serif;
  font-weight: bold;
  padding: 8px 10px 8px 10px;
  border-radius: 5px;
  border: none;
  margin: 5px;
}

.modal-button-confirm:hover {
  background-color: rgb(47, 139, 47);
  cursor: pointer;
}

.modal-button-cancel {
  background-color: rgb(185, 33, 33);
  color: white;
  font-family: "Epilogue", sans-serif;
  font-weight: bold;
  padding: 8px 10px 8px 10px;
  border-radius: 5px;
  border: none;
  margin: 5px;
}

.modal-button-cancel:hover {
  background-color: rgb(141, 38, 38);
  cursor: pointer;
}
</style>