<template>
  <div class="transaction-navbar">
    <div class="container transaction-navbar-inner-div">
      <div class="row transaction-navbar-inner-div">
        <div class="col"></div>
        <div class="col-10 align-self-center">

          <div class="container">
            <div class="row pr-3">
              <div class="col-9 align-self-center">
                <button type="button" class="btn btn-primary border-0 navbar-button ml-3 pl-0" @click="showModal">
                  <svg class="ml-2 mr-2 bi bi-plus-circle" width="1.8em" height="1.8em" viewBox="0 0 16 16" fill="white" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M8 3.5a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5H4a.5.5 0 0 1 0-1h3.5V4a.5.5 0 0 1 .5-.5z"/>
                    <path fill-rule="evenodd" d="M7.5 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0V8z"/>
                    <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                  </svg>
                  ADD ENTRY
                </button>
                <button type="button" class="btn btn-primary border-0 navbar-button ml-3 pl-0" disabled>
                  <svg class="ml-2 mr-1 bi bi-cloud-upload" width="1.8em" height="1.8em" viewBox="0 0 16 16" fill="white" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.887 6.2l-.964-.165A2.5 2.5 0 1 0 3.5 11H6v1H3.5a3.5 3.5 0 1 1 .59-6.95 5.002 5.002 0 1 1 9.804 1.98A2.501 2.501 0 0 1 13.5 12H10v-1h3.5a1.5 1.5 0 0 0 .237-2.981L12.7 7.854l.216-1.028a4 4 0 1 0-7.843-1.587l-.185.96z"/>
                    <path fill-rule="evenodd" d="M5 8.854a.5.5 0 0 0 .707 0L8 6.56l2.293 2.293A.5.5 0 1 0 11 8.146L8.354 5.5a.5.5 0 0 0-.708 0L5 8.146a.5.5 0 0 0 0 .708z"/>
                    <path fill-rule="evenodd" d="M8 6a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0v-8A.5.5 0 0 1 8 6z"/>
                  </svg>
                  IMPORT CSV
                </button>
              </div>
              <div class="col-3 align-self-center">
                <h5 class="navbar-text-secondary float-right">CURRENT BALANCE</h5>
                <h4 v-if="currentBalance > 0" class="float-right positive-balance">
                  {{ formatCurrency(currentBalance, false) }}
                </h4>
                <h4 v-else class="float-right negative-balance">
                  {{ formatCurrency(currentBalance, true) }}
                </h4>
              </div>
            </div>
          </div>

        </div>
        <div class="col"></div>
      </div>
    </div>

    <!-- new transaction form modal -->
    <div class="modal" tabindex="-1" role="dialog" ref="modal">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add Balance Entry</h5>
          </div>
          <div class="modal-body">

            <form class="mt-3" method="POST" :action="'/transactions/asyncstore'" :ref="'new-form-tag'">
              <input type="hidden" name="_token" :value="csrf">

              <!-- inputs -->
              <div class="form-row">
                <div class="col">
                  <div class="form-group">
                    <label for="labelInput">LABEL</label>
                    <input type="text" class="form-control" id="labelInput" maxlength="35">
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="dateInput">DATE</label>
                    <input type="date" class="form-control" id="dateInput">
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="amountInput">AMOUNT</label>
                    <input type="number" class="form-control" id="amountInput" step="0.01" min="-5000" max="5000">
                  </div>
                </div>
              </div>

              <!-- buttons -->
              <div class="mt-4">
                <hr />
                <div class="float-right mt-3 mb-3">
                  <button type="button" class="btn btn-lg btn-light mr-2" @click="hideModal()">Cancel</button>
                  <button type="button" class="btn btn-lg btn-primary" @click="addTransaction()">Save Entry</button>
                </div>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
    export default {
      props: {
        csrf: String,
        currentBalance: Number,
      },
      mounted(){
        //
      },
      methods: {
        emitUpdate: function () {
          this.$emit('update');
        },
        //display the modal with the add entry form
        showModal() {
          let element = this.$refs.modal;
          $(element).modal('show')
        },
        //hide the modal with the add entry form
        hideModal() {
          let element = this.$refs.modal;
          $(element).modal('hide')
        },
        //add a new transaction
        addTransaction: function (id) {
          this.hideModal();

          var formTagRef = 'new-form-tag';

          axios.post('/transactions/asyncstore', {
              label: this.$refs[formTagRef].elements.labelInput.value,
              date: this.$refs[formTagRef].elements.dateInput.value,
              amount: this.$refs[formTagRef].elements.amountInput.value,
              headers: {
                  'Accept': 'application/json'
              }
          })
            .then(response => {
                 var data = response.data;
                 if(data.status == 'created') {
                    console.log('added new transaction - id # ' + String(data.transaction));
                    this.emitUpdate();
                 } else {
                    console.log('error adding new transaction');
                    console.log(data.error);
                 }
            })
            .catch(error => {
              console.log(error);
            });

            //clear the form inputs
            this.$refs[formTagRef].elements.labelInput.value = '';
            this.$refs[formTagRef].elements.dateInput.value = '';
            this.$refs[formTagRef].elements.amountInput.value = '';
        },
      },
    }
</script>

<style>
  .transaction-navbar {
    background-color: #192b4e;
    height: 150px;
  }
  .transaction-navbar-inner-div {
    height: 100%;
  }
  .navbar-text {
    color: white;
  }
  .navbar-text-secondary {
    color: #a3aab8;
  }
  .navbar-button {
    background-color: #0054fe;
  }
  .positive-balance {
    color: #37ae45;
  }
  .negative-balance {
    color: red;
  }
</style>
