<template>
  <div>
    <div class="container">
      <div class="row">
        <div class="col-12">
          <transaction-list-component :currentBalance="currentBalance" :currentTransactions="currentTransactions"></transaction-list-component>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    export default {
      props: {
        balance: Number,
        transactions: [Object, Array],
      },
      data(){
        return {
            currentBalance: 0,
            currentTransactions: [],
        }
      },
      mounted(){
        //initialize data values from props sent by the view
        this.currentBalance = this.balance;
        this.currentTransactions = this.transactions;
      }
    }
</script>
