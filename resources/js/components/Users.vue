<template>
    <div class="container">
        <div class="row mt-4">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Users</h3>

                <div class="card-tools">

                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addNew">
                    Add New <i class="fas fa-user-plus fa-fw"></i>
                  </button>

                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Type</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>183</td>
                      <td>John Doe</td>
                      <td>11-7-2014</td>
                      <td><span class="tag tag-success">Approved</span></td>
                      <td>
                          <a href=""><i class="fa fa-edit blue"></i></a>
                          <a href=""><i class="fa fa-trash red"></i></a>
                        </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- Modal -->
<div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="addNewLabel">Add User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        </div>

        <form @submit.prevent="createUser">
        <div class="modal-body">
            <form>
                <div class="form-group">
                    <input v-model="form.name" type="text" name="name" placeholder="Full Name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                    <has-error :form="form" field="name"></has-error>
                </div>
                <div class="form-group">
                    <input v-model="form.email" type="email" name="email" placeholder="Email Address" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                    <has-error :form="form" field="email"></has-error>
                </div>
                <div class="form-group">
                    <textarea v-model="form.bio" class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }" id="bio" name="bio" placeholder="Short bio for user (Optional)" rows="3"></textarea>
                    <has-error :form="form" field="bio"></has-error>
                </div>
                <div class="form-group">
                    <select id="type" class="form-control" name="type" v-model="form.type" :class="{ 'is-invalid': form.errors.has('role') }">
                        <option selected>Select User Role</option>
                        <option>Admin</option>
                        <option>User</option>
                    </select>
                    <has-error :form="form" field="type"></has-error>

                </div>
                <div class="form-group">
                    <input v-model="form.password" type="password" name="name" placeholder="Password" class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                    <has-error :form="form" field="password"></has-error>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Create</button>
        </div>
        </form>
      </div>

    </div>
  </div>
</div>


</template>

<script>
    export default {
        data(){
            return{
                form: new Form({
                    name:'',
                    email:'',
                    password:'',
                    type:'',
                    bio:'',
                    photo:'',
                })
            }
        },
        methods:{
            createUser(){
                this.form.post('/api/user');
            }

        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>