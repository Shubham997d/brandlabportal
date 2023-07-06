<template>
  <div class="deal-invoice">
    <div class="invoice-tab">
      <b-card no-body>
        <b-tabs pills card>
          <b-tab title="Create Invoice" active>
            <form
            ref="form_1"
              @submit.prevent="onSubmitDealInvoice"
              class="deal-invoice-form"

            >
              <div class="row" v-if="invoiceBankFieldsExists == true">
                <div class="col-sm-6">
                  <label for="name" class="text-sm text-gray-700"
                    >{{ "Invoice Type" | localize }}
                    <span class="astrik"> * </span>
                  </label>
                  <select
                    v-model="formInvoice.invoice_type"
                    name="invoiceType"
                    id="invoiceType"
                    class="form-control"
                  >
                    <option
                      v-for="option in invoicetype"
                      :value="option.value"
                      :key="option.value"
                    >
                      {{ option.text }}
                    </option>
                  </select>
                  <div
                    class="error"
                    v-if="
                      displayErrorMessage($v.formInvoice.invoice_type.required)
                    "
                  >
                    {{ validationErrorMessages.required }}.
                  </div>
                </div>
                <div class="col-sm-6 dropdown-vertical-stacking">
                  <label for="country" class="text-sm text-gray-700"
                    >{{ "Country" | localize }}
                    <span class="astrik"> * </span>
                  </label>
                  <model-select
                    :options="Country"
                    v-model="formInvoice.country"
                    placeholder="Country"
                    class="form-control"
                    option-value="value"
                    option-text="text"
                  ></model-select>
                  <div
                    class="error"
                    v-if="displayErrorMessage($v.formInvoice.country.required)"
                  >
                    {{ validationErrorMessages.required }}.
                  </div>
                </div>
                <div class="col-sm-6">
                  <label for="name" class="text-sm text-gray-700"
                    >{{ "Customer Name" | localize }}
                    <span class="astrik"> * </span>
                  </label>
                  <input
                    v-model="formInvoice.customer_name"
                    maxlength="50"
                    id="name"
                    ref="focusInput"
                    class="form-control"
                    type="text"
                  />
                  <div
                    class="error"
                    v-if="
                      displayErrorMessage($v.formInvoice.customer_name.required)
                    "
                  >
                    {{ validationErrorMessages.required }}.
                  </div>
                   <div
                    class="error"
                    v-if="
                      displayErrorMessage($v.formInvoice.customer_name.alpha)
                    "
                  >
                  This field accepts Alphabets only.
                  </div>
                </div>
                <div class="col-sm-6">
                  <label for="Customer_company" class="text-sm text-gray-700"
                    >{{ "Customer Company" | localize }}
                    <span class="astrik"> * </span>
                  </label>
                  <input
                    v-model="formInvoice.customer_company"
                    id="Customer_company"
                    ref="focusInput"
                    class="form-control"
                    type="text"
                  />
                  <div
                    class="error"
                    v-if="
                      displayErrorMessage(
                        $v.formInvoice.customer_company.required
                      )
                    "
                  >
                    {{ validationErrorMessages.required }}.
                  </div>
                   <div
                    class="error"
                    v-if="
                      displayErrorMessage($v.formInvoice.customer_company.alpha)
                    "
                  >
                  This field accepts Alphabets only.
                  </div>
                </div>
                <div class="col-sm-6">
                  <label for="address" class="text-sm text-gray-700"
                    >{{ "Customer Address" | localize }}
                    <span class="astrik"> * </span>
                  </label>
                  <input
                    v-model="formInvoice.customer_address"
                    id="address"
                    ref="focusInput"
                    class="form-control"
                    type="text"
                  />
                  <div
                    class="error"
                    v-if="
                      displayErrorMessage(
                        $v.formInvoice.customer_address.required
                      )
                    "
                  >
                    {{ validationErrorMessages.required }}.
                  </div>
                </div>
                <div class="col-sm-6">
                  <label for="customer_city" class="text-sm text-gray-700"
                    >{{ "Customer City" | localize }}
                    <span class="astrik"> * </span>
                  </label>
                  <input
                    v-model="formInvoice.customer_city"
                    id="customer_city"
                    ref="focusInput"
                    class="form-control"
                    type="text"
                  />
                  <div
                    class="error"
                    v-if="
                      displayErrorMessage($v.formInvoice.customer_city.required)
                    "
                  >
                    {{ validationErrorMessages.required }}.
                  </div>
                   <div
                    class="error"
                    v-if="
                      displayErrorMessage($v.formInvoice.customer_city.alpha)
                    "
                  >
                  This field accepts Alphabets only.
                  </div>
                </div>
                <div class="col-sm-6">
                  <label for="postal_code" class="text-sm text-gray-700"
                    >{{ "Postal/ZIP Code" | localize }}
                    <span class="astrik"> * </span>
                  </label>
                  <input
                    v-model="formInvoice.postal_code"
                    id="postal_code"
                    ref="focusInput"
                    class="form-control"
                    type="text"
                  />
                  <div
                    class="error"
                    v-if="
                      displayErrorMessage($v.formInvoice.postal_code.required)
                    "
                  >
                    {{ validationErrorMessages.required }}.
                  </div>
                </div>

                <div class="col-sm-6 dropdown-vertical-stacking">
                  <label for="payment" class="text-sm text-gray-700"
                    >{{ "Due Date" | localize }}
                    <span class="astrik"> * </span>
                  </label>
                  <datepicker
                    lazy
                    v-model="formInvoice.due_date"
                    ref="due_date"
                    :placeholder="$options.filters.localize('Select Due Date')"
                    :format="customFormatter"
                    input-class="form-control"
                  ></datepicker>
                  <div
                    class="error"
                    v-if="displayErrorMessage($v.formInvoice.due_date.required)"
                  >
                    {{ validationErrorMessages.required }}.
                  </div>
                </div>

                <div class="col-sm-6">
                  <label for="vatnumber" class="text-sm text-gray-700"
                    >{{ "VAT Number" | localize }}
                    <span class="astrik"> * </span>
                  </label>
                  <input
                    v-model="formInvoice.vat_number"
                    class="form-control"
                    type="text"
                  />
                  <div
                    class="error"
                    v-if="
                      displayErrorMessage($v.formInvoice.vat_number.required)
                    "
                  >
                    {{ validationErrorMessages.required }}.
                  </div>
                   <div
                    class="error"
                    v-if="
                      displayErrorMessage($v.formInvoice.vat_number.numeric)
                    "
                  >
                  This field accepts numeric only.
                  </div>
                </div>
                <div class="col-sm-6">
                  <label for="invoicenumber" class="text-sm text-gray-700"
                    >{{ "Invoice Number" | localize }}
                    <span class="astrik"> * </span>
                  </label>
                  <input
                    v-model="formInvoice.invoice_number"
                    class="form-control"
                    type="text"
                  />
                  <div
                    class="error"
                    v-if="
                      displayErrorMessage(
                        $v.formInvoice.invoice_number.required
                      )
                    "
                  >
                    {{ validationErrorMessages.required }}.
                  </div>
                </div>
                <div class="col-sm-6">
                  <label for="reference" class="text-sm text-gray-700"
                    >{{ "Reference" | localize }}
                    <span class="astrik"> * </span>
                  </label>
                  <input
                    v-model="formInvoice.reference"
                    maxlength="50"
                    id="reference"
                    ref="focusInput"
                    class="form-control"
                    type="text"
                  />
                  <div
                    class="error"
                    v-if="
                      displayErrorMessage($v.formInvoice.reference.required)
                    "
                  >
                    {{ validationErrorMessages.required }}.
                  </div>
                </div>
                <div class="col-sm-6">
                  <label for="vat_percentage" class="text-sm text-gray-700"
                    >{{ "VAT Percentage" | localize }}
                    <span class="astrik"> * </span>
                  </label>
                  <input
                    v-model="formInvoice.vat_percentage"
                    id="vat_percentage"
                    ref="focusInput"
                    class="form-control"
                    type="number"
                  />
                  <div
                    class="error"
                    v-if="
                      displayErrorMessage(
                        $v.formInvoice.vat_percentage.required
                      )
                    "
                  >
                    {{ validationErrorMessages.required }}.
                  </div>
                  <div
                    class="error"
                    v-if="
                      displayErrorMessage($v.formInvoice.vat_percentage.between)
                    "
                  >
                    Must be between
                    {{ $v.formInvoice.vat_percentage.$params.between.min }} and
                    {{ $v.formInvoice.vat_percentage.$params.between.max }}
                  </div>
                </div>

                <div class="col-sm-6 dropdown-vertical-stacking">
                  <label for="invoice_date" class="text-sm text-gray-700"
                    >{{ "Invoice Date" | localize }}
                    <span class="astrik"> * </span>
                  </label>
                  <datepicker
                    lazy
                    v-model="formInvoice.invoice_date"
                    ref="invoice_date"
                    :placeholder="
                      $options.filters.localize('Select Invoice Date')
                    "
                    :format="customFormatter"
                    input-class="form-control"
                  ></datepicker>
                  <div
                    class="error"
                    v-if="
                      displayErrorMessage($v.formInvoice.invoice_date.required)
                    "
                  >
                    {{ validationErrorMessages.required }}.
                  </div>
                </div>
                <div class="col-sm-6">
                  <label for="currency" class="text-sm text-gray-700"
                    >{{ "Currency" | localize }}
                    <span class="astrik"> * </span>
                  </label>
                  <multiselect
                    id="invoiceCurrrency"
                    class="form-control"
                    v-model="formInvoice.currency"
                    :options="currencies"
                    :custom-label="CurrencyFormat"
                    placeholder=""
                    label="name"
                    value="symbol"
                    track-by="name"
                  ></multiselect>
                  <div
                    class="error"
                    v-if="displayErrorMessage($v.formInvoice.currency.required)"
                  >
                    {{ validationErrorMessages.required }}.
                  </div>
                </div>
                <div
                  class="invoice-fields"
                  v-if="
                    formInvoice.invoice_type == fieldsInvoiceConstants.Invoice
                  "
                >
                  <div
                    class="col-sm-12"
                    v-for="(invoice, index) in Invoice"
                    :key="index"
                  >
                    <div class="row">
                      <div class="col-sm-4">
                        <input
                          class="form-control"
                          type="text"
                          :name="`Invoice[${index}][description]`"
                          v-model="invoice.description"
                          placeholder="Description"
                          required
                        />
                      </div>
                      <div class="col-sm-4">
                        <input
                          class="form-control"
                          type="number"
                          min="0"
                          :name="`Invoice[${index}][quantity]`"
                          v-model="invoice.quantity"
                          placeholder="Quantity"
                          required
                        />
                      </div>
                      <div class="col-sm-4">
                        <input
                          class="form-control"
                          type="number"
                          min="0"
                          :name="`Invoice[${index}][amount]`"
                          v-model="invoice.amount"
                          placeholder="Amount"
                          required
                        />
                      </div>
                    </div>
                    <a
                      href="javascript:void(0);"
                      v-show="index > 0"
                      @click="removefields(index)"
                      class="delete-button"
                    >
                      <font-awesome-icon :icon="faTrashAlt"></font-awesome-icon
                    ></a>
                  </div>
                  <div class="form-group">
                    <button
                      @click="addExperience"
                      type="button"
                      class="main-button"
                    >
                      <font-awesome-icon
                        :icon="faPlus"
                        class="cursor-pointer text-sm"
                      ></font-awesome-icon>
                    </button>
                  </div>
                </div>
              </div>
              <div class="invoice-submit">
                <button type="submit" class="main-button">
                  {{ "Generate invoice" | localize }}
                </button>
              </div>
            </form>
          </b-tab>

          <b-tab title="Invoice List">
            <div class="invoice_list_table" >
               <!-- <b-table striped hover :items="listItems" :fields="listFields"></b-table> -->
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Invoice Number</th>
                    <th scope="col">Invoice Date</th>
                    <th scope="col">Due Date</th>
                    <th scope="col">Download Link</th>
                  </tr>
                </thead>
                <tbody v-if="invoiceList.length > 0 && invoiceList != null">
                  <tr   v-for="invoice in invoiceList" :key="invoice.id">
                    <td>{{invoice.id}}</td>
                    <td>{{invoice.invoice_number}}</td>
                    <td>{{dateFormat(invoice.invoice_date)}}</td>
                    <td>{{dateFormat(invoice.content.due_date)}}</td>
                    <td><button type="button" class="btn main-button"><a v-bind:href="storageConstants + invoice.file_name"  target="_blank">Download Invoice</a></button></td>
                  </tr>
       
                </tbody>
                <tbody v-if="invoiceList.length == 0 || invoiceList == null">

              <!-- <div class="text-center my-2 empty-body">There are no records to show</div> -->
              <tr role="row" class="b-table-empty-row"><td colspan="5" role="cell" class=""><div role="alert" aria-live="polite"><div class="text-center my-2">There are no records to show</div></div></td></tr>
                </tbody>
              </table>
            </div>
            
          </b-tab>

          <b-tab title="Default Details">
            <form
              @submit.prevent="onSubmitDefaultDetails"
              class="deal-invoice-form"
            >
              <div class="row">
                <div class="col-sm-6">
                  <label for="formtype" class="text-sm text-gray-700"
                    >{{ "Form Type" | localize }}
                    <span class="astrik"> * </span>
                  </label>
                  <select
                    v-model="defaultFields.field_type"
                    name="formtype"
                    id="formtype"
                    class="form-control"
                  >
                    <option
                      v-for="option in defaultFormType"
                      :value="option.value"
                      :key="option.value"
                    >
                      {{ option.text }}
                    </option>
                  </select>
                  <div
                    class="error"
                    v-if="
                      displayErrorMessageDefaultFields(
                        $v.defaultFields.field_type.required
                      )
                    "
                  >
                    {{ validationErrorMessages.required }}.
                  </div>
                </div>

                <div
                  class="col-sm-6"
                  v-if="
                    defaultFields.field_type == defaultFieldInvocie.Bank_Form
                  "
                >
                  <label for="bank" class="text-sm text-gray-700"
                    >{{ "Bank Name" | localize }}
                    <span class="astrik"> * </span>
                  </label>
                  <input
                    v-model="defaultFields.bankFields.bank"
                    maxlength="50"
                    id="bank"
                    ref="focusInput"
                    class="form-control"
                    type="text"
                  />
                  <div
                    class="error"
                    v-if="
                      displayErrorMessageDefaultFields(
                        $v.defaultFields.bankFields.bank.required
                      )
                    "
                  >
                    {{ validationErrorMessages.required }}.
                  </div>
                </div>
                <div
                  class="col-sm-6"
                  v-if="
                    defaultFields.field_type == defaultFieldInvocie.Bank_Form
                  "
                >
                  <label for="account_name" class="text-sm text-gray-700"
                    >{{ "Account Name" | localize }}
                    <span class="astrik"> * </span>
                  </label>
                  <input
                    v-model="defaultFields.bankFields.account_name"
                    id="account_name"
                    ref="focusInput"
                    class="form-control"
                    type="text"
                  />
                  <div
                    class="error"
                    v-if="
                      displayErrorMessageDefaultFields(
                        $v.defaultFields.bankFields.account_name.required
                      )
                    "
                  >
                    {{ validationErrorMessages.required }}.
                  </div>
                </div>
                <div
                  class="col-sm-6"
                  v-if="
                    defaultFields.field_type == defaultFieldInvocie.Bank_Form
                  "
                >
                  <label for="sort_code" class="text-sm text-gray-700"
                    >{{ "Sort Code" | localize }}
                    <span class="astrik"> * </span>
                  </label>
                  <input
                    v-model="defaultFields.bankFields.sort_code"
                    class="form-control"
                    type="text"
                  />
                  <div
                    class="error"
                    v-if="
                      displayErrorMessageDefaultFields(
                        $v.defaultFields.bankFields.sort_code.required
                      )
                    "
                  >
                    {{ validationErrorMessages.required }}.
                  </div>
                </div>
                <div
                  class="col-sm-6"
                  v-if="
                    defaultFields.field_type == defaultFieldInvocie.Bank_Form
                  "
                >
                  <label for="account_number" class="text-sm text-gray-700"
                    >{{ "Account Number" | localize }}
                    <span class="astrik"> * </span>
                  </label>
                  <input
                    v-model="defaultFields.bankFields.account_number"
                    class="form-control"
                    type="number"
                  />
                  <div
                    class="error"
                    v-if="
                      displayErrorMessageDefaultFields(
                        $v.defaultFields.bankFields.account_number.required
                      )
                    "
                  >
                    {{ validationErrorMessages.required }}.
                  </div>
                </div>
                <div
                  class="col-sm-6"
                  v-if="
                    defaultFields.field_type == defaultFieldInvocie.Bank_Form
                  "
                >
                  <label for="iban" class="text-sm text-gray-700"
                    >{{ "IBAN" | localize }}
                    <span class="astrik"> * </span>
                  </label>
                  <input
                    v-model="defaultFields.bankFields.IBAN"
                    maxlength="50"
                    id="iban"
                    ref="focusInput"
                    class="form-control"
                    type="text"
                  />
                  <div
                    class="error"
                    v-if="
                      displayErrorMessageDefaultFields(
                        $v.defaultFields.bankFields.IBAN.required
                      )
                    "
                  >
                    {{ validationErrorMessages.required }}.
                  </div>
                </div>

                <div
                  class="col-sm-6"
                  v-if="
                    defaultFields.field_type == defaultFieldInvocie.Bank_Form
                  "
                >
                  <label for="swift_code" class="text-sm text-gray-700"
                    >{{ "Swift Code" | localize }}
                    <span class="astrik"> * </span>
                  </label>
                  <input
                    v-model="defaultFields.bankFields.swift_code"
                    maxlength="50"
                    id="swift_code"
                    ref="focusInput"
                    class="form-control"
                    type="text"
                  />
                  <div
                    class="error"
                    v-if="
                      displayErrorMessageDefaultFields(
                        $v.defaultFields.bankFields.swift_code.required
                      )
                    "
                  >
                    {{ validationErrorMessages.required }}.
                  </div>
                </div>
                <div
                  class="col-sm-6"
                  v-if="
                    defaultFields.field_type == defaultFieldInvocie.Address_Form
                  "
                >
                  <label for="company_name" class="text-sm text-gray-700"
                    >{{ "Company Name" | localize }}
                    <span class="astrik"> * </span>
                  </label>
                  <input
                    v-model="defaultFields.addressFields.company_name"
                    maxlength="50"
                    id="company_name"
                    ref="focusInput"
                    class="form-control"
                    type="text"
                  />
                  <div
                    class="error"
                    v-if="
                      displayErrorMessageDefaultFields(
                        $v.defaultFields.addressFields.company_name.required
                      )
                    "
                  >
                    {{ validationErrorMessages.required }}.
                  </div>
                </div>

                <div
                  class="col-sm-6"
                  v-if="
                    defaultFields.field_type == defaultFieldInvocie.Address_Form
                  "
                >
                  <label for="company_address" class="text-sm text-gray-700"
                    >{{ "Company Address" | localize }}
                    <span class="astrik"> * </span>
                  </label>
                  <input
                    v-model="defaultFields.addressFields.address"
                    maxlength="50"
                    id="address"
                    ref="focusInput"
                    class="form-control"
                    type="text"
                  />
                  <div
                    class="error"
                    v-if="
                      displayErrorMessageDefaultFields(
                        $v.defaultFields.addressFields.address.required
                      )
                    "
                  >
                    {{ validationErrorMessages.required }}.
                  </div>
                </div>
                <div
                  class="col-sm-6"
                  v-if="
                    defaultFields.field_type == defaultFieldInvocie.Address_Form
                  "
                >
                  <label for="company_city" class="text-sm text-gray-700"
                    >{{ "City" | localize }}
                    <span class="astrik"> * </span>
                  </label>
                  <input
                    v-model="defaultFields.addressFields.city"
                    maxlength="50"
                    id="company_city"
                    ref="focusInput"
                    class="form-control"
                    type="text"
                  />
                  <div
                    class="error"
                    v-if="
                      displayErrorMessageDefaultFields(
                        $v.defaultFields.addressFields.city.required
                      )
                    "
                  >
                    {{ validationErrorMessages.required }}.
                  </div>
                </div>
                <div
                  class="col-sm-6"
                  v-if="
                    defaultFields.field_type == defaultFieldInvocie.Address_Form
                  "
                >
                  <label for="postal_code" class="text-sm text-gray-700"
                    >{{ "ZIP/Postal COde" | localize }}
                    <span class="astrik"> * </span>
                  </label>
                  <input
                    v-model="defaultFields.addressFields.postal_code"
                    maxlength="50"
                    id="postal_code"
                    ref="focusInput"
                    class="form-control"
                    type="text"
                  />
                  <div
                    class="error"
                    v-if="
                      displayErrorMessageDefaultFields(
                        $v.defaultFields.addressFields.postal_code.required
                      )
                    "
                  >
                    {{ validationErrorMessages.required }}.
                  </div>
                </div>
              </div>
              <div class="invoice-submit">
                <button type="submit" class="main-button">
                  {{ "Update" | localize }}
                </button>
              </div>
            </form>
          </b-tab>
        </b-tabs>
      </b-card>
    </div>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";
import Datepicker from "vuejs-datepicker";
import { required, requiredIf, between, numeric, helpers } from "vuelidate/lib/validators";
import { ModelSelect } from "vue-search-select";
import moment from "moment";
import { faPlus, faTrashAlt } from "@fortawesome/free-solid-svg-icons";
import { VueEditor } from "vue2-editor";
const alpha = helpers.regex('alpha', /^[a-zA-Z ]*$/);


export default {
  components: {
    Datepicker,
    ModelSelect,
    VueEditor,
  },
  data: () => ({
    faPlus,
    faTrashAlt,
    createinvoiceButtonClicked: false,
    updateDefaultFieldButtonClicked: false,
    Country: Constants.Country.label,
    invoicetype: Constants.Invoice_type.type,
    defaultFormType: Constants.defaultInvoiceFormType.type,
    currencies: Constants.currrenciesDetail,
    validationErrorMessages: Constants.validationErrorMessages,
    defaultFieldInvocie: Constants.defaultFieldsInvoice,
    fieldsInvoiceConstants: Constants.fieldsInvoice,
    storageConstants: Constants.miscellaneous.storage.pdf,
    // invoiceList: [],
    formInvoice: {
      invoice_type: "",
      customer_name: "",
      customer_company: "",
      customer_address: "",
      customer_city: "",
      postal_code: "",
      country: "",
      due_date: "",
      invoice_date: "",
      vat_number: "",
      vat_percentage: "",
      invoice_number: "",
      reference: "",
    },

    listFields: [
          {
            key: 'id',
            label: 'ID',
          },
          {
            key: 'invoice_number',
            label: 'Invoice Number',
          },
          {
            key: 'invoice_date',
            label: 'Invoice Date',
          },
          {
            key: 'due_date',
            label: 'Due Date',
          },
           {
            key: 'link',
            label: 'Download Link',
          }
        ],

         listItems: [
          { id: 40, invoice_number: 'Dickerson', invoice_date: 'Macdonald',due_date: '12 DEc 2021', link: "abc123" },
          { id: 21, invoice_number: 'Larsen', invoice_date: 'Shaw',due_date: '12 DEc 2021', link: "abc123" },
          { id: 89, invoice_number: 'Geneva', invoice_date: 'Wilson',due_date: '12 DEc 2021', link: "abc123" }
        ],

    Invoice: [
      {
        description: "",
        quantity: "",
        amount: "",
      },
    ],
    defaultFields: {
      field_type: "",
      bankFields: {
        bank: "",
        account_name: "",
        sort_code: "",
        account_number: "",
        IBAN: "",
        swift_code: "",
      },
      addressFields: {
        company_name: "",
        address: "",
        city: "",
        postal_code: "",
      },
    },
  }),
  validations: {
    formInvoice: {
      invoice_type: {
        required,
      },
      customer_name: {
        required,
        alpha
      },
      customer_company: {
        required,
        alpha
      },
      customer_address: {
        required,
      },
      customer_city: {
        required,
        alpha
      },
      postal_code: {
        required,
      },
      country: {
        required,
      },
      due_date: {
        required,
      },
      invoice_date: {
        required,
      },
      vat_number: {
        required,
        numeric
      },
      vat_percentage: {
        required,
        between: between(1, 100),
      },
      currency: {
        required,
      },
      invoice_number: {
        required,
      },
      reference: {
        required,
      },
    },

    defaultFields: {
      field_type: {
        required,
      },
      bankFields: {
        bank: {
          required: requiredIf(function (defaultFields) {
            if (
              this.defaultFields.field_type ==
              Constants.defaultFieldsInvoice.Bank_Form
            ) {
              return true;
            }
          }),
        },
        account_name: {
          required: requiredIf(function (defaultFields) {
            if (
              this.defaultFields.field_type ==
              Constants.defaultFieldsInvoice.Bank_Form
            ) {
              return true;
            }
          }),
        },
        sort_code: {
          required: requiredIf(function (defaultFields) {
            if (
              this.defaultFields.field_type ==
              Constants.defaultFieldsInvoice.Bank_Form
            ) {
              return true;
            }
          }),
        },
        account_number: {
          required: requiredIf(function (defaultFields) {
            if (
              this.defaultFields.field_type ==
              Constants.defaultFieldsInvoice.Bank_Form
            ) {
              return true;
            }
          }),
        },
        IBAN: {
          required: requiredIf(function (defaultFields) {
            if (
              this.defaultFields.field_type ==
              Constants.defaultFieldsInvoice.Bank_Form
            ) {
              return true;
            }
          }),
        },
        swift_code: {
          required: requiredIf(function (defaultFields) {
            if (
              this.defaultFields.field_type ==
              Constants.defaultFieldsInvoice.Bank_Form
            ) {
              return true;
            }
          }),
        },
      },
      addressFields: {
        company_name: {
          required: requiredIf(function (defaultFields) {
            if (
              this.defaultFields.field_type ==
              Constants.defaultFieldsInvoice.Address_Form
            ) {
              return true;
            }
          }),
        },
        address: {
          required: requiredIf(function (defaultFields) {
            if (
              this.defaultFields.field_type ==
              Constants.defaultFieldsInvoice.Address_Form
            ) {
              return true;
            }
          }),
        },
        city: {
          required: requiredIf(function (defaultFields) {
            if (
              this.defaultFields.field_type ==
              Constants.defaultFieldsInvoice.Address_Form
            ) {
              return true;
            }
          }),
        },
        postal_code: {
          required: requiredIf(function (defaultFields) {
            if (
              this.defaultFields.field_type ==
              Constants.defaultFieldsInvoice.Address_Form
            ) {
              return true;
            }
          }),
        },
      },
    },
  },
  computed: {
    ...mapState({
      currentView: (state) => state.currentView,
      deal: (state) => state.deal,
      users: (state) => state.deal.users,
    }),
    invoiceList: function(){      
      if (this.deal.deal != null)  {        
          return this.deal.deal.get_deal_invoice;
      }else{
          return [];  
      }
    },
    invoiceBankFieldsExists(){
      if (typeof this.deal.deal.get_stage_data[0] != 'undefined' && this.deal.deal.get_stage_data[0] != null ) {
          if (this.deal.deal.get_stage_data[0].get_deal_invoice_default_bank != null) {
          this.defaultFields.bankFields.IBAN = this.deal.deal.get_stage_data[0].get_deal_invoice_default_bank.content.IBAN;
          this.defaultFields.bankFields.bank = this.deal.deal.get_stage_data[0].get_deal_invoice_default_bank.content.bank;
          this.defaultFields.bankFields.account_name = this.deal.deal.get_stage_data[0].get_deal_invoice_default_bank.content.account_name;
          this.defaultFields.bankFields.sort_code = this.deal.deal.get_stage_data[0].get_deal_invoice_default_bank.content.sort_code;
          this.defaultFields.bankFields.account_number = this.deal.deal.get_stage_data[0].get_deal_invoice_default_bank.content.account_number;
          this.defaultFields.bankFields.swift_code = this.deal.deal.get_stage_data[0].get_deal_invoice_default_bank.content.swift_code;
          }
            if (this.deal.deal.get_stage_data[0].get_deal_invoice_default_address != null) {
          this.defaultFields.addressFields.company_name = this.deal.deal.get_stage_data[0].get_deal_invoice_default_address.content.company_name;
          this.defaultFields.addressFields.address = this.deal.deal.get_stage_data[0].get_deal_invoice_default_address.content.address;
          this.defaultFields.addressFields.city = this.deal.deal.get_stage_data[0].get_deal_invoice_default_address.content.city;
          this.defaultFields.addressFields.postal_code = this.deal.deal.get_stage_data[0].get_deal_invoice_default_address.content.postal_code;
          }
        return true;
      }else{
        return false;
      }
        

    }
  
  },
  // watch: {
  //     'deal.deal.get_deal_invoice': function (currentVal,OldValue){
  //       console.log(currentVal)
  //           if (typeof currentVal != "undefined") {
  //               this.invoiceList = currentVal;

  //           }
  //     }
  // },


  created() {
    // setTimeout(() => {
    //   if (this.deal.deal.get_stage_data[0].get_deal_invoice_default_bank != null) {
    //   this.defaultFields.bankFields.IBAN = this.deal.deal.get_stage_data[0].get_deal_invoice_default_bank.content.IBAN;
    //   this.defaultFields.bankFields.bank = this.deal.deal.get_stage_data[0].get_deal_invoice_default_bank.content.bank;
    //   this.defaultFields.bankFields.account_name = this.deal.deal.get_stage_data[0].get_deal_invoice_default_bank.content.account_name;
    //   this.defaultFields.bankFields.sort_code = this.deal.deal.get_stage_data[0].get_deal_invoice_default_bank.content.sort_code;
    //   this.defaultFields.bankFields.account_number = this.deal.deal.get_stage_data[0].get_deal_invoice_default_bank.content.account_number;
    //   this.defaultFields.bankFields.swift_code = this.deal.deal.get_stage_data[0].get_deal_invoice_default_bank.content.swift_code;
    //   }
    //     if (this.deal.deal.get_stage_data[0].get_deal_invoice_default_address != null) {
    //   this.defaultFields.addressFields.company_name = this.deal.deal.get_stage_data[0].get_deal_invoice_default_address.content.company_name;
    //   this.defaultFields.addressFields.address = this.deal.deal.get_stage_data[0].get_deal_invoice_default_address.content.address;
    //   this.defaultFields.addressFields.city = this.deal.deal.get_stage_data[0].get_deal_invoice_default_address.content.city;
    //   this.defaultFields.addressFields.postal_code = this.deal.deal.get_stage_data[0].get_deal_invoice_default_address.content.postal_code;
    //   }
    // }, 1000);
  },

  methods: {
    ...mapActions(["showNotification"]),

    addExperience() {
      this.Invoice.push({
        description: "",
        quantity: "",
        amount: "",
      });
    },

    removefields(i) {
      // alert(index);
      this.Invoice.splice(parseInt(i), 1);
    },

    displayErrorMessage(value) {
      if (!value && this.createinvoiceButtonClicked) {
        return true;
      } else {
        return false;
      }
    },
    customFormatter(date) {
      return moment(date).format(Constants.customDateFormat.format);
    },
    CurrencyFormat({ name }) {
      return `${name}`;
    },
    dateFormat(date){
      return moment(date).format(Constants.customDateFormat.formatDate);

  },
  onResetDealInvoice(event) {
     this.formInvoice.invoice_type = "";
     this.formInvoice.customer_name = "";
     this.formInvoice.customer_company = "";
     this.formInvoice.customer_address = "";
     this.formInvoice.customer_city = "";
     this.formInvoice.postal_code = "";
     this.formInvoice.country = "";
     this.formInvoice.due_date = "";
     this.formInvoice.invoice_date = "";
     this.formInvoice.vat_number = "";
     this.formInvoice.vat_percentage = "";
     this.formInvoice.invoice_number = "";
     this.formInvoice.reference = "";
     this.Invoice = [{
          description: ''
        }, {
          quantity: ''
        }, {
          amount: ''
        }]
    },

    onSubmitDealInvoice(event) {
      this.createinvoiceButtonClicked = true;
      if (this.$v.formInvoice.$invalid == true) {
        return false;
      } else {
        event.preventDefault();
      this.createinvoiceButtonClicked = false;
        axios
          .post("/data/deal-invoice/" + this.deal.deal.get_stage_data[0].id, {
            data: { formInvoice: this.formInvoice, Invoice: this.Invoice },
          })
          .then((response) => {
            if (response.data.status === "success") {
              this.$refs.form_1.reset();
               this.onResetDealInvoice();

              window.open(
                window.location.origin +
                  Constants.miscellaneous.storage.pdf +
                  response.data.data
              );
                this.$emit('refreshActvity');
              var dealsParam = { loadMore: false };
              this.showNotification({
                type: response.data.status,
                message: response.data.message,
              });
            }
          })
          .catch((error) => {
            // console.log("error", error);
            this.showNotification({
              type: error.response.data.status,
              message: error.response.data.message,
            });
          });
      }
    },

    displayErrorMessageDefaultFields(value) {
      if (!value && this.updateDefaultFieldButtonClicked) {
        return true;
      } else {
        return false;
      }
    },
    onSubmitDefaultDetails(event) {
      this.updateDefaultFieldButtonClicked = true;
      if (
        this.$v.defaultFields.bankFields.$invalid == true &&
        this.$v.defaultFields.addressFields.$invalid == false
      ) {
        return false;
      } else if (
        this.$v.defaultFields.bankFields.$invalid == false &&
        this.$v.defaultFields.addressFields.$invalid == true
      ) {
        return false;
      } else {
        event.preventDefault();
        axios
          .post(
            "/data/deal-invoice-default/" + this.deal.deal.get_stage_data[0].id,
            {
              data: this.defaultFields,
            }
          )
          .then((response) => {
            if (response.data.status === "success") {
              var dealsParam = { loadMore: false };
              this.showNotification({
                type: response.data.status,
                message: response.data.data,
              });
            }
          })
          .catch((error) => {
            // console.log("error", error);
            this.showNotification({
              type: error.response.data.status,
              message: error.response.data.message,
            });
          });
      }
    },
  },
};
</script>