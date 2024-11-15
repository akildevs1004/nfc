<template>
  <div>
    <v-card>
      <v-row>
        <v-col> Welcome {{ display_name }}</v-col>
      </v-row>
      <v-row>
        <v-col>
          <!-- <v-btn color="primary" dense small @click="addAttendance()"
            >Manual Entry
          </v-btn> -->

          <v-btn class="mt-4" dense small @click="startNFCScan" color="primary"
            >Scan
          </v-btn>
        </v-col>
        <v-col class="text-right">
          <!-- <v-icon dense @click="getDatafromApi()" color="primary"
            >mdi-cached
          </v-icon> -->
          <v-btn dense small @click="syncWithServer" color="primary"
            >Update to Server</v-btn
          >
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <div style="color: green" v-if="outputMessage">
            <p v-html="outputMessage"></p>
          </div>
        </v-col>
      </v-row>
      <!-- <v-row>
        <v-col style="color: green"
          >Log Recorded Successfully at 12:15 PM</v-col
        >
      </v-row> -->

      <!-- <v-row v-if="localStorageRecordsCount > 0">
        <v-col style="color: green"> {{ localStorageRecordsCount }}</v-col>
      </v-row> -->
      <v-row>
        <v-col>
          <v-data-table
            :loading="loading"
            :headers="headers"
            :items="attendanceRecordsTable"
            :items-per-page="10"
            item-key="id"
            ><template v-slot:[`item.sno`]="{ item, index }">
              {{ index + 1 }}
            </template>
            <template v-slot:[`item.server_time`]="{ item, index }">
              {{ item.server_time ?? "---" }}
            </template>
            <template v-slot:[`item.nfc_location`]="{ item, index }">
              {{ item.nfc_location ?? "---" }}
            </template>
            <template v-slot:[`item.sync_status`]="{ item }">
              <v-chip
                :color="item.sync_status === 'Updated' ? 'green' : 'red'"
                dark
              >
                {{ item.sync_status }}
              </v-chip>
            </template>
          </v-data-table>
        </v-col>
      </v-row>
    </v-card>
  </div>
</template>

<script>
export default {
  layout: "guardlogin",
  components: {},
  data: () => ({
    headers: [
      { text: "#", value: "sno" },
      { text: "NFC Location", value: "nfc_location" },
      { text: "Log Time", value: "log_time" },

      { text: "Server Status", value: "sync_status" },
      { text: "Server Time", value: "server_time" },
    ],
    localStorageRecordsCount: 0,
    attendanceRecords: [],
    attendanceRecordsTable: [],
    dataController: null, // To store the current AbortController instance

    serial_number: "",
    nfc_location: "---",
    outputMessage: "",

    dialogEventsList: false,
    technician_id: null,
    loading: false,
    key: false,
    profile_percentage: 60,
    tab: null,

    _id: null,
    date_from: null,
    date_to: null,
    refreshData: true,
    display_name: "",
    recordOffline: false,
  }),
  computed: {},
  mounted() {
    this.loadLocalAttendance();

    // setTimeout(() => {
    //   this.key = 1;
    //   this.getDatafromApi();
    // }, 2000);
  },
  created() {
    // if (this.$auth.user.technician)
    //   this.technician_id = this.$auth.user.technician.id;
    // setInterval(() => {
    //   if (this.$route.name == "technician-dashboard" && this.refreshData) {
    //     this.key = this.key + 1;
    //     this.getDatafromApi();
    //   }
    // }, 1000 * 10);
    // // this._id = this.$route.params.id;
    // let today = new Date();
    // let monthObj = this.$dateFormat.monthStartEnd(today);
    // this.date_from = monthObj.first;
    // this.date_to = monthObj.last;

    this.serial_number = "1234";
    this.nfc_location = "---";
    setInterval(() => {
      this.syncWithServer();
      //this.startNFCScan();

      const storedRecords = localStorage.getItem("attendanceRecords");
      let attendanceRecords = storedRecords ? JSON.parse(storedRecords) : [];
      this.localStorageRecordsCount = attendanceRecords.length;
    }, 1000 * 15);

    this.getDatafromApi();
    this.display_name =
      this.$auth.user.first_name + " " + this.$auth.user.last_name;
  },
  watch: {},
  methods: {
    addAttendance() {
      this.loading = true;
      this.addAttendanceRecord({
        id: Date.now(),
        user_id: this.$auth.user.id,
        log_time: this.getNowDateformat(),
        serial_number: this.serial_number,
        nfc_location: "---",
      });
    },
    loadLocalAttendance() {
      const storedRecords = localStorage.getItem("attendanceRecords");
      this.attendanceRecords = storedRecords ? JSON.parse(storedRecords) : [];
      this.totalRecords = this.attendanceRecords.length;
    },
    saveLocalAttendance() {
      this.attendanceRecords.sort((a, b) => {
        return new Date(b.log_time) - new Date(a.log_time); // Ascending order
      });
      if (localStorage)
        localStorage.setItem(
          "attendanceRecords",
          JSON.stringify(this.attendanceRecords)
        );
    },
    getNowDateformat() {
      const date = new Date();

      const year = date.getFullYear(); // 2024
      const month = String(date.getMonth() + 1).padStart(2, "0"); // "12"
      const day = String(date.getDate()).padStart(2, "0"); // "31"
      const hours = String(date.getHours()).padStart(2, "0"); // "02"
      const minutes = String(date.getMinutes()).padStart(2, "0"); // "22"
      const seconds = String(date.getSeconds()).padStart(2, "0"); // "53"

      return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`; // Output: "2024-12-31 02:22:53"
    },
    addAttendanceRecord(record) {
      // const isDuplicate = this.attendanceRecords.some(
      //   (new1) =>
      //     new1.user_id === record.user_id &&
      //     new1.serial_number === record.serial_number &&
      //     new1.log_time === record.log_time
      // );

      // if (isDuplicate) {
      //   //console.log("Duplicate Found");

      //   this.outputMessage = "Duplicate Found";

      //   return; // Exit the method if duplicate is found
      // }

      record.sync_status = record.sync_status ?? "Pending";
      this.attendanceRecords.push(record);
      this.saveLocalAttendance();
      this.totalRecords = this.attendanceRecords.length;
      this.outputMessage = "Log Recorded ";
      this.syncWithServer();
      this.getDatafromApi();
      this.loading = false;
    },
    async syncWithServer() {
      this.loading = true;
      try {
        // Filter records with "Pending" status
        const unsyncedRecords = this.attendanceRecords.filter(
          (record) => record.sync_status === "Pending"
        );
        if (unsyncedRecords.length == 0) {
          this.outputMessage = "All logs are updated ";

          localStorage.removeItem("attendanceRecords");
        }

        for (const record of unsyncedRecords) {
          const recordToSync = {
            company_id: this.$auth.user.company_id,
            ...record,
          };

          try {
            const response = await this.$axios.post(
              "sync_guard_logs_server",
              recordToSync
            );

            console.log("Response ", response);

            // Check response status and update sync status based on record id
            if (response.data.status) {
              // Find the original record in attendanceRecords by id
              const originalRecord = this.attendanceRecords.find(
                (r) => r.id === record.id
              );

              if (originalRecord) {
                originalRecord.sync_status = "Updated"; // Update sync status
                originalRecord.server_time = this.getNowDateformat(); // Update sync status
                console.log(`Record ${record.id} synced successfully`);

                this.outputMessage =
                  "Log updated to Server Successfully at  " +
                  originalRecord.server_time;

                // Find the record index in attendanceRecords
                const index = this.attendanceRecords.findIndex(
                  (r) => r.id === record.id
                );

                if (index !== -1) {
                  // Remove record from the component's data
                  this.attendanceRecords.splice(index, 1);

                  // Save updated records back to localStorage
                  localStorage.setItem(
                    "attendanceRecords",
                    JSON.stringify(this.attendanceRecords)
                  );

                  console.log(
                    `Record ${record.id} synced and removed locally.`
                  );
                }
              }
            } else {
              console.error("Sync failed for record", record.id);
            }
          } catch (error) {
            console.error("Network error for record", record.id, error);

            this.attendanceRecordsTable = [...this.attendanceRecords, record];
            this.optimiseTable();
          }
        }

        // Save all updated records to local storage once at the end
        this.saveLocalAttendance();
      } catch (error) {
        console.error("Error syncing data:", error);
      }
      this.loading = false;

      this.getDatafromApi();
    },

    updateOptions(options) {
      this.options = options;
    },
    stopRefreshdata() {
      this.refreshData = false;
    },
    startRefreshdata() {
      this.refreshData = true;
      this.key = this.key + 1;
      this.getDatafromApi();
    },
    getPercentage($key) {
      let test =
        (this.onlineStats.Temperature.online * 100) /
        (this.onlineStats.Temperature.online,
        this.onlineStats.Temperature.offline);

      return test;
    },

    async startNFCScan() {
      // Check if Web NFC API is supported
      if (!("NDEFReader" in window)) {
        this.outputMessage =
          "Web NFC Feature is not supported on this browser. ";
        return;
      }
      this.loading = true;
      try {
        // Create a new NFC reader instance
        const nfcReader = new NDEFReader();

        // Start scanning for NFC tags
        await nfcReader.scan();
        this.outputMessage = "NFC scan started. Tap an NFC tag to read data.";

        // Event listener for successful NFC read
        nfcReader.onreading = (event) => {
          this.outputMessage = `<strong>NFC Data Read Successfully:</strong><br>`;
          const { message, serialNumber } = event;

          this.serial_number = serialNumber;
          this.outputMessage += `<p>Adding Offline Record`;
          //this.recordOffline = true;
          this.addAttendance();

          // // Display NFC tag's unique ID (UID)
          // this.outputMessage += `<p><strong>NFC Tag UID (Serial Number):</strong> ${serialNumber}</p>`;

          // // Loop through all records and display them
          // message.records.forEach((record, index) => {
          //   this.outputMessage += `<h3>Record ${index + 1}</h3>`;
          //   this.outputMessage += `<p>Record Type: ${record.recordType}</p>`;

          //   // Decode the record data based on type
          //   if (record.recordType === "text") {
          //     const textDecoder = new TextDecoder();
          //     this.outputMessage += `<p>Text: ${textDecoder.decode(
          //       record.data
          //     )}</p>`;
          //   } else if (record.recordType === "url") {
          //     const url = new TextDecoder().decode(record.data);
          //     this.outputMessage += `<p>URL: ${url}</p>`;
          //   } else {
          //     // For other record types, display raw data as a fallback
          //     const rawData = new TextDecoder().decode(record.data);
          //     this.outputMessage += `<p>Data: ${rawData}</p>`;
          //   }
          // });

          // Optionally, stop scanning after reading a tag
          nfcReader.abort();
          this.outputMessage += "<p>Scan stopped after reading tag.</p>";
        };

        // Event listener for read errors
        nfcReader.onreadingerror = (error) => {
          this.outputMessage = `Error reading NFC. Please try again. Details: ${error.message}`;
        };
      } catch (error) {
        console.error("Error initiating NFC scan:", error);
        this.outputMessage = `Error: ${error.message}`;
      }
      this.loading = false;
    },

    async getDatafromApi() {
      // Cancel any ongoing request
      if (this.dataController) {
        this.dataController.abort();
      }

      // Create a new AbortController for the current request
      this.dataController = new AbortController();

      this.loading = true;
      try {
        const { data } = await this.$axios.get(`guard_tracking_logs`, {
          params: {
            per_page: 10,
            company_id: this.$auth.user.company_id,
            user_id: this.$auth.user.id,
          },
          signal: this.dataController.signal, // Attach the AbortController's signal
        });

        if (data && data.data) {
          const mappedRecords = data.data.map((element) => ({
            id: new Date(element.log_time).getTime(),
            user_id: element.user_id,
            log_time: element.log_time,
            serial_number: element.serial_number,
            nfc_location: element.device.location, // Assuming NFC location is provided
            sync_status: "Updated",
          }));

          this.attendanceRecordsTable = [
            ...this.attendanceRecords,
            ...mappedRecords.filter(
              (newRecord) =>
                !this.attendanceRecords.some(
                  (existingRecord) => existingRecord.id === newRecord.id
                )
            ),
          ];
          this.optimiseTable();
        } else {
          console.warn("No records returned from the API.");
        }
      } catch (error) {
        if (error.name === "AbortError") {
          console.log("Previous request canceled.");
        } else {
          console.error("Error fetching guard tracking logs:", error);
        }
      } finally {
        this.loading = false;
      }

      // if (this.recordOffline) {
      //   this.attendanceRecordsTable = [...this.attendanceRecordsTable, record];
      //   this.optimiseTable();
      //   this.recordOffline = false;
      // }
    },

    optimiseTable() {
      // Remove duplicates based on log_time
      this.attendanceRecordsTable = this.attendanceRecordsTable.filter(
        (record, index, self) =>
          index === self.findIndex((r) => r.log_time === record.log_time)
      );
      this.attendanceRecordsTable.sort((a, b) => {
        return new Date(b.log_time) - new Date(a.log_time); // Ascending order
      });
    },
  },
};
</script>
