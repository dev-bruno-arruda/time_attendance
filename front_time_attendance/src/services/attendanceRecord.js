import UseApi from 'src/composables/UseApi';


export default function AttendanceRecordService() {
  const { list, post, getByDates} = UseApi('attendance');

  return {
    list,
    post,
    getByDates,
  }
}
