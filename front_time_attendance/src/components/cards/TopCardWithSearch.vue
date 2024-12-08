<template>
    <div class="q-mb-md q-mt-md">
      <q-card class="q-pa-md" :style="{ borderRadius: borderRadius }">
        <div class="fit row wrap justify-center items-center content-center">
          <div class="col-8 q-pa-md">
            <div class="text-h6">{{ title }}</div>
          </div>
          <div class="col-3">
            <q-input
              v-model="localSearch"
              :placeholder="searchPlaceholder"
              @keyup.enter="emitSearch"
              dense
              class="q-mt-sm"
            >
              <template v-slot:append>
                <q-icon v-if="localSearch === ''" :name="searchIcon" />
                <q-icon v-else :name="clearIcon" class="cursor-pointer" @click="clearSearch" />
              </template>
            </q-input>
          </div>
          <div class="col q-pa-sm">
            <q-btn 
              :color="buttonColor"
              :label="buttonLabel"
              :to="buttonRoute"
              class="q-mt-sm"
            />
          </div>
        </div>
      </q-card>
    </div>
  </template>
  
  <script>
  export default {
    name: "TopCardWithSearch",
    props: {
      title: { type: String, required: true },
      searchPlaceholder: { type: String, default: "Search..." },
      buttonLabel: { type: String, required: true },
      buttonRoute: { type: [String, Object], required: true },
      buttonColor: { type: String, default: "purple-8" },
      borderRadius: { type: String, default: "16px" },
      searchIcon: { type: String, default: "search" },
      clearIcon: { type: String, default: "clear" },
      search: { type: String, default: "" },
    },
    data() {
      return {
        localSearch: this.search,
      };
    },
    watch: {
      search(newVal) {
        this.localSearch = newVal;
      },
      localSearch(newVal) {
        this.$emit("update:search", newVal);
      },
    },
    methods: {
      clearSearch() {
        this.localSearch = "";
        this.emitSearch();
      },
      emitSearch() {
        this.$emit("search", this.localSearch);
      },
    },
  };
  </script>
  