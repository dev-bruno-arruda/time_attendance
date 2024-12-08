import UseApi from 'src/composables/UseApi';


export default function AttendanceRecordService() {
  const { list, post, getByDates} = UseApi('employees');

  return {
    list,
    post,
    getByDates,
  }
}
