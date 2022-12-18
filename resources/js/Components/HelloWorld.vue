<template>
  <div class="container-sm">
    <div class="row mt-5">
      <div class="col">
        <div class="card">
          <div class="card-header card-header-title">
            注文
            <div class="form-check form-switch ui-rate-option">
                <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" v-model="isDecimailPointEnable">
                <label class="form-check-label" for="flexSwitchCheckDefault">少数点</label>
            </div>
        </div>
          <div class="card-body main-bg">
            <h5 class="card-title main-title">
              ■ {{ kgTotal }}kg作ってください
            </h5>
            <p class="card-text">
              <rate-sheet
                class="mt-3 mx-4"
                :fish-info="fishInfo"
                :recipe="recipe"
              />
            </p>
          </div>
        </div>

        <mix-worker ref="mixWorker" class="mt-3" :fish-info="fishInfo" @complete="onMixInput" />
      </div>
    </div>
    <!-- Modal -->
    <div
      ref="modalComplete"
      class="modal fade"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">正解</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <p>おめでとう！</p>
            <p>美味しいだしをつくれました。</p>
          </div>
          <div class="modal-footer">
            <!-- <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
            >
              Close
            </button> -->
            <button type="button" class="btn btn-primary" @click="onCompleteModalClose">次へ</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div
      ref="modalNg"
      class="modal fade"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">不正解</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <p>間違っています</p>
          </div>
          <div class="modal-footer">
            <!-- <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
            >
              Close
            </button> -->
            <button type="button" class="btn btn-secondary" @click="onNgModalClose">閉じる</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import RateSheet from "./RateSheet.vue";
import MixWorker from "./MixWorker.vue";

export default {
  name: "hello-world",
  props: {},
  components: {
    RateSheet,
    MixWorker,
  },
  watch: {
    isDecimailPointEnable(value) {
        const dp = value ? '1': '0'
        history.replaceState(null, null, `?dp=${dp}`)
    },
  },
  mounted() {
    this.completeModal = new bootstrap.Modal(this.$refs.modalComplete, {
        backdrop: "static",
    });
    this.ngModal = new bootstrap.Modal(this.$refs.modalNg, {
        backdrop: "static",
    });

  },
  methods: {
    createRecipe(fishInfo, isDecimailPointEnable) {
      const pointList = [3, 5, 7,]
      const recipe = [];
      const typeCount = Math.floor(Math.random() * fishInfo.length) + 1
      const list = _.shuffle(fishInfo.map((e, i) => i))

      let rates = []
      let remine = 100
      let prevDecimalPoint = 0
      for (let i = 0; i < typeCount - 1; i++) {
        let r = 0
        if (prevDecimalPoint) {
            r = 10 - prevDecimalPoint
            if (remine > 20) {
                r += Math.floor(Math.random() * 2) * 10
            }
            prevDecimalPoint = 0

        } else {
            r = (Math.floor(Math.random() * ((remine / 10) - (typeCount - i)))) * 10
            if (!isDecimailPointEnable) {
                r += 10
            } else {
                if (r <= 10) {
                    if (!Math.floor(Math.random() * 3)) {
                        prevDecimalPoint = _.sample(pointList)
                        r += prevDecimalPoint
                    } else {
                        r += 10
                    }
                } else {
                    r += 10
                }
            }
        }

        rates.push(r);
        remine -= r;
      }


      rates.push(remine)
      rates = _.shuffle(rates)

      for (let i = 0; i < typeCount; i++) {
        recipe.push({
          fish: list[i],
          rate: rates[i] / 10,
        });
      }
      return recipe;
    },
    isValidMix(mix) {
      const unit = this.kgTotal * 100;
      let recipe = _.cloneDeep(this.recipe);
      let isValidMix = true;
      mix.forEach((m, mixIndex) => {
        let findIndex = recipe.findIndex((r) => {
          if (r.fish === m.fish) {
            if (r.rate * unit === m.g) {
              return true;
            }
          }
          return false;
        });
        if (findIndex > -1) {
          recipe.splice(findIndex, 1);
        } else {
          isValidMix = false;
        }
      });

      return isValidMix && recipe.length === 0;
    },
    onMixInput(mix) {
      const isValid = this.isValidMix(mix)

      if (isValid) {
        this.completeModal.show()
      } else {
        this.ngModal.show()
      }
    },
    onCompleteModalClose() {
        this.completeModal.hide()

        this.kgTotal = Math.floor(Math.random() * 10) + 1
        this.recipe = this.createRecipe(this.fishInfo, this.isDecimailPointEnable)
        this.$refs.mixWorker.clear()

    },
    onNgModalClose() {
        this.ngModal.hide()
    },
  },
  data() {
    const fishInfo = [
      { name: "カツオ " },
      { name: "煮干し" },
      { name: "マグロ" },
      { name: "エビ" },
      { name: "昆布" },
    ];

    const max = 10;
    const kgTotal = Math.floor(Math.random() * max) + 1;
    let isDecimailPointEnable = true

    const params = new URLSearchParams(window.location.search)
    if (params.has('dp')) {
        const dp = parseInt(params.get('dp')) ?? 0
        isDecimailPointEnable = !!(dp)
    }

    const recipe = this.createRecipe(fishInfo, isDecimailPointEnable);

    return {
      kgTotal,
      recipe,
      fishInfo,
      isDecimailPointEnable,
    };
  },
};
</script>

<style lang="scss" scoped>
.main-bg {
  background: #dcffd0;
}
.main-title {
  // color: #ffffff;
}
.card-header-title {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.ui-rate-option {
    font-size: 0.8em;
    margin: 1px 20px;
}
</style>