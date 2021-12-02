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
                <p
                  v-if="editTempEp == 'editTemp'"
                  class="title-modal-body-form"
                >
                  Edição de temporada
                </p>
                <p v-if="editTempEp == 'editEp'" class="title-modal-body-form">
                  Edição de série
                </p>
                <div
                  v-if="editTempEp == 'editTemp' || editTempEp == 'editEp'"
                  class="modal-body-form-temporada-episodio"
                >
                  <div v-if="editTempEp == 'editTemp'" class="create-temporada">
                    <span class="span-input">Temporada: </span>
                    <input
                      class="input-tmep"
                      id="create-temporada-numero-input"
                      v-model="tempEditNumero"
                      type="number"
                      min="1"
                      placeholder="Número"
                      required
                    />
                    <input
                      class="input-tmep"
                      id="create-temporada-nome-input"
                      v-model="tempEditNome"
                      type="text"
                      placeholder="Nome"
                      required
                    />
                    <button
                      @click="confirmEditTemp"
                      class="btn-input-tmep-update"
                    >
                      <i class="fas fa-check-circle"></i>
                    </button>
                  </div>
                  <div v-if="editTempEp == 'editEp'" class="create-episodio">
                    <span class="span-input">Episódio: </span>
                    <select
                      class="select-tmep"
                      id="create-episodio-temporada-numero-input"
                      v-model="epEditTempId"
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
                      v-model="epEditNumero"
                      type="number"
                      min="1"
                      placeholder="Número"
                      required
                    />
                    <input
                      class="input-tmep"
                      id="create-episodio-nome-input"
                      v-model="epEditNome"
                      type="text"
                      placeholder="Nome"
                      required
                    />
                    <button
                      @click="confirmEditEp"
                      class="btn-input-tmep-update"
                    >
                      <i class="fas fa-check-circle"></i>
                    </button>
                  </div>
                </div>
                <p v-if="editTempEp == 'cad'" class="title-modal-body-form">
                  Edição e exclusão de temporadas e séries
                </p>
                <div
                  v-if="editTempEp == 'cad'"
                  class="modal-body-form-temporada-episodio"
                >
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
            tempId: this.serieTemporadas[i].id,
            tempNumero: this.serieTemporadas[i].numero,
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
      editTempEp: "cad",
      tempEditId: null,
      tempEditNumero: null,
      tempEditNome: null,
      epEditTempId: null,
      epEditNumero: null,
      epEditNome: null,
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
      this.editTempEp = "editTemp";
      this.tempEditId = this.temporadaHandle.id;
      this.tempEditNumero = this.temporadaHandle.numero;
      this.tempEditNome = this.temporadaHandle.nome;
    },
    confirmEditTemp() {
      axios
        .put(`api/v1/temporada/${this.tempEditId}`, {
          numero: this.tempEditNumero,
          nome: this.tempEditNome,
        })
        .then((response) => {
          this.editTempEp = "cad";
          this.temporadaHandle = null;
          this.showModalEditSerieEpisodeo = false;
          if (response.status == 200) {
            notify({
              text: "Edição realizada com sucesso !",
              theme: "green",
            });
            this.$emit("reloadlist");
          }
        })
        .catch((error) => {
          notify({
            text: error,
            theme: "red",
          });
        });
    },
    editEpisodio() {
      this.editTempEp = "editEp";
      this.epEditId = this.episodioHandle.epProps.id;
      this.epEditTempId = this.episodioHandle.tempId;
      this.epEditNumero = this.episodioHandle.epProps.numero;
      this.epEditNome = this.episodioHandle.epProps.nome;
    },
    confirmEditEp() {
      axios
        .put(`api/v1/episodio/${this.epEditId}`, {
          temporada_id: this.epEditTempId,
          numero: this.epEditNumero,
          nome: this.epEditNome,
        })
        .then((response) => {
          this.editTempEp = "cad";
          this.episodioHandle = null;
          this.showModalEditSerieEpisodeo = false;
          if (response.status == 200) {
            notify({
              text: "Edição realizada com sucesso !",
              theme: "green",
            });
            this.$emit("reloadlist");
          }
        })
        .catch((error) => {
          notify({
            text: error,
            theme: "red",
          });
        });
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
@import "./styles/formSeries.css";
</style>
