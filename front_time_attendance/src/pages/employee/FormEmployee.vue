<template>
  <q-page class="q-pa-sm">
    <div class="text-h6 q-ma-md">{{ $t('employees.employee_registration') }}</div>
    <q-separator />
    <q-form @submit.prevent="onSubmit" class="row q-col-gutter-sm">
      <q-input
        outlined
        v-model="form.name"
        :label="$t('name')"
        lazy-rules
        class="col-lg-6 col-xs-12"
        :rules="[val => val && val.length > 0 || $t('employees.enter_name')]"
      />
      <q-input
        outlined
        v-model="form.email"
        :label="$t('email')"
        type="email"
        lazy-rules
        class="col-lg-6 col-xs-12"
        :rules="[val => val && val.includes('@') || $t('employees.enter_valid_email')]"
      />
      <q-select
        outlined
        v-model="form.role"
        :label="$t('role')"
        :options="roles"
        option-value="value"
        option-label="label"
        class="col-lg-6 col-xs-12"
      />
      <q-input
        outlined
        v-model="form.birth_date"
        :label="$t('birth_date')"
        type="date"
        class="col-lg-6 col-xs-12"
      />
      <q-input
        outlined
        v-model="form.cpf"
        :label="$t('cpf')"
        lazy-rules
        class="col-lg-6 col-xs-12"
        :rules="[val => val && val.length === 11 || $t('employees.enter_valid_cpf')]"
      />
      <q-input
        outlined
        v-model="form.cep"
        :label="$t('cep')"
        lazy-rules
        class="col-lg-6 col-xs-12"
        :rules="[val => val && val.length === 8 || $t('employees.enter_valid_cep')]"
        @blur="fetchAddressData"
      />
      <q-input
        outlined
        v-model="form.address"
        :label="$t('address')"
        class="col-lg-12 col-xs-12"
      />
      <q-input
        outlined
        v-model="form.number"
        :label="$t('number')"
        type="number"
        class="col-lg-4 col-xs-12"
      />
      <q-input
        outlined
        v-model="form.state"
        :label="$t('state')"
        class="col-lg-4 col-xs-12"
      />
      <q-input
        outlined
        v-model="form.city"
        :label="$t('city')"
        class="col-lg-4 col-xs-12"
      />
      <q-select
        outlined
        v-model="form.manager_id"
        :label="$t('manager')"
        :options="managers"
        option-value="id"
        option-label="name"
        emit-value
        map-options
        class="col-lg-6 col-xs-12"
      />
      <div class="col-lg-12 col-xs-12 q-mt-lg">
        <div class="q-gutter-md flex justify">
          <q-btn type="submit" :label="$t('btns.save')" color="primary" class="q-px-md" />
          <q-btn
            type="reset"
            :label="$t('btns.cancel')"
            color="white"
            text-color="primary"
            outline
            class="q-px-md"
            :to="{ name: 'EmployeesList' }"
          />
        </div>
      </div>


      
    </q-form>
  </q-page>
</template>

<script>
import { defineComponent, ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useQuasar } from 'quasar';
import EmployeeService from 'src/services/employee';
import { notifyError, notifySuccess } from 'boot/helpers';
import { useI18n } from 'vue-i18n';
import viaCepService from 'src/services/viaCepService'; 

export default defineComponent({
  name: 'EmployeeForm',
  setup() {
    const { post, update, getById, list } = EmployeeService();
    const $q = useQuasar();
    const router = useRouter();
    const route = useRoute();
    const form = ref({
      name: '',
      email: '',
      role: '',
      birth_date: '',
      cpf: '',
      cep: '',
      address: '',
      number: '',
      state: '',
      city: '',
      manager_id: null,
    });
    const roles = ref([
      { value: 'employee', label: 'employee' },
      { value: 'admin', label: 'admin' },
    ]);
    const managers = ref([]);
    const { t } = useI18n();

    const loadForm = async () => {
      try {
        if (route.params.id) {
          const response = await getById(route.params.id);
          if (response.status === 'error') {
            notifyError(response.message);
            router.push({ name: 'EmployeesList' });
          } else {
            form.value = {
              name: response.data.attributes.name,
              email: response.data.attributes.email,
              role: roles.value.find(role => role.value === response.data.attributes.role) || '',
              birth_date: response.data.attributes.birth_date,
              cpf: response.data.attributes.cpf,
              cep: response.data.attributes.cep,
              address: response.data.attributes.address,
              number: response.data.attributes.number,
              state: response.data.attributes.state,
              city: response.data.attributes.city,
              manager_id: response.data.attributes.manager_id,
            };
          }
        }
        const managersResponse = await list();
        managers.value = managersResponse.map(manager => ({
          id: manager.id,
          name: manager.attributes.name,
        }));
      } catch (error) {
        notifyError(error.message);
      }
    };

    const onSubmit = async () => {
    let response;
    try {
      const payload = {
        data: {
          type: 'employeer',
          attributes: {
            name: form.value.name,
            email: form.value.email,
            role: form.value.role?.value || form.value.role,
            birth_date: form.value.birth_date,
            cpf: form.value.cpf,
            cep: form.value.cep,
            address: form.value.address,
            number: form.value.number,
            state: form.value.state,
            city: form.value.city,
            manager_id: form.value.manager_id,
          },
        },
      };
      response = route.params.id
        ? await update(route.params.id, payload)
        : await post(payload);

      notifySuccess(t(response.message));
      router.push({ name: 'EmployeesList' });
    } catch (error) {
      
    if (error.response && error.response.data) {
      const errorMessage =
        error.response.data.message || t('An unexpected error occurred');
      notifyError(errorMessage);

      console.error('API Error:', error.response.data.message);
    } else {
      notifyError(error.message || t('An unexpected error occurred'));
      console.error('Error:', error);
    }
  }
  };
  const fetchAddressData = async () => {
      try {
        if (form.value.cep.length === 8) {
          const data = await viaCepService.fetchCepData(form.value.cep);
          form.value.address = data.logradouro || '';
          form.value.city = data.localidade || '';
          form.value.state = data.uf || '';
        }
      } catch (error) {
        notifyError(error.message);
      }
    };

    loadForm();

    return {
      form,
      roles,
      managers,
      onSubmit,
      fetchAddressData,
      t,
    };
  },
});
</script>

<style scoped>
.q-separator {
  margin: 16px 0;
}
</style>
