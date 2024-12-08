<template>
  <q-page class="q-pa-sm">
    <q-table
      :title="$t('users.title')"
      :rows="users"
      :columns="columns"
      row-key="id"
    >
      <template v-slot:top>
        <span class="text-h5">{{ $t('users.title') }}</span>
        <q-space />
        <q-btn color="primary" :label="$t('btns.new')" :to="{ name: 'formUsers' }"/>
      </template>
      <template v-slot:body-cell-actions="props">
        <q-td :props="props" class="q-gutter-sm" style="width: 100px;">
          <q-btn
            color="primary"
            icon="edit"
            dense size="sm"
            :to="{ name: 'formUsers', params: { id: props.row.id } }"
          >
            <q-tooltip>
              {{ $t('btns.edit') }}
            </q-tooltip>
          </q-btn>
          
          <q-btn
            color="negative"
            icon="delete"
            dense size="sm"
            @click="deleteUser(props.row.id)"
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
import usersService from 'src/services/users';
import { notifyError, notifySuccess } from 'boot/helpers';
import { useI18n } from 'vue-i18n';

export default defineComponent({
  name: 'ListUsers',
  setup() {
    const users = ref([]);
    const { list, remove } = usersService();
    const { t } = useI18n();

    const columns = computed(() => [
      { name: 'name', label: t('name'), field: 'name', sortable: true, align: 'left' },
      { name: 'email', label: t('email'), field: 'email', sortable: true, align: 'left' },
      { name: 'role', label: t('role'), field: 'role', sortable: true, align: 'left' },
      { name: 'birth_date', label: t('birth_date'), field: 'birth_date', sortable: true, align: 'left' },
      { name: 'cpf', label: t('cpf'), field: 'cpf', sortable: true, align: 'left' },
      { name: 'manager_id', label: t('manager'), field: 'manager_id', sortable: true, align: 'left' },
      { name: 'actions', label: t('columns.action'), field: 'actions', align: 'left' }
    ]);
    
    onMounted(() => {
      getUsers();
    });

    const getUsers = async () => {
      try {
        const userList = await list();
        users.value = userList.map(user => ({
          ...user,
          role: user.role === 'admin' ? t('roles.admin') : t('roles.employee'),
          manager_id: user.manager_id || 'N/A',
        }));
      } catch (error) {
        notifyError(error.message);
      }
    };

    const deleteUser = async (id) => {
      try {
        const response = await remove(id);
        if (response.success) {
          notifySuccess(response.message);
          getUsers(); // Atualiza a lista após a exclusão
        }
      } catch (error) {
        notifyError(error.message);
      }
    };

    return {
      users,
      columns,
      deleteUser
    };
  },
});
</script>

<style scoped>
.q-table .q-gutter-sm {
  padding: 8px 16px;
}
</style>
