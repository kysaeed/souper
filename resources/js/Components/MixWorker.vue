<template>
  <div>
    <div class="card">
      <div class="card-header">混ぜる！</div>
      <div class="card-body">
        <h5 class="card-title"></h5>
        <div class="card-text">
          <div class="mix-list">
            <div class="row" v-for="(m, i) in mixList" :key="i">
              <div class="col">
                <select
                  class="form-select"
                  aria-label="Default select example"
                  v-model="m.fish"
                >
                  <option v-for="(f, i) in fishInfo" :value="i" :key="i">
                    {{ f.name }}
                  </option>
                </select>
              </div>
              <div class="col">
                <div class="input-group mb-3">
                  <input
                    v-model="m.g"
                    type="number"
                    class="form-control"
                    placeholder="グラム"
                    aria-describedby="basic-addon1"
                    min="0"
                  />
                  <span class="input-group-text">g</span>
                </div>
              </div>
              <div class="col-1">
                <button
                  type="button"
                  class="btn btn-danger"
                  @click="onDelete(i)"
                >
                  x
                </button>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="d-grid gap-2">
                  <button class="btn btn-outline-primary" @click="onAdd">
                    + 材料
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <button class="btn btn-primary" @click="onCompleate">できた！！</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "mix-worker",
  props: {
    fishInfo: {
      type: Array,
      default: () => [],
    },
  },
  methods: {
    clear() {
        this.mixList = []
    },
    onAdd() {
      this.mixList.push({
        fish: 0,
        g: 0,
      });
    },
    onDelete(index) {
      this.mixList.splice(index, 1);
    },
    onCompleate() {
      this.$emit("complete", this.mixList);
    },
  },
  data() {
    const mixList = [];

    return {
      mixList,
    };
  },
};
</script>

<style lang="scss" scoped>
.mix-list {
  border: 1px solid #eee;
  border-radius: 4px;

  margin: 16px;
  padding: 8px;
}
</style>