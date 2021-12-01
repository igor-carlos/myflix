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
    <template v-if="showModalEditSerieStatus" name="modalEditSerieStatus">
      <transition name="modal">
        <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
              <div class="modal-body">
                <slot name="body">
                  Você tem certeza que alterar o status da série
                  {{ serieToEditStatusName }} para
                  {{
                    serieToEditStatusStatus === "assistido"
                      ? "não-assistido"
                      : "assistido"
                  }}
                  ?</slot
                >
              </div>
              <div class="modal-footer">
                <button
                  class="modal-button-confirm"
                  @click="confirmSerieStatusEdit"
                >
                  Confirmar
                </button>
                <button
                  class="modal-button-cancel"
                  @click="cancelSerieStatusEdit"
                >
                  Cancelar
                </button>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </template>
    <template v-if="showModalEditSerieEpisodeo" name="modalEditSerieEpisodeo">
      <transition name="modal">
        <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-lg">
              <div class="modal-body">
                <div class="modal-body-temporadas">
                  <div v-if="serieTemporadas.length > 0">
                    <h3>Temporadas (TP) e episódios (EP):</h3>
                    <br /><br />
                    <div
                      v-for="temporada in serieTemporadas"
                      :key="temporada.id"
                    >
                      <p class="temporada-p">
                        TP{{ temporada.numero }} - {{ temporada.nome }}
                      </p>
                      <div v-if="temporada.episodios.length > 0">
                        <div
                          v-for="episodio in temporada.episodios"
                          :key="episodio.id"
                        >
                          <p class="episodio-p">
                            EP{{ episodio.numero }} - {{ episodio.nome }}
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div v-else>
                    <p>Não há nenhuma temporada cadastrada para essa série !</p>
                  </div>
                </div>
                <p class="title-modal-body-form">
                  Cadastro de temporadas e séries
                </p>
                <div class="modal-body-form-temporada-episodio">
                  <div class="create-temporada">
                    <span class="span-input">Temporada: </span>
                    <input
                      class="input-tmep"
                      id="create-temporada-numero-input"
                      v-model="createTemporadaNumeroInputValue"
                      type="number"
                      min="1"
                      placeholder="Número"
                      required
                    />
                    <input
                      class="input-tmep"
                      id="create-temporada-nome-input"
                      v-model="createTemporadaNomeInputValue"
                      type="text"
                      placeholder="Nome"
                      required
                    />
                    <button
                      @click="createSeasonForSerie"
                      class="btn-input-tmep-positive"
                    >
                      <i class="fas fa-plus-circle"></i>
                    </button>
                  </div>
                  <div
                    v-if="serieTemporadas.length > 0"
                    class="create-episodio"
                  >
                    <span class="span-input">Episódio: </span>
                    <select
                      class="select-tmep"
                      id="create-episodio-temporada-numero-input"
                      v-model="serieTemporadasSelectedId"
                      type="number"
                      min="1"
                      placeholder="Número da temporada"
                      required
                    >
                      <option
                        v-for="temporada in serieTemporadas"
                        v-bind:value="temporada.id"
                        :key="temporada.id"
                      >
                        {{ temporada.numero }} - {{ temporada.nome }}
                      </option>
                    </select>
                    <input
                      class="input-tmep"
                      id="create-episodio-numero-input"
                      v-model="createEpisodioNumeroInputValue"
                      type="number"
                      min="1"
                      placeholder="Número"
                      required
                    />
                    <input
                      class="input-tmep"
                      id="create-episodio-nome-input"
                      v-model="createEpisodioNomeInputValue"
                      type="text"
                      placeholder="Nome"
                      required
                    />
                    <button
                      @click="createEpisodeForSerie"
                      class="btn-input-tmep-positive"
                    >
                      <i class="fas fa-plus-circle"></i>
                    </button>
                  </div>
                </div>
                <br />
                <p class="title-modal-body-form">
                  Edição e exclusão de temporadas e séries
                </p>
                <div class="modal-body-form-temporada-episodio">
                  <div class="create-temporada">
                    <span class="span-input">Temporada: </span>
                    <select
                      class="select-tmep"
                      v-model="temporadaHandle"
                      required
                    >
                      <option
                        v-for="temporada in serieTemporadas"
                        v-bind:value="temporada"
                        :key="temporada.id"
                      >
                        TP{{ temporada.numero }} - {{ temporada.nome }}
                      </option>
                    </select>
                    <button
                      @click="deleteTemporada"
                      class="btn-input-tmep-negative"
                    >
                      <i class="fas fa-minus-circle"></i>
                    </button>
                    <button @click="editTemporada" class="btn-input-tmep-edit">
                      <i class="fas fa-edit"></i>
                    </button>
                  </div>
                  <div
                    v-if="serieTemporadas.length > 0"
                    class="create-episodio"
                  >
                    <span class="span-input">Episódio: </span>
                    <select
                      class="select-tmep"
                      v-model="episodioHandle"
                      required
                    >
                      <option
                        v-for="episodio in serieEpisodios"
                        v-bind:value="episodio"
                        :key="episodio.id"
                      >
                        {{ episodio.nomeToShow }}
                      </option>
                    </select>
                    <button
                      @click="deleteEpisodio"
                      class="btn-input-tmep-negative"
                    >
                      <i class="fas fa-minus-circle"></i>
                    </button>
                    <button @click="editEpisodio" class="btn-input-tmep-edit">
                      <i class="fas fa-edit"></i>
                    </button>
                  </div>
                </div>
                <br />
                <p class="title-modal-body-form">Ultimo episódio assistido</p>
                <div class="modal-body-form-temporada-episodio">
                  <div class="create-temporada">
                    <span class="span-input">Episódio: </span>
                    <select
                      class="select-tmep"
                      v-model="lastEpisodioHandle"
                      required
                    >
                      <option
                        v-for="episodio in serieEpisodios"
                        v-bind:value="episodio"
                        :key="episodio.id"
                      >
                        {{ episodio.nomeToShow }}
                      </option>
                    </select>
                    <button
                      @click="lastEpisodeWhatched"
                      class="btn-input-tmep-update"
                    >
                      <i class="fas fa-check-circle"></i>
                    </button>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button
                  class="modal-button-cancel"
                  @click="cancelSerieEpisodeoEdit"
                >
                  Fechar
                </button>
              </div>
              <span v-if="messageTmep.length > 0" class="span-warning">{{
                messageTmep
              }}</span>
            </div>
          </div>
        </div>
      </transition>
    </template>
  </div>
</template>

<script>
import axios from "axios";
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
    this.$root.$on("editSerieStatus", (serie) => {
      this.showModalEditSerieStatus = true;
      this.serieToEditStatusId = serie.id;
      this.serieToEditStatusName = serie.nome;
      this.serieToEditStatusStatus = serie.status;
    });
    this.$root.$on("editSerieEpisodeo", (serie) => {
      this.showModalEditSerieEpisodeo = true;
      this.serieForEpisodioEditionId = serie.id;
      this.serieTemporadas = serie.temporadas;
      this.serieEpisodios = [];
      for (var i = 0; i < this.serieTemporadas.length; i++) {
        for (var y = 0; y < this.serieTemporadas[i].episodios.length; y++) {
          this.serieEpisodios.push({
            serieId: serie.id,
            nomeToShow: `TP${("0" + this.serieTemporadas[i].numero).slice(
              -2
            )}EP${this.serieTemporadas[i].episodios[y].numero}`,
            epProps: this.serieTemporadas[i].episodios[y],
          });
        }
      }
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
      showModalEditSerieStatus: false,
      serieToEditStatusId: null,
      serieToEditStatusName: "",
      serieToEditStatusStatus: "",
      messageTmep: "",
      showModalEditSerieEpisodeo: false,
      serieForEpisodioEditionId: null,
      serieTemporadas: null,
      serieTemporadasSelectedId: null,
      createTemporadaNumeroInputValue: null,
      createTemporadaNomeInputValue: null,
      createEpisodioNumeroTemporadaInputValue: null,
      createEpisodioNumeroInputValue: null,
      createEpisodioNomeInputValue: null,
      temporadaHandle: null,
      episodioHandle: null,
      serieEpisodios: null,
      lastEpisodioHandle: null,
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
            text: error,
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
      if (this.serieName == "" || this.serieName.length <= 4) {
        notify({
          text: "Nome da série inválido (precisa ser maior que 4 caracteres)!",
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
      serie.nome ? (this.serieName = serie.nome) : (this.serieName = "");
      serie.categoria
        ? (this.selectedCategory = serie.categoria)
        : (this.selectedCategory = "Categoria");
      serie.streaming
        ? (this.selectedStreaming = serie.streaming)
        : (this.selectedStreaming = "Streaming");
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
            text: error,
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
            text: error,
            theme: "red",
          });
        });
    },
    cancelSerieStatusEdit() {
      this.showModalEditSerieStatus = false;
    },
    confirmSerieStatusEdit() {
      if (!this.serieToEditStatusId) {
        notify({
          text: "Não foi possível indentificar a série a ser editada",
          theme: "red",
        });
        return;
      }
      axios
        .put(`api/v1/serie/${this.serieToEditStatusId}/status`)
        .then((response) => {
          this.showModalEditSerieStatus = false;
          if (response.status == 200) {
            notify({
              text: "Status alterado !",
              theme: "green",
            });
            this.$emit("reloadlist");
          }
        })
        .catch((error) => {
          this.showModalEditSerieStatus = false;
          notify({
            text: error,
            theme: "red",
          });
        });
    },
    setMessageTmep(message) {
      this.messageTmep = message;
      setTimeout(() => (this.messageTmep = ""), 3000);
    },
    cancelSerieEpisodeoEdit() {
      this.showModalEditSerieEpisodeo = false;
    },
    createSeasonForSerie() {
      if (!this.serieForEpisodioEditionId) {
        this.setMessageTmep("Não foi possível achar a série a ser editada");
        return;
      }
      if (
        !this.createTemporadaNumeroInputValue &&
        this.createTemporadaNumeroInputValue > 0
      ) {
        this.setMessageTmep(
          "É necessário fornecer um número válido para criar uma temporada"
        );
        return;
      }
      if (!this.createTemporadaNomeInputValue) {
        this.setMessageTmep(
          "É necessário fornecer um nome para criar uma temporada"
        );
        return;
      }
      for (var i = 0; i < this.serieTemporadas.length; i++) {
        if (
          this.serieTemporadas[i].numero == this.createTemporadaNumeroInputValue
        ) {
          this.setMessageTmep("O número dessa temporada já existe");
          return;
        }
      }
      axios
        .post("api/v1/temporada", {
          serie_id: this.serieForEpisodioEditionId,
          numero: this.createTemporadaNumeroInputValue,
          nome: this.createTemporadaNomeInputValue,
        })
        .then((response) => {
          if (response.status == "201") {
            notify({
              text: "Temporada cadastrada com sucesso !",
              theme: "green",
            });
            this.$emit("reloadlist");
            this.showModalEditSerieEpisodeo = false;
            this.serieForEpisodioEditionId = null;
            this.createTemporadaNumeroInputValue = null;
            this.createTemporadaNomeInputValue = null;
          } else {
            notify({
              text: "Algo de inesperado ocorreu !",
              theme: "red",
            });
          }
        })
        .catch((error) => {
          notify({
            text: error,
            theme: "red",
          });
        });
    },
    createEpisodeForSerie() {
      if (!this.serieTemporadasSelectedId) {
        this.setMessageTmep(
          "É necessário selecionar uma temporada para fazer o cadastro de um episódio"
        );
        return;
      }
      if (
        !this.createEpisodioNumeroInputValue &&
        this.createEpisodioNumeroInputValue > 0
      ) {
        this.setMessageTmep(
          "É necessário fornecer um número válido para criar um episódio"
        );
        return;
      }
      if (!this.createEpisodioNomeInputValue) {
        this.setMessageTmep(
          "É necessário fornecer um nome para criar um episódio"
        );
        return;
      }
      let temporadaId = this.serieTemporadasSelectedId;
      let indexOfTemporada = null;
      for (var i = 0; i < this.serieTemporadas.length; i++) {
        if (this.serieTemporadas[i].id === temporadaId) {
          indexOfTemporada = i;
        }
      }
      if (indexOfTemporada === null) {
        this.setMessageTmep(
          "Não foi possível encontrar a temporada que deseja cadastrar um episódio"
        );
        return;
      }
      for (
        var i = 0;
        i < this.serieTemporadas[indexOfTemporada].episodios.length;
        i++
      ) {
        if (
          this.serieTemporadas[indexOfTemporada].episodios[i].numero ==
          this.createEpisodioNumeroInputValue
        ) {
          this.setMessageTmep(
            "O número desse episódio já existe nessa temporada"
          );
          return;
        }
      }
      axios
        .post("api/v1/episodio", {
          temporada_id: temporadaId,
          numero: this.createEpisodioNumeroInputValue,
          nome: this.createEpisodioNomeInputValue,
        })
        .then((response) => {
          if (response.status == "201") {
            notify({
              text: "Episódio cadastrado com sucesso !",
              theme: "green",
            });
            this.$emit("reloadlist");
            this.showModalEditSerieEpisodeo = false;
            this.serieTemporadasSelectedId = null;
            this.createEpisodioNumeroInputValue = null;
            this.createEpisodioNomeInputValue = null;
          } else {
            notify({
              text: "Algo de inesperado ocorreu !",
              theme: "red",
            });
          }
        })
        .catch((error) => {
          notify({
            text: error,
            theme: "red",
          });
        });
    },
    deleteTemporada() {
      if (!this.temporadaHandle.id) {
        this.setMessageTmep(
          "Não foi possível encontrar a temporada que deseja excluir"
        );
        return;
      }
      axios
        .delete(`api/v1/temporada/${this.temporadaHandle.id}`)
        .then((response) => {
          if (response.status == "200") {
            notify({
              text: "Temporada deletada com sucesso !",
              theme: "green",
            });
            this.$emit("reloadlist");
            this.temporadaHandle = null;
            this.showModalEditSerieEpisodeo = false;
          } else {
            notify({
              text: "Algo de inesperado ocorreu !",
              theme: "red",
            });
          }
        })
        .catch((error) => {
          notify({
            text: error,
            theme: "red",
          });
        });
    },
    deleteEpisodio() {
      if (!this.episodioHandle.epProps.id) {
        this.setMessageTmep(
          "Não foi possível encontrar o episódio que deseja excluir"
        );
        return;
      }
      axios
        .delete(`api/v1/episodio/${this.episodioHandle.epProps.id}`)
        .then((response) => {
          if (response.status == "200") {
            notify({
              text: "Episodio deletado com sucesso !",
              theme: "green",
            });
            this.$emit("reloadlist");
            this.episodioHandle = null;
            this.showModalEditSerieEpisodeo = false;
          } else {
            notify({
              text: "Algo de inesperado ocorreu !",
              theme: "red",
            });
          }
        })
        .catch((error) => {
          notify({
            text: error,
            theme: "red",
          });
        });
    },
    editTemporada() {
      console.log(this.temporadaHandle);
    },
    editEpisodio() {
      console.log(this.episodioHandle);
    },
    lastEpisodeWhatched() {
      if (!this.lastEpisodioHandle) {
        this.setMessageTmep(
          "Não foi possível encontrar o episódio para que seja salvo"
        );
        return;
      }
      axios
        .patch(`api/v1/serie/last-episode/${this.lastEpisodioHandle.serieId}`, {
          lastEpisode: this.lastEpisodioHandle.nomeToShow,
        })
        .then((response) => {
          if (response.status == "200") {
            notify({
              text: "Ultimo episódio assistido atualizado com sucesso !",
              theme: "green",
            });
            this.$emit("reloadlist");
            this.lastEpisodioHandle = null;
            this.showModalEditSerieEpisodeo = false;
          } else {
            notify({
              text: "Algo de inesperado ocorreu !",
              theme: "red",
            });
          }
        })
        .catch((error) => {
          notify({
            text: error,
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
  z-index: 100;
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

.modal-container-lg {
  width: 800px;
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

.temporada-p {
  text-align: start;
  color: rgb(197, 64, 64);
}

.episodio-p {
  text-align: start;
  margin-left: 30px;
  color: rgb(231, 98, 98);
}

.create-temporada {
  margin: 5px;
  display: flex;
  justify-content: flex-end;
}

.create-episodio {
  margin: 5px;
  display: flex;
  justify-content: flex-end;
}

.span-input {
  width: 100%;
  text-align: start;
}

.btn-input-tmep-positive {
  margin-left: 5px;
  border-radius: 5px;
  border-color: transparent;
  cursor: pointer;
  background-color: rgb(68, 163, 68);
  color: white;
}

.btn-input-tmep-negative {
  margin-left: 5px;
  border-radius: 5px;
  border-color: transparent;
  cursor: pointer;
  background-color: rgb(151, 39, 39);
  color: white;
}

.btn-input-tmep-edit {
  margin-left: 5px;
  border-radius: 5px;
  border-color: transparent;
  cursor: pointer;
  background-color: rgb(214, 180, 27);
  color: white;
}

.btn-input-tmep-update {
  margin-left: 5px;
  border-radius: 5px;
  border-color: transparent;
  cursor: pointer;
  background-color: rgb(63, 114, 192);
  color: white;
}

.btn-input-tmep:hover {
  background-color: rgb(49, 128, 49);
}

.input-tmep {
  border-radius: 5px;
}

.input-tmep:focus {
  outline: none;
}

.select-tmep {
  border-radius: 5px;
}

.span-warning {
  color: rgb(195, 223, 37);
}

.modal-body-temporadas {
  margin-bottom: 50px;
}

.modal-body-form-temporada-episodio {
  padding: 10px;
  border-top: 1px solid white;
}

.title-modal-body-form {
  text-align: start;
}
</style>
