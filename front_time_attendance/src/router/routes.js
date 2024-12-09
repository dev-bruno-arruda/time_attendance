const routes = [
  {
    path: '/',
    redirect: '/Login-1'  // Redirect to Login-1 page
  },
  {
    path: '/Login-1',
    component: () => import('pages/Login-1.vue')
  },
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/Error404.vue')
  },
  {
    path: '/dashboard',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/Dashboard.vue') },
      { path: '/Dashboard2', component: () => import('pages/Dashboard2.vue') },
      { path: '/Profile', component: () => import('pages/UserProfile.vue') },
      { path: '/attendance-register', name: 'AttendanceRegister', component: () => import('pages/attendance/AttendanceRegister.vue') },
      {
        path: '/employees',
        name: 'EmployeesList',
        component: () => import('pages/employee/EmployeesList.vue'),
        beforeEnter: (to, from, next) => {
          const isAuthenticated = () => !!localStorage.getItem('token');
          const isAdmin = () => {
          const role = JSON.parse(localStorage.getItem('role'))
          return role === 'admin'
          };

          if (isAuthenticated() && isAdmin()) {
            next();
          } else {
            next('/dashboard'); // Redirect if not an admin
          }
        }
      },
      {
        path: '/employees-form/:id?',
        name: 'EmployeeForm',
        component: () => import('pages/employee/FormEmployee.vue'),
        beforeEnter: (to, from, next) => {
          
          const isAuthenticated = () => !!localStorage.getItem('token');
          const isAdmin = () => {
            const role = JSON.parse(localStorage.getItem('role'))
            return role === 'admin'
            };
          
          if (isAuthenticated() && isAdmin()) {
            next();
          } else {
            next('/dashboard'); // Redirect if not an admin
          }
        }
      }
    ],
    beforeEnter: (to, from, next) => {
      const isAuthenticated = () => {
        return !!localStorage.getItem('token');
      };

      if (isAuthenticated()) {
        next();
      } else {
        next('/Login-1'); // Redirect if not authenticated
      }
    }
  }
];

export default routes;
