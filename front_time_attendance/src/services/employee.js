import UseApi from 'src/composables/UseApi';


export default function EmployeeService() {
  const { list, post, update, getById, remove} = UseApi('employees');

  return {
    list,
    post,
    update,
    getById,
    remove
  }
}
