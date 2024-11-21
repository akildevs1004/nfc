<template>
  <div>
    <div class="text-center ma-2">
      <v-snackbar v-model="snackbar" top="top" color="secondary" elevation="24">
        {{ response }}
      </v-snackbar>
    </div>
    <v-card class="elevation-2">
      <v-card-text>
        <v-row>
          <v-col
            style="padding-left: 4px; padding-right: 4px; max-width: 150px"
          >
            <v-text-field
              :disabled="!editable"
              label="Route Name"
              hide-details
              outlined
              dense
              v-model="routeName"
            />
          </v-col>
          <v-col
            style="padding-left: 4px; padding-right: 4px; max-width: 120px"
          >
            <v-select
              :disabled="!editable"
              class="pb-0"
              hide-details
              v-model="routeStartHour"
              outlined
              dense
              label="Start Hour"
              :items="oneTo23"
            ></v-select>
          </v-col>
          <v-col
            style="padding-left: 4px; padding-right: 4px; max-width: 120px"
          >
            <v-select
              :disabled="!editable"
              class="pb-0"
              hide-details
              v-model="routeStartMinute"
              outlined
              dense
              label="Start Minute"
              :items="oneTOsixty"
            ></v-select>
          </v-col>
          <v-col
            style="padding-left: 4px; padding-right: 4px; max-width: 120px"
          >
            <v-select
              :disabled="!editable"
              class="pb-0"
              hide-details
              v-model="routeEndHour"
              outlined
              dense
              label="End Hour"
              :items="oneTo23"
            ></v-select>
          </v-col>
          <v-col
            style="padding-left: 4px; padding-right: 4px; max-width: 120px"
          >
            <v-select
              :disabled="!editable"
              class="pb-0"
              hide-details
              v-model="routeEndMinute"
              outlined
              dense
              label="End Minute"
              :items="oneTOsixty"
            ></v-select> </v-col
          ><v-col
            style="padding-left: 4px; padding-right: 4px; max-width: 120px"
          >
            <v-select
              :disabled="!editable"
              class="pb-0"
              hide-details
              v-model="repeatMinutes"
              outlined
              dense
              label="Repeat"
              :items="repeatMinutesArray"
              item-text="text"
              item-value="value"
            ></v-select>
          </v-col>
          <!-- <v-col style="padding-left: 4px; padding-right: 4px">
            <v-text-field
              :disabled="!editable"
              label="Route Notes"
              hide-details
              outlined
              dense
              v-model="routeNotes"
          /></v-col> -->
          <!-- <v-col class="text-right">
        <v-btn x-small title="New" color="primary" @click="addLocation()"
          >+ Check Points
        </v-btn>
      </v-col> -->
        </v-row>

        <v-row>
          <v-col>
            <v-checkbox
              :disabled="!editable"
              v-model="monCheckbox"
              :label="`Mon`"
            ></v-checkbox> </v-col
          ><v-col>
            <v-checkbox
              :disabled="!editable"
              v-model="tueCheckbox"
              :label="`Tue`"
            ></v-checkbox> </v-col
          ><v-col>
            <v-checkbox
              :disabled="!editable"
              v-model="wedCheckbox"
              :label="`Wed`"
            ></v-checkbox> </v-col
          ><v-col>
            <v-checkbox
              :disabled="!editable"
              v-model="thuCheckbox"
              :label="`Thu`"
            ></v-checkbox> </v-col
          ><v-col>
            <v-checkbox
              :disabled="!editable"
              v-model="friCheckbox"
              :label="`Fri`"
            ></v-checkbox> </v-col
          ><v-col>
            <v-checkbox
              :disabled="!editable"
              v-model="satCheckbox"
              :label="`Sat`"
            ></v-checkbox> </v-col
          ><v-col>
            <v-checkbox
              :disabled="!editable"
              v-model="sunCheckbox"
              :label="`Sun`"
            ></v-checkbox>
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <v-textarea
              outlined
              rows="1"
              label="Notes"
              v-model="routeNotes"
            ></v-textarea>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
    <br />
    <h4>Checkpoints</h4>
    <br />

    <v-row
      v-for="(d, index) in routeLocations"
      :key="index"
      style="border: 0px solid black"
    >
      <v-col style="max-width: 20px">
        {{ index + 1 }}
      </v-col>
      <v-col style="padding: 4px; max-width: 150px">
        <v-select
          :disabled="!editable"
          class="pb-0"
          hide-details
          v-model="d.device_id"
          outlined
          dense
          label="Checkpoint"
          :items="devicesList"
          item-value="id"
          item-text="location"
        ></v-select>
      </v-col>
      <!-- <v-col style="padding: 4px; max-width: 100px">
        <v-select
          :disabled="!editable"
          class="pb-0"
          hide-details
          v-model="d.start_hour"
          outlined
          dense
          label="Hour"
          :items="oneTo23"
        ></v-select>
      </v-col>
      <v-col style="padding: 4px; max-width: 100px">
        <v-select
          :disabled="!editable"
          class="pb-0"
          hide-details
          v-model="d.start_minute"
          outlined
          dense
          label="Minute"
          :items="oneTOsixty"
        ></v-select>
      </v-col> -->
      <v-col style="padding: 4px">
        <v-text-field
          :disabled="!editable"
          label="Checkpoint Notes"
          hide-details
          outlined
          dense
          v-model="d.notes"
        />
      </v-col>
      <v-col style="max-width: 50px">
        <v-icon
          :disabled="!editable"
          v-if="index == 0 && editable"
          style="color: black"
          outlined
          @click="addLocation"
          size="20"
          fab
          >mdi-plus-circle</v-icon
        >

        <v-icon
          :disabled="!editable"
          v-if="index > 0"
          dark
          fab
          style="color: black; padding-top: 10px"
          outlined
          @click="removeEarningItem(index)"
          size="20"
          >mdi-delete</v-icon
        >
      </v-col>
    </v-row>

    <v-row class="pr-3">
      <v-col cols="10" class="text-right" style="color: red">
        <!-- <v-btn class="error" small @click="cancel">Cancel</v-btn> -->
        {{ errorMessage }}
      </v-col>
      <v-col cols="2" class="text-right" v-if="editable">
        <v-btn class="primary" small @click="save_device_info"
          >{{ item ? "Update" : "Create" }}
        </v-btn>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  props: ["item", "editId", "editable"],
  data() {
    return {
      routeName: "",
      routeStartHour: "00",
      routeStartMinute: "00",
      routeEndHour: "00",
      routeEndMinute: "00",
      routeNotes: "",
      devicesList: [],

      monCheckbox: true,
      tueCheckbox: true,
      wedCheckbox: true,
      thuCheckbox: true,
      friCheckbox: true,
      satCheckbox: true,
      sunCheckbox: true,
      repeatMinutes: 0,
      deviceTypes: [],
      SensorTypes: [],
      displayEditform: false,
      loading: false,
      device_info: false,
      menu: false,
      date: false,
      snackbar: false,
      response: "",
      errors: [],
      repeatMinutesArray: [],
      zonesErrors: [],
      errorMessage: "",

      routeLocations: [],

      oneTOsixty: [],
      oneTo23: [],
    };
  },
  created() {
    for (let index = 0; index <= 60; index++) {
      this.oneTOsixty.push(index <= 9 ? "0" + index : index.toString());
    }
    for (let index = 0; index <= 23; index++) {
      this.oneTo23.push(index <= 9 ? "0" + index : index.toString());
    }
    if (this.$store.state.storeAlarmControlPanel?.DeviceTypes) {
      this.deviceTypes = this.$store.state.storeAlarmControlPanel.DeviceTypes;
    }
    if (this.$store.state.storeAlarmControlPanel?.SensorTypes) {
      this.SensorTypes = this.$store.state.storeAlarmControlPanel.SensorTypes;
    }
    if (this.item) this.getInfo();
    else this.addLocation();

    // Generate 1 to 60 minutes
    const minutes = Array.from({ length: 60 }, (_, i) => {
      const value = i + 1; // Value in minutes
      return { text: `${value} minute${value > 1 ? "s" : ""}`, value };
    });

    // Generate 2 to 24 hours (in minutes)
    const hours = Array.from({ length: 11 }, (_, i) => {
      const value = (i + 2) * 60; // Convert hours to minutes
      return { text: `${i + 2} hour${i + 2 > 1 ? "s" : ""}`, value };
    });

    // Merge the arrays
    this.repeatMinutesArray = [
      { text: "No Repeat", value: 0 },
      ...minutes,
      ...hours,
    ];

    this.getDevicesList();
  },
  computed: {},
  methods: {
    displayEdit() {
      this.displayEditform = true;
    },
    cancel() {
      this.displayEditform = false;
    },
    removeEarningItem(index) {
      this.routeLocations.splice(index, 1);
    },
    addLocation() {
      let obj = {
        device_id: "",
        start_hour: "00",
        start_minute: "00",
        notes: "",
      };
      this.routeLocations.push(obj);
    },

    async getDevicesList() {
      let options = {
        params: {
          company_id: this.$auth.user.company_id,
          order_by: "location",
        },
      };

      let { data } = await this.$axios.get("devices-list", options);
      if (data) {
        this.devicesList = data;
      }
    },
    getInfo() {
      this.loading = true;
      //let zones = this.editDevice.routeLocations;
      if (this.item) {
        this.routeName = this.item.name;

        this.routeStartHour =
          this.item.start_time?.split(":")[0].trim() ?? "00";
        this.routeStartMinute =
          this.item.start_time?.split(":")[1].trim() ?? "00";

        this.routeEndHour = this.item.end_time?.split(":")[0].trim() ?? "00";
        this.routeEndMinute = this.item.end_time?.split(":")[1].trim() ?? "00";
        this.routeNotes = this.item.notes;

        this.monCheckbox = this.item.mon;
        this.tueCheckbox = this.item.tue;
        this.wedCheckbox = this.item.wed;
        this.thuCheckbox = this.item.thu;
        this.friCheckbox = this.item.fri;
        this.satCheckbox = this.item.sat;
        this.sunCheckbox = this.item.sun;
        this.repeatMinutes = this.item.repeat_minutes;

        this.item.route_locations.forEach((element) => {
          let obj = {
            device_id: element.device_id,
            // start_hour: element.schedule_time.split(":")[0] ?? "00",
            // start_minute: element.schedule_time.split(":")[1] ?? "00",
            notes: element.notes,
          };
          this.routeLocations.push(obj);
        });
      }
      //   else {
      //     if (this.item) {
      //       if (this.item.routeLocations.length == 0) {
      //         this.addLocation();
      //       }
      //       this.editDevice.routeLocations = [];
      //     }
      //   }
    },
    can(item) {
      return true;
    },
    caps(str) {
      if (str == "" || str == null) {
        return "---";
      } else {
        let res = str.toString();
        return res.replace(/\b\w/g, (c) => c.toUpperCase());
      }
    },

    save_device_info() {
      this.errors = [];

      let payload = {
        route_checkpoints: this.routeLocations.filter(
          (d) => d.device_id !== ""
        ),

        company_id: this.$auth?.user?.company?.id,
        route_name: this.routeName,
        route_start_hour: this.routeStartHour,
        route_start_minute: this.routeStartMinute,
        route_end_hour: this.routeEndHour,
        route_end_minute: this.routeEndMinute,
        route_notes: this.routeNotes,

        mon_checkbox: this.monCheckbox,
        tue_checkbox: this.tueCheckbox,
        wed_checkbox: this.wedCheckbox,
        thu_checkbox: this.thuCheckbox,
        fri_checkbox: this.friCheckbox,
        sat_checkbox: this.satCheckbox,
        sun_checkbox: this.sunCheckbox,
        repeat_minutes: this.repeatMinutes,

        route_id: this.editId ?? null,
      };
      this.errorMessage = "";
      this.loading = true;
      this.$axios
        .post(`/guard_route_checkpoints_update`, payload)
        .then(({ data }) => {
          this.loading = false;

          if (!data.status) {
            this.errors = data.errors;
            if (data.message) {
              this.snackbar = true;
              this.response = data.message;
              this.errorMessage = this.response;
            }

            data.errors.forEach((element) => {
              this.errorMessage = element[0];
              this.snackbar = true;
              this.response = this.errorMessage;
              return;
            });
          } else {
            this.snackbar = true;
            this.response = data.message;
            //this.device = data.record || { zones: [] };
            //this.close_device_info();
            setTimeout(() => {
              this.$emit("closeDialog");
            }, 1000);
          }
        })
        .catch((e) => {
          this.errors = [];
          console.log(e);

          this.errorMessage = e.response.data.message;
          this.snackbar = true;
          this.response = this.errorMessage;
        });
    },
    close_device_info() {
      this.device_info = false;
      this.errors = [];
      this.cancel();
      this.$emit("close-popup");
    },
  },
};
</script>
