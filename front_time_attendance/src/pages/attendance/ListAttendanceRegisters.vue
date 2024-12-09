<template>
  <q-page class="q-pa-sm q-mb-md">
    <div>
      <TopCardWithSearch
        :title="$t('employees_attendance_registers.title')"
        :search="search"
        @update:search="(val) => search = val"
        :search-placeholder="$t('search') + '...'"
        :button-label="$t('btns.new')"
        @search="onSearch"
        @search-dates="onSearchDates"
      />
    </div>
    <q-card class="q-pa-md" style="border-radius: 16px;">
      <q-table
        flat bordered
        :rows="attendanceRegisters"
        :columns="columns"
        row-key="id"
        table-header-class="bg-purple-8 text-white"
        class="rounded"
      >
        <template v-slot:body-cell-flag="props">
          <q-td :props="props" class="q-gutter-sm flag-cell">
            <div class="flag-wrapper">
              <q-img :src="props.row.flag" class="flag-img" />
            </div>
          </q-td>
        </template>
      </q-table>
    </q-card>
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted, computed, onBeforeMount } from 'vue';
import AttendanceRecordService from 'src/services/attendance'; 
import { notifyError, notifySuccess } from 'boot/helpers';
import { useI18n } from "vue-i18n";
import { formatDate } from 'src/helpers';
import TopCardWithSearch from "src/components/cards/TopCardWithSearch.vue";

export default defineComponent({
  components: { TopCardWithSearch },
  name: 'ListAttendanceRegisters',
  setup() {
    const search = ref('');
    const attendanceRegisters = ref([]);
    const isDataLoaded = ref(false);
    const { list: ListAttendanceRegisters } = AttendanceRecordService();
    const { t } = useI18n();

    const columns = computed(() => [
      { name: 'employee_name', label: t('name'), field: 'employee_name', sortable: true, align: 'left' },
      { name: 'employee_role', label: t('role'), field: 'employee_role', sortable: true, align: 'left' },
      { name: 'age', label: t('age'), field: 'age', align: 'left' },
      { name: 'manager_name', label: t('manager_name'), field: 'manager_name', sortable: true, align: 'left' },
      { name: 'registered_at', label: t('registered_at'), field: 'registered_at', sortable: true, align: 'left' },
    ]);


    const getAttendanceRegister = async (start_date = '', end_date = '') => {
      try {
        const params = new URLSearchParams();
        if (start_date) params.append('start_date', start_date);
        if (end_date) params.append('end_date', end_date);
        
        const data = await ListAttendanceRegisters(params);
        console.log('Dados recebidos:', data);
        attendanceRegisters.value = data.map(item => ({
          ...item,
          registered_at: formatDate(item.registered_at),
        }));
      } catch (error) {
        notifyError(error.message || t('error.fetchingData'));
      }
    };

    const onSearch = () => {
      getAttendanceRegister(search.value);
    };

    const onSearchDates = ({ start_date, end_date }) => {
      getAttendanceRegister(start_date, end_date);
    };

    onBeforeMount(() => {
      getAttendanceRegister();
    });

    onMounted(() => {
      isDataLoaded.value = true;
    });

    return {
      search,
      attendanceRegisters,
      columns,
      onSearch,
      onSearchDates,
    };
  }
});
</script>
