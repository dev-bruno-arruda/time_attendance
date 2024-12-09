import UseApi from 'src/composables/UseApi';


export default function EmployeeService() {
  const { list, post, update, getById} = UseApi('employees');

  return {
    list,
    post,
    update,
    getById,
  }
}
