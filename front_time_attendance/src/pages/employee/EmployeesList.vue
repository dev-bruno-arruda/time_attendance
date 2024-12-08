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
        <q-btn color="primary" :label="$t('btns.new')" :to="{ name: 'EmployeeForm' }"/>
      </template>
      <template v-slot:body-cell-actions="props">
        <q-td :props="props" class="q-gutter-sm" style="width: 100px;">
          <q-btn
            color="primary"
            icon="edit"
            dense size="sm"
            :to="{ name: 'EmployeeForm', params: { id: props.row.id } }"
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
import employeesService from 'src/services/employee';
import { notifyError, notifySuccess } from 'boot/helpers';
import { useI18n } from 'vue-i18n';

export default defineComponent({
  name: 'EmployeesList',
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
      const response = await list();
   
      if (Array.isArray(response)) {
        employees.value = response.map(employee => ({
          id: employee.id,
          name: employee.attributes.name,
          email: employee.attributes.email,
          role: employee.attributes.role,
          cpf: employee.attributes.cpf,
          manager: employee.attributes.manager_id
        }));
      } else {
        console.error('No valid employee data found:', response);
        notifyError('No employee data found.');
      }
    } catch (error) {
      console.error('Error fetching employees:', error);
      notifyError(error.message);
    }
  };


    const deleteEmployee = async (id) => {
      try {
        console.log('removendo')
        const response = await remove(id);
        console.log("response", response)
        if (response =='' ) {
          notifySuccess(t('employees.deleted_success'));
          getEmployees()
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
