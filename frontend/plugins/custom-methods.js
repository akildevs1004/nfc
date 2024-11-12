export default ({ app }, inject) => {
  inject("dateFormat", {
    format1: (inputdate) => {
      // Create a Date object with the date "2023-09-13"  Output Sun, Jan 01, 2023
      const inputDate = new Date(inputdate);
      const options = {
        year: "numeric",
        month: "short",
        day: "2-digit",
        weekday: "short",
      };
      const formattedDate = inputDate.toLocaleDateString("en-US", options);
      return formattedDate;
    },
    format2: (inputdate) => {
      if (!inputdate || inputdate == "--") return "---";
      // Create a Date object with the date "2023-09-13"  Output: "23-09-13"
      const date = new Date(inputdate);

      const year = date.getFullYear().toString().slice(-2);
      const month = (date.getMonth() + 1).toString().padStart(2, "0"); // Note: Month is zero-indexed
      const day = date.getDate().toString().padStart(2, "0");

      return `${year}-${month}-${day}`;
    },
    format3: (inputdate) => {
      if (!inputdate || inputdate == "--") return "---";
      const currentDate = new Date(inputdate);

      const year = currentDate.getFullYear();
      const month = (currentDate.getMonth() + 1).toString().padStart(2, "0"); // Adding 1 to month because it's zero-based.
      const day = currentDate.getDate().toString().padStart(2, "0");
      const hours = currentDate.getHours().toString().padStart(2, "0");
      const minutes = currentDate.getMinutes().toString().padStart(2, "0");
      const seconds = currentDate.getSeconds().toString().padStart(2, "0");

      return `${year}-${month}-${day} ${hours}:${minutes} `;
    },
    format4: (inputdate) => {
      if (!inputdate || inputdate == "--") return "---";
      const currentDate = new Date(inputdate);

      const year = currentDate.getFullYear();
      const month = (currentDate.getMonth() + 1).toString().padStart(2, "0"); // Adding 1 to month because it's zero-based.
      const day = currentDate.getDate().toString().padStart(2, "0");
      const hours = currentDate.getHours().toString().padStart(2, "0");
      const minutes = currentDate.getMinutes().toString().padStart(2, "0");
      const seconds = currentDate.getSeconds().toString().padStart(2, "0");

      const inputDate = new Date(inputdate);
      const options = {
        year: "numeric",
        month: "short",
        day: "2-digit",
        weekday: "short",
      };
      const formattedDate = inputDate.toLocaleDateString("en-US", options);

      return `${formattedDate}  ${hours}:${minutes} `;
    },
    formatDateMonthYear: (inputdate) => {
      if (!inputdate || inputdate == "--") return "---";
      const currentDate = new Date(inputdate);

      const year = currentDate.getFullYear();
      const month = (currentDate.getMonth() + 1).toString().padStart(2, "0"); // Adding 1 to month because it's zero-based.
      const day = currentDate.getDate().toString().padStart(2, "0");
      const hours = currentDate.getHours().toString().padStart(2, "0");
      const minutes = currentDate.getMinutes().toString().padStart(2, "0");
      const seconds = currentDate.getSeconds().toString().padStart(2, "0");

      const inputDate = new Date(inputdate);
      const options = {
        year: "numeric",
        month: "short",
        day: "2-digit",
      };
      const formattedDate = inputDate.toLocaleDateString("en-US", options);

      return `${formattedDate}  ${hours}:${minutes} `;
    },
    format5: (inputdate) => {
      if (!inputdate || inputdate == "--") return "---";
      const currentDate = new Date(inputdate);

      const year = currentDate.getFullYear();
      const month = (currentDate.getMonth() + 1).toString().padStart(2, "0"); // Adding 1 to month because it's zero-based.
      const day = currentDate.getDate().toString().padStart(2, "0");
      const hours = currentDate.getHours().toString().padStart(2, "0");
      const minutes = currentDate.getMinutes().toString().padStart(2, "0");
      const seconds = currentDate.getSeconds().toString().padStart(2, "0");

      const inputDate = new Date(inputdate);
      const options = {
        year: "numeric",
        month: "short",
        day: "2-digit",
        weekday: "short",
      };
      const formattedDate = inputDate.toLocaleDateString("en-US", options);

      return `${hours}:${minutes} ${formattedDate}   `;
    },
    monthStartEnd: (inputdate) => {
      if (!inputdate || inputdate == "--") return "---";
      // Get the current date
      const currentDate = new Date(inputdate);

      // Get the first day of the current month
      const firstDayOfMonth = new Date(
        currentDate.getFullYear(),
        currentDate.getMonth(),
        1
      );

      // Get the last day of the current month
      const lastDayOfMonth = new Date(
        currentDate.getFullYear(),
        currentDate.getMonth() + 1,
        0
      );

      // Format the dates as strings (in 'YYYY-MM-DD' format)
      const formattedFirstDay = `${firstDayOfMonth.getFullYear()}-${(
        firstDayOfMonth.getMonth() + 1
      )
        .toString()
        .padStart(2, "0")}-01`;
      const formattedLastDay = `${lastDayOfMonth.getFullYear()}-${(
        lastDayOfMonth.getMonth() + 1
      )
        .toString()
        .padStart(2, "0")}-${lastDayOfMonth.getDate()}`;

      return { first: formattedFirstDay, last: formattedLastDay };
    },
    time2Hm: (inputdate) => {
      if (!inputdate || inputdate == "--") return "---";
      // Split the time string by ':'
      const timeParts = inputdate.split(":");

      // Extract the hour and minutes
      const hour = timeParts[0];
      const minutes = timeParts[1];

      // console.log(`Hour: ${hour}`);
      // console.log(`Minutes: ${minutes}`);
      return `${hour}:${minutes}`;
    },
    format_month_name_year: (inputdate) => {
      // Create a Date object with the date "2023-09-13"  Output: "23-09-13"
      const date = new Date(inputdate);

      const months = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
      ];

      const currentDate = date;
      const currentMonth = months[currentDate.getMonth()];
      const currentYear = currentDate.getFullYear();

      return `${currentMonth} ${currentYear}`;
    },
    format_date_month_name_year: (inputdate) => {
      if (!inputdate || inputdate == "--") return "---";
      // Create a Date object with the date "2023-09-13"  Output Sun, Jan 01, 2023
      const inputDate = new Date(inputdate);
      const options = {
        year: "numeric",
        month: "short",
        day: "2-digit",
      };
      const formattedDate = inputDate.toLocaleDateString("en-US", options);
      return formattedDate;
    },
    minutesToHHMM(minutes) {
      var hours = Math.floor(minutes / 60);
      var remainingMinutes = minutes % 60;
      return (
        (hours < 10 ? "0" : "") +
        hours +
        ":" +
        (remainingMinutes < 10 ? "0" : "") +
        remainingMinutes
      );
    },
    format6: (inputdate) => {
      if (!inputdate || inputdate == "--") return "---";
      //20:30
      if (
        inputdate == "---" ||
        inputdate == "--" ||
        inputdate == 0 ||
        inputdate == ""
      )
        return "---";
      const currentDate = new Date(inputdate);

      const year = currentDate.getFullYear();
      const month = (currentDate.getMonth() + 1).toString().padStart(2, "0"); // Adding 1 to month because it's zero-based.
      const day = currentDate.getDate().toString().padStart(2, "0");
      const hours = currentDate.getHours().toString().padStart(2, "0");
      const minutes = currentDate.getMinutes().toString().padStart(2, "0");
      const seconds = currentDate.getSeconds().toString().padStart(2, "0");

      const inputDate = new Date(inputdate);
      const options = {
        year: "numeric",
        month: "short",
        day: "2-digit",
        weekday: "short",
      };
      const formattedDate = inputDate.toLocaleDateString("en-US", options);
      if (year == 1970 || inputdate == 0) return "---";
      return `${hours}:${minutes}`;
    },
    can(per, thisobj) {
      let u = thisobj.$auth.user;

      return (
        (u && u.permissions.some((e) => e == per || per == "/")) ||
        u.is_master ||
        u.user_type == "branch"
      );
    },

    verifyDeviceSensorName(sensorName, devices) {
      for (let device of devices) {
        if (device.device_type == sensorName) {
          return true;
        }

        // if (device.sensorzones) {
        //   let filter = device.sensorzones.filter(
        //     (e) => e.sensor_name == sensorName
        //   );
        //   if (filter.length > 0) {
        //     return true;
        //   }
        // }
      }

      return false;
    },
  });

  inject("pagePermission", {
    can(page, thisObj) {
      if (!thisObj) return true; //undefiend
      //if (!thisObj) thisObj = this.$store.state.auth;
      if (thisObj) {
        let u = thisObj.$auth.user;
        let filter = u.permissions.filter((e) => e.page_name == page);

        return (u && filter.length > 0) || u.is_master || u.role_id == 1;
      }
    },
    // can(per, thisobj) {
    //   let u = thisobj.$auth.user;

    //   // return (
    //   //   (u && u.permissions.some((e) => e == per || per == "/")) ||
    //   //   u.is_master ||
    //   //   u.user_type == "branch"
    //   // );

    //   return (
    //     (u && u.permissions.some((e) => e == per || per == "/")) || u.is_master
    //   );
    // },
  });

  inject("utils", {
    getRelaventBuildingType(id) {
      let relaventImage = {
        1: "Commercial",
        2: "Residencial",
        3: "Semi Commercial",
        4: "Inventory",
      };
      return relaventImage[id] ?? "Unknwon";
    },

    getAlarmIcons() {
      return {
        intruder: {
          color: "#ff0000",
          text: "Alarm",
          image:
            process.env.BACKEND_URL2 + "/google_map_icons/google_alarm.png",
          icon: "mdi-alarm",
        },
        alarm: {
          color: "#ff0000",
          text: "Alarm",
          image:
            process.env.BACKEND_URL2 + "/google_map_icons/google_alarm.png",
          icon: "mdi-alarm",
        },
        temperature: {
          color: "#ff0000",
          text: "Fire Alarm",
          image:
            process.env.BACKEND_URL2 +
            "/google_map_icons/google_temperature_alarm.png",
          icon: "mdi-alarm",
        },
        fire: {
          color: "#ff0000",
          text: "Fire Alarm",
          image:
            process.env.BACKEND_URL2 +
            "/google_map_icons/google_fire_alarm.png",
          icon: "mdi-alarm",
        },
        water: {
          color: "#ff0000",
          text: "Water Alarm",
          image:
            process.env.BACKEND_URL2 +
            "/google_map_icons/google_water_alarm.png",
          icon: "mdi-alarm",
        },

        sos: {
          color: "#ff0000",
          text: "SOS Alarm",
          image:
            process.env.BACKEND_URL2 + "/google_map_icons/google_sos_alarm.png",
          icon: "mdi-alarm",
        },
        medical: {
          color: "#ff0000",
          text: "Medical Alarm",
          image:
            process.env.BACKEND_URL2 +
            "/google_map_icons/google_medical_alarm.png",
          icon: "mdi-alarm",
        },
        offline: {
          color: "#626262",
          text: "Offline",
          image:
            process.env.BACKEND_URL2 + "/google_map_icons/google_offline.png",
          icon: "mdi-download-network-outline",
        },
        closed: {
          color: "#626262",
          text: "Closed",
          image:
            process.env.BACKEND_URL2 + "/google_map_icons/google_offline.png",
          icon: "mdi-download-network-outline",
        },
        armed: {
          color: "#00930b",
          text: "Armed",
          image:
            process.env.BACKEND_URL2 + "/google_map_icons/google_armed.png",
          icon: "mdi-lock",
        },
        disarm: {
          color: "#ff0000",
          text: "Disarm",
          image:
            process.env.BACKEND_URL2 + "/google_map_icons/google_disarm.png",
          icon: "mdi-lock-open",
        },
        ac_off: {
          color: "#ff0000",
          text: "Power Loss",
          image:
            process.env.BACKEND_URL2 + "/google_map_icons/google_ac_off.png",
          icon: "mdi-lock-open",
        },
        dc_off: {
          color: "#ff0000",
          text: "Power Loss",
          image:
            process.env.BACKEND_URL2 + "/google_map_icons/google_dc_off.png",
          icon: "mdi-lock-open",
        },
        battery: {
          color: "#ff0000",
          text: "Power Loss",
          image:
            process.env.BACKEND_URL2 + "/google_map_icons/google_dc_off.png",
          icon: "mdi-lock-open",
        },
      };
    },
    getRelaventMarkers(alarm) {
      let relaventImage = {
        Intruder: "/alarm-icons/intruder.png",
        Burglary: "/alarm-icons/burglary.png",
        Medical: "/alarm-icons/medical.png",
        Fire: "/alarm-icons/fire.png",
        Water: "/alarm-icons/water.png",
        Temperature: "/alarm-icons/temperature.png",
        Humidity: "/alarm-icons/humidity.png",
      };
      if (relaventImage[alarm.alarm_type])
        return relaventImage[alarm.alarm_type];
      else return null;
      // Get the image URL based on the alarm key, if exists
      let selectedImage = relaventImage[alarm];

      // If the alarm type is unknown, randomly select an image from the available options
      if (!selectedImage) {
        const imageLinks = Object.values(relaventImage);
        selectedImage =
          imageLinks[Math.floor(Math.random() * imageLinks.length)];
      }

      return selectedImage;
    },

    getRelaventImage(alarm) {
      let relaventImage = {
        Intruder: "/device-icons/burglary.png",
        Burglary: "/device-icons/burglary.png",
        Medical: "/device-icons/medical.png",
        Fire: "/device-icons/fire.png",
        Water: "/device-icons/water.png",
        Temperature: "/device-icons/temperature.png",
        //Humidity: "/device-icons/humidity.png",
        Offline: "/device-icons/offline.png",
      };
      // Get the image URL based on the alarm key, if exists
      let selectedImage = relaventImage[alarm];

      // If the alarm type is unknown, randomly select an image from the available options
      if (!selectedImage) {
        const imageLinks = Object.values(relaventImage);
        selectedImage =
          imageLinks[Math.floor(Math.random() * imageLinks.length)];
      }

      return selectedImage;
    },
    getRelaventCategoryColor(category) {
      let relaventImage = {
        Critical: "red",
        Low: "primary",
        High: "",
        Medium: "orange",
      };
      return relaventImage[category] ?? "grey";
    },
  });
};
