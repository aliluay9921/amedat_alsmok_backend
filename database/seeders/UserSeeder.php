<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    // مبيعات
    // User::create([
    //   'full_name' => 'مبيعات',
    //   'user_name' => 'admin1@admin',
    //   'user_type' => 0,
    //   'password' => bcrypt('admin1234'),
    // ]);
    // معمل الاول
    // User::create([
    //   'full_name' => 'معمل العامرية',
    //   'user_name' => 'admin2@admin',
    //   'user_type' => 1,
    //   'password' => bcrypt('admin@1234'),
    // ]);
    // معمل الثاني
    // User::create([
    //   'full_name' => 'معمل الفروسية',
    //   'user_name' => 'admin3@admin',
    //   'user_type' => 2,
    //   'password' => bcrypt('admin@123456'),
    // ]);
    // محاسب
    // User::create([
    //   'full_name' => 'الحسابات',
    //   'user_name' => 'admin4@admin',
    //   'user_type' => 3,
    //   'password' => bcrypt('admin12345'),
    // ]);
    // User::create([
    //   'full_name' => 'مهندسين',
    //   'user_name' => 'admin5@admin',
    //   'user_type' => 5,
    //   'password' => bcrypt('admin@12345'),
    // ]);

    // User::create([
    //   'full_name' => 'الادارة',
    //   'user_name' => 'manager@admin',
    //   'user_type' => 6,
    //   'password' => bcrypt('manager1234'),
    // ]);

    // User::create([
    //   'full_name' => 'معمل الفروسية2',
    //   'user_name' => 'admin7@admin',
    //   'user_type' => 7,
    //   'password' => bcrypt('admin@1234567'),
    // ]);

    User::create([
      'full_name' => 'معمل عامرية2 - مطار',
      'user_name' => 'admin8@admin',
      'user_type' => 8,
      'password' => bcrypt('admin@12345678'),
    ]);
  }
}













// <template>
//   <v-card class="elevation-1">
//     <!-- dilog to done and print invoice -->
//     <template>
//       <v-container>
//         <v-row justify="center">
//           <v-col cols="12" sm="3">
//             <v-btn dark color="primary" to="/invoicment">الفواتير</v-btn>
//           </v-col>
//           <v-col cols="12" sm="3">
//             <v-btn dark color="primary" to="/processing"
//               >المبيعات قيد الانتضار</v-btn
//             >
//           </v-col>
//           <v-col cols="12" sm="3">
//             <v-btn dark color="primary" to="/processDone"
//               >المبيعات قيد التنفيذ</v-btn
//             >
//           </v-col>
//           <v-col cols="12" sm="3">
//             <v-btn dark color="primary" to="/doneInvoice"
//               >المبيعات المكتملة</v-btn
//             >
//           </v-col>
//         </v-row>
//       </v-container>
//     </template>
//     <template>
//       <v-container>
//         <v-row justify="center">
//           <v-dialog v-model="dialog" scrollable max-width="900px">
//             <v-card>
//               <v-card-title class="text-h5 secondary white--text">
//                 تأكيد الطلب
//               </v-card-title>
//               <v-card-text>
//                 <v-form ref="form" id="printMe">
//                   <v-col cols="12" md="12" lg="12">
//                     <v-row>
//                       <v-col sm="12" md="12" lg="12" justify="center">
//                         <div class="title text-center" justify="center">
//                           <h1
//                             style="
//                               margin: 0 auto;
//                               border-top: 1px dashed #bbb;
//                               padding: 10px;
//                               text-align: center;
//                             "
//                           >
//                             شركة اعمدة الشموخ للمقاولات العامة المحدودة وتجهيز
//                             الكونكريت
//                           </h1>
//                         </div>
//                         <div class="numbers" justify="center">
//                           <p
//                             style="
//                               margin: 0 auto;
//                               padding: 10px;
//                               text-align: center;
//                             "
//                           >
//                             <b>07711119970-07811119970-07705333603</b>
//                           </p>
//                         </div>
//                         <hr />
//                         <div>
//                           <p
//                             style="
//                               margin: 0 auto;
//                               padding: 10px;
//                               text-align: right;
//                             "
//                           >
//                             <span style="padding-left: 75px">
//                               <b>رقم الفاتورة : {{ invoicement_no + 1 }}</b>
//                             </span>
//                             <span style="padding-left: 75px">
//                               <b>التسلسل: </b>
//                             </span>
//                             <span style="padding-left: 75px"
//                               ><b
//                                 >التأريخ :
//                                 {{ new Date() | moment("DD.MM.YYYY") }}
//                               </b></span
//                             >

//                             <span style="padding-left: 55px">
//                               <b
//                                 >الوقت : {{ new Date() | moment(" h:mm:ss") }}
//                               </b>
//                             </span>
//                           </p>
//                         </div>
//                       </v-col>
//                     </v-row>
//                     <v-row dir="rtl">
//                       <div class="col-4 d-block">
//                         <div class="details">
//                           <div class="title">
//                             <label for="" color="primary"> اسم السائق</label>
//                           </div>
//                           <div class="data">
//                             <input
//                               type="text"
//                               style="text-align: center"
//                               v-model="driver_name"
//                             />
//                           </div>
//                         </div>
//                         <div class="details">
//                           <div class="title">
//                             <label for=""> رقم السيارة</label>
//                           </div>
//                           <div class="data">
//                             <input
//                               type="text"
//                               style="text-align: center"
//                               v-model="car_number"
//                             />
//                           </div>
//                         </div>
//                         <div class="details">
//                           <div class="title">
//                             <label for="" style="padding-left: 35px">
//                               الكمية
//                             </label>
//                           </div>
//                           <div class="data">
//                             <input
//                               type="text"
//                               style="text-align: center"
//                               v-model="quantity_car"
//                             />
//                           </div>
//                         </div>
//                         <div class="details">
//                           <div class="title">
//                             <label for="" style="padding-left: 35px">
//                               النوع
//                             </label>
//                           </div>
//                           <div class="data">
//                             <p>{{ type }}</p>
//                           </div>
//                         </div>
//                         <div class="details">
//                           <div class="title">
//                             <label for="" style="padding-left: 35px">
//                               اسم الموقع
//                             </label>
//                           </div>
//                           <div class="data">
//                             <p>{{ place }}</p>
//                           </div>
//                         </div>
//                         <div class="details">
//                           <div class="title">
//                             <label for="" style="padding-left: 35px">
//                               اسم المستلم
//                             </label>
//                           </div>
//                           <div class="data">
//                             <p>{{ name_customer }}</p>
//                           </div>
//                         </div>
//                         <div class="details">
//                           <div class="title">
//                             <label for="" style="padding-left: 35px">
//                               اسم المجهز
//                             </label>
//                           </div>
//                           <div class="data">
//                             <p>{{ full_name }}</p>
//                           </div>
//                         </div>
//                         <div class="details">
//                           <div class="title">
//                             <label for="" style="padding-left: 35px">
//                               اسم المندوب
//                             </label>
//                           </div>
//                           <div class="data">
//                             <p>{{ name_representative }}</p>
//                           </div>
//                         </div>
//                       </div>
//                     </v-row>
//                     <v-row>
//                       <div class="signthure">
//                         <p>التوقيع</p>
//                       </div>
//                     </v-row>
//                   </v-col>
//                 </v-form>
//               </v-card-text>
//               <v-card-actions>
//                 <v-spacer></v-spacer>

//                 <v-col cols="auto">
//                   <v-btn
//                     secondary
//                     color="secondary"
//                     @click="add_process"
//                     v-print="'#printMe'"
//                   >
//                     طباعة
//                   </v-btn>
//                 </v-col>
//                 <v-col cols="auto">
//                   <v-btn secondary color="secondary" @click="doneInvoice">
//                     انهاء الحساب
//                   </v-btn>
//                 </v-col>
//                 <v-col cols="auto">
//                   <v-btn secondary color="secondary" @click="close">
//                     غلق
//                   </v-btn>
//                 </v-col>
//               </v-card-actions>
//             </v-card>
//           </v-dialog>
//         </v-row>
//       </v-container>
//     </template>
//     <v-data-table
//       :headers="headers"
//       :items="salesProcessDone"
//       :search="search"
//       loading-text="جاري التحميل يرجى الأنتظار"
//     >
//       <template v-slot:item="{ item }">
//         <tr>
//           <td class="text-start" v-if="item.status == 0">
//             <v-chip color="yellow">قيد المراجعة</v-chip>
//           </td>
//           <td class="text-start" v-else-if="item.status == 1">
//             <v-chip color="green">تم الترحيل </v-chip>
//           </td>
//           <td class="text-start" v-else-if="item.status == 2">
//             <v-chip color="info">رحلت من المعمل</v-chip>
//           </td>
//           <td class="text-start" v-else-if="item.status == 3">
//             <v-chip color="gray">قيد التفيذ</v-chip>
//           </td>
//           <td class="text-start">{{ item.place }}</td>
//           <td class="text-start">{{ item.name_customer }}</td>
//           <td class="text-start">{{ item.type }}</td>
//           <td class="text-start">{{ item.quantity }}</td>
//           <td class="text-start">{{ item.man_buliding }}</td>
//           <td class="text-start">{{ item.workers }}</td>
//           <td class="text-start">{{ item.bump }}</td>
//           <td class="text-start">{{ item.name_representative }}</td>
//           <td class="text-start">{{ item.phone_number }}</td>
//           <td class="text-start">{{ item.price }}</td>
//           <td class="text-start">{{ item.actual_quantity }}</td>
//           <td class="text-start mr-5 ml-5">{{ item.date }}</td>

//           <td class="text-start">{{ item.time }}</td>
//           <td class="text-start">{{ item.notes }}</td>

//           <td class="text-start">
//             <v-btn dark color="yellow" @click="done(item)"
//               >اكمال عملية التنفيذ</v-btn
//             >
//           </td>
//         </tr>
//       </template>
//       <template v-slot:top>
//         <v-toolbar>
//           <v-divider class="mx-4" inset vertical></v-divider>
//           <v-spacer></v-spacer>
//           <v-text-field
//             v-model="search"
//             append-icon="mdi-magnify"
//             label="بحث"
//             single-line
//             hide-details
//           ></v-text-field>
//         </v-toolbar>
//       </template>
//     </v-data-table>
//   </v-card>
// </template>
// <script>
// export default {
//   data() {
//     return {
//       search: "",
//       rules: [(value) => !!value || "هذا الحقل مطلوب."],
//       driver_name: "",
//       sale_category_id: "",
//       car_number: "",
//       actual_quantity: "",
//       quantity_car: "",
//       name_representative: "",
//       phone_number: "",
//       place: "",
//       date: "",
//       time: "",
//       type: "",
//       employee: "",
//       name_customer: "",
//       dialog: false,
//       item: {},
//       printObj: {
//         id: "print_me",
//         popTitle: "طباعة سند قبض/صرف",
//         extraHead: '<meta http-equiv="Content-Language"content="en-ar"/>',
//       },
//       headers: [
//         {
//           text: "الحالة",
//           value: "status",
//           align: "start",
//           class: "secondary white--text title ",
//         },
//         {
//           text: "الموقع ",
//           value: "place",
//           align: "start",
//           class: "secondary white--text title",
//         },
//         {
//           text: "الزبون",
//           value: "name_customer",
//           align: "start",
//           align: "start",
//           class: "secondary white--text title  ",
//         },
//         {
//           text: "النوع",
//           value: "type",
//           align: "start",
//           class: "secondary white--text title",
//         },
//         {
//           text: "الكمية",
//           value: "quantity",
//           align: "start",
//           class: "secondary white--text title",
//         },
//         {
//           text: "الخلفات",
//           value: "man_buliding",
//           align: "start",
//           class: "secondary white--text title",
//         },
//         {
//           text: "العمال",
//           value: "workers",
//           align: "start",
//           class: "secondary white--text title",
//         },
//         {
//           text: "البم",
//           value: "bump",
//           align: "start",
//           class: "secondary white--text title",
//         },
//         {
//           text: "المندوب",
//           value: "name_representative",
//           align: "start",
//           class: "secondary white--text title",
//         },
//         {
//           text: "الهاتف",
//           value: "phone_number",
//           align: "center",
//           align: "start",
//           class: "secondary white--text title",
//         },
//         {
//           text: "السعر",
//           value: "price",
//           align: "start",
//           class: "secondary white--text title",
//         },
//         {
//           text: " الكمية الفعلية",
//           value: "actual_quantity",
//           align: "start",
//           class: "secondary white--text title",
//         },
//         {
//           text: "التأريخ",
//           value: "date",
//           align: "start",
//           class: "secondary white--text title",
//         },
//         {
//           text: "الوقت",
//           value: "time",
//           align: "start",
//           class: "secondary white--text title",
//         },
//         {
//           text: "الملاحضات",
//           value: "notes",
//           align: "start",
//           class: "secondary white--text title",
//         },

//         {
//           text: "ترحيل",
//           align: "start",
//           class: "secondary white--text title",
//         },
//       ],
//     };
//   },
//   computed: {
//     full_name() {
//       return localStorage.getItem("full_name");
//     },
//     salesProcessDone() {
//       return this.$store.state.saleCategory.sales_process_done;
//     },

//     table_loading() {
//       return this.$store.state.saleCategory.table_loading;
//     },
//     invoicement_no() {
//       return this.$store.state.processing.invoicemnts.length;
//       // state.invoicemnts.length
//     },
//   },
//   methods: {
//     close() {
//       this.dialog = false;
//       // location.reload();
//     },
//     doneInvoice() {
//       this.$store.dispatch("saleCategory/doneInvoice", this.sale_category_id);
//       this.dialog = false;
//     },

//     add_process() {
//       let data = {};
//       data["sale_category_id"] = this.sale_category_id;
//       data["driver_name"] = this.driver_name;
//       data["car_number"] = this.car_number;
//       data["quantity_car"] = this.quantity_car;
//       data["invoice_no"] = this.invoicement_no + 1;
//       data["sequence"] = 0;

//       this.$store.dispatch("processing/addInvoicemnt", data);
//       this.$store.dispatch("processing/getInvoicemnts");
//     },
//     done(item) {
//       console.log("here");
//       this.$store.dispatch("processing/getInvoicemnts");

//       this.dialog = true;
//       this.sale_category_id = item.id;
//       this.name_representative = item.name_representative;
//       this.phone_number = item.phone_number;
//       this.place = item.place;
//       this.date = item.date;
//       this.time = item.time;
//       this.type = item.type;
//       this.name_customer = item.name_customer;
//       this.employee = item.employee.full_name;
//     },
//     getSalesDone() {
//       this.$store.dispatch("saleCategory/getSalesDone");
//       this.$store.dispatch("processing/getInvoicemnts");
//     },
//   },
//   created() {
//     this.getSalesDone();
//   },
//   watch: {
//     invoicement_no() {
//       return this.$store.state.processing.invoicemnts.length;
//     },
//   },
// };
// </script>
// <style>
// /* هاي تخلي الهدر مرتب كلة */
// .v-data-table-header th {
//   white-space: nowrap;
// }

// .details {
//   font-weight: bold;
//   display: flex;
//   flex-direction: row;
//   padding: 5px;
//   max-height: 50px;
//   width: 350px;
//   text-align: center;
// }

// .details .title {
//   border: 1px solid black;
//   padding: 6px;
//   margin-left: 10px;
//   font-weight: bold;
//   border-radius: 5px;
//   text-align: center;
//   max-width: 175px;
//   min-width: 175px;
//   /* background-color: rgba(17, 56, 183, 0.372); */
// }
// .details .data {
//   border: 1px solid black;
//   font-weight: bold;
//   border-radius: 5px;
//   padding: 5px;
//   min-width: 225px;
// }
// .details .data :focus {
//   outline: none;
// }
// .numbers {
//   font-size: 22px;
//   border-radius: 10px;
//   /* padding: 20px; */
//   /* margin-top: 30px; */
//   /* text-align: right; */
// }

// .signthure {
//   font-family: 900 bold;
//   padding-bottom: 100px;
// }
// </style>