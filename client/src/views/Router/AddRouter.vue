<script setup>
// Write your script here
</script>

<template>
    <div class="row">
        <div class="col-12">
            <div class="p-title-box mt-4">
                <div>
                    <h2 class="p-title">Interfaces</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card card-h-100">
                <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">Connected Routers</h4>
                </div>
                <div class="card-body pt-0">
                    <table class="table table-responsive align-middle">
            <thead class="table-light">
              <tr>
                <th>Router SSID</th>
                <th>IP Address</th>
                <th>MAC Address</th>
                <th class="text-center" style="width: 20%">Details</th>
              </tr>
            </thead>
            <tbody>
              <!--<tr v-if="order_items_waiting.length == 0">
                                <tr>
                                    <td colspan="6" class="text-center" style="height:55px!important;">No data
                                        available.</td>
                                </tr>
                                -->
              <!-- <tr v-for="order_item in order_items_waiting" :key="order_item.id">-->
              <tr>
                <td class="align-middle">aaa</td>
                <td>aaa</td>
                <td style="max-width: 150px; word-wrap: break-word">aaa</td>
                <td class="text-center">
                  <div class="d-flex justify-content-center">
                    <button
                      class="btn btn-xs btn-light"
                      title="Accept Order-Item"
                      @click="updateStatus(order_item, 'P')"
                    >
                      <i class="bi bi-eye"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-h-100">
                <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">Add Router</h4>
                </div>
                <div class="card-body pt-0">
                    <form>
                        <div class="form-group">
                            <label for="exampleInputEmail1">IP Address</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter router ip address">
                            
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Username</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter username">
                            
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Enter password">
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>