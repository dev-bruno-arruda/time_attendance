<template>
  <q-page class="q-pa-sm">
    <q-table
      :title="$t('employees.title')"
      :rows="employees"
      :columns="columns"
      row-key="id"
    >
      <template v-slot:top>
        <span class="text-h5">{{ $t('employees.title') }}</span>
        <q-space />
        <q-btn color="primary" :label="$t('btns.new')" :to="{ name: 'formEmployees' }"/>
      </template>
      <template v-slot:body-cell-actions="props">
        <q-td :props="props" class="q-gutter-sm" style="width: 100px;">
          <q-btn
            color="primary"
            icon="edit"
            dense size="sm"
            :to="{ name: 'formEmployees', params: { id: props.row.id } }"
          >
            <q-tooltip>
              {{ $t('btns.edit') }}
            </q-tooltip>
          </q-btn>
          
          <q-btn
            color="negative"
            icon="delete"
            dense size="sm"
            @click="deleteEmployee(props.row.id)"
          >
            <q-tooltip>
              {{ $t('btns.delete') }}
            </q-tooltip>
          </q-btn>
        </q-td>
      </template>
    </q-table>
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted, computed } from 'vue';
import employeesService from 'src/services/employees';
import { notifyError, notifySuccess } from 'boot/helpers';
import { useI18n } from 'vue-i18n';

export default defineComponent({
  name: 'ListEmployees',
  setup() {
    const employees = ref([]);
    const { list, remove } = employeesService();
    const { t } = useI18n();

    const columns = computed(() => [
      { name: 'name', label: t('name'), field: 'name', sortable: true, align: 'left' },
      { name: 'email', label: t('email'), field: 'email', sortable: true, align: 'left' },
      { name: 'role', label: t('role'), field: 'role', sortable: true, align: 'left' },
      { name: 'cpf', label: t('cpf'), field: 'cpf', sortable: true, align: 'left' },
      { name: 'manager', label: t('manager'), field: 'manager', sortable: true, align: 'left' },
      { name: 'actions', label: t('columns.action'), field: 'actions', align: 'left' }
    ]);
    
    onMounted(() => {
      getEmployees();
    });

    const getEmployees = async () => {
      try {
        const employeesList = await list();
        employees.value = employeesList.map(employee => ({
          ...employee,
          manager: employee.manager_id ? employee.manager.name : ''
        }));
      } catch (error) {
        notifyError(error.message);
      }
    };

    const deleteEmployee = async (id) => {
      try {
        const response = await remove(id);
        if (response.success) {
          notifySuccess(response.message);
          getEmployees(); // Atualizar lista após a exclusão
        }
      } catch (error) {
        notifyError(error.message);
      }
    };

    return {
      employees,
      columns,
      deleteEmployee
    };
  },
});
</script>

<style scoped>
.q-table .q-gutter-sm {
  padding: 8px 16px;
}
</style>
