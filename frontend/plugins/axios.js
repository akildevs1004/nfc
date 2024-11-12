export default ({ app, $axios, store }, inject) => {
  const pendingRequests = [];
  const MAX_PENDING_REQUESTS = 20;

  // Helper function to cancel all pending requests
  const cancelPendingRequests = () => {
    pendingRequests.forEach(({ cancel }) => cancel("Request Canceled ......."));
    pendingRequests.length = 0; // Clear the array
  };
  $axios.onError((error) => {
    if (error.response && error.response.status === 401) {
      app.$auth.refreshTokens();
      app.$auth.reset();
    }
    pendingRequests.shift(); // Remove the oldest request from the queue
    //return Promise.reject(error);
  });
  $axios.onRequest(async (config) => {
    if (!config) return config;
    let user = store.state.auth.user;

    if (user) {
      config.params = {
        ...config.params,
        company_id: user.company_id,
      };
    }

    if (user?.role?.role_type == "guard") {
      if (user && user.employee && user.employee.branch_id > 0) {
        config.params = {
          ...config.params,
          branch_id: user.employee.branch_id,
        };
      }
    }
    if (user && user.branch_id && user.branch_id > 0) {
      config.params = {
        ...config.params,
        branch_id: user && user.branch_id,
      };
    }

    if (user && user.user_type == "department") {
      config.params = {
        ...config.params,
        department_id: user && user.department_id,
        user_type: user && user.user_type,
      };
    }
    if (user && user.user_type == "security") {
      if (!user.security) return config;
      let customersList = user.security.customers_assigned.map(
        (e) => e.customer_id
      );
      if (customersList.length > 0) {
        config.params = {
          ...config.params,
          filter_customers_list: customersList,
          //user_type: user && user.user_type,
        };
      }
    }

    const source = $axios.CancelToken.source();
    config.cancelToken = source.token;

    pendingRequests.push({ cancel: source.cancel, url: config.url });

    if (pendingRequests.length > MAX_PENDING_REQUESTS) {
      cancelPendingRequests();
    }

    return config;

    return config; // Return the modified config
  });

  // Response interceptor to remove completed requests from the queue
  $axios.onResponse((response) => {
    pendingRequests.shift(); // Remove the oldest request from the queue
    return response;
  });
};
// export default ({ app, $axios, store }, inject) => {
//   $axios.onError((error) => {
//     if (error.response && error.response.status === 401) {
//       app.$auth.refreshTokens();
//       app.$auth.reset();
//     }

//     return Promise.reject(error);
//   });
//   $axios.onRequest(async (config) => {
//     if (!config) return config;
//     let user = store.state.auth.user;

//     if (user) {
//       config.params = {
//         ...config.params,
//         company_id: user.company_id,
//       };
//     }

//     if (user?.role?.role_type == "guard") {
//       if (user && user.employee && user.employee.branch_id > 0) {
//         config.params = {
//           ...config.params,
//           branch_id: user.employee.branch_id,
//         };
//       }
//     }
//     if (user && user.branch_id && user.branch_id > 0) {
//       config.params = {
//         ...config.params,
//         branch_id: user && user.branch_id,
//       };
//     }

//     if (user && user.user_type == "department") {
//       config.params = {
//         ...config.params,
//         department_id: user && user.department_id,
//         user_type: user && user.user_type,
//       };
//     }
//     if (user && user.user_type == "security") {
//       if (!user.security) return config;
//       let customersList = user.security.customers_assigned.map(
//         (e) => e.customer_id
//       );
//       if (customersList.length > 0) {
//         config.params = {
//           ...config.params,
//           filter_customers_list: customersList,
//           //user_type: user && user.user_type,
//         };
//       }
//     }

//     return config; // Return the modified config
//   });
// };
