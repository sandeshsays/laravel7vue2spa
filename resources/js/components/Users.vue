<template>
  <div class="container">
    <div class="row mt-4" v-if="$gate.isSuperadminOrAdmin()">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Users</h3>
            <div class="card-tools">
              <button class="btn btn-success" @click="newModal">
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
                  <th>Registered At</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="user in users.data" :key="user.id">
                  <td>{{ user.id }}</td>
                  <td>{{ user.name | upText }}</td>
                  <td>{{ user.email }}</td>
                  <td>{{ user.type | capitalize }}</td>
                  <td>{{ user.created_at | myDate }}</td>
                  <td>
                    <a href="#" @click="editModal(user)"
                      ><i class="fa fa-edit blue"></i
                    ></a>
                    <a href="#" @click="deleteUser(user.id)"
                      ><i class="fa fa-trash red"></i
                    ></a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <pagination
              :data="users"
              @pagination-change-page="getResults"
            ></pagination>
          </div>
        </div>
        <!-- /.card -->
      </div>
    </div>

    <div v-if="!$gate.isSuperadminOrAdmin()">
      <not-found></not-found>
    </div>

    <!-- Modal -->
    <div
      class="modal fade"
      id="addNew"
      tabindex="-1"
      role="dialog"
      aria-labelledby="addNewLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 v-show="!editMode" class="modal-title" id="addNewLabel">
              Add User
            </h5>
            <h5 v-show="editMode" class="modal-title" id="addNewLabel">
              Update User
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <form @submit.prevent="editMode ? updateUser() : createUser()">
            <div class="modal-body">
              <form>
                <div class="form-group">
                  <input
                    v-model="form.name"
                    type="text"
                    name="name"
                    placeholder="Full Name"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('name') }"
                  />
                  <has-error :form="form" field="name"></has-error>
                </div>
                <div class="form-group">
                  <input
                    v-model="form.email"
                    type="email"
                    name="email"
                    placeholder="Email Address"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('email') }"
                  />
                  <has-error :form="form" field="email"></has-error>
                </div>
                <div class="form-group">
                  <textarea
                    v-model="form.bio"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('bio') }"
                    id="bio"
                    name="bio"
                    placeholder="Short bio for user (Optional)"
                    rows="3"
                  ></textarea>
                  <has-error :form="form" field="bio"></has-error>
                </div>
                <div class="form-group">
                  <select
                    name="type"
                    v-model="form.type"
                    id="type"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('type') }"
                  >
                    <option value="">Select User Role</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                  </select>
                  <has-error :form="form" field="type"></has-error>
                </div>
                <div class="form-group">
                  <input
                    v-model="form.password"
                    type="password"
                    name="name"
                    placeholder="Password"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('password') }"
                  />
                  <has-error :form="form" field="password"></has-error>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">
                Close
              </button>
              <button v-show="!editMode" type="submit" class="btn btn-success">
                Create
              </button>
              <button v-show="editMode" type="submit" class="btn btn-primary">
                Update
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      editMode: false,
      users: {},
      form: new Form({
        id: "",
        name: "",
        email: "",
        password: "",
        type: "",
        bio: "",
        photo: "",
      }),
    };
  },
  methods: {
    getResults(page = 1) {
      axios.get("api/user?page=" + page).then((response) => {
        this.users = response.data;
      });
    },

    editModal(user) {
      this.editMode = true;
      this.form.reset();
      $("#addNew").modal("show");
      this.form.fill(user);
    },

    newModal() {
      this.editMode = false;
      this.form.reset();
      $("#addNew").modal("show");
    },

    deleteUser(id) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.isConfirmed) {
          this.form
            .delete("api/user/" + id)
            .then(() => {
              Swal.fire("Deleted!", "Your file has been deleted.", "success");
              Fire.$emit("afterCreated");
            })
            .catch(() => {
              Swal("Failed!", "Something went wrong.", "warning");
            });
        }
      });
    },

    loadUsers() {
      if (this.$gate.isSuperadminOrAdmin()) {
        axios.get("api/user").then(({ data }) => (this.users = data));
      }
    },

    updateUser() {
      this.$Progress.start();
      this.form
        .put("api/user/" + this.form.id)
        .then(() => {
          //successful
          $("#addNew").modal("hide");
          Toast.fire({
            icon: "success",
            title: "User updated successfully",
          });
          Fire.$emit("afterCreate");
          this.$Progress.finish();
        })
        .catch(() => {
          //fail
          this.$Progress.fail();
        });
    },

    createUser() {
      this.$Progress.start();
      this.form
        .post("/api/user")
        .then(() => {
          Fire.$emit("afterCreated");
          $("#addNew").modal("hide");
          Toast.fire({
            icon: "success",
            title: "User created successfully",
          });
          this.$Progress.finish();
        })
        .catch();
    },
  },

  mounted() {
    console.log("Component mounted.");
    Fire.$on("searching",()=>{
        let query = this.$parent.search;
        axios.get('api/findUser?q=' + query)
        .then((data)=>{
            this.users = data.data;
        })
        .catch(()=>{
            //
        })
    });
    this.loadUsers();
    Fire.$on("afterCreated", () => {
      this.loadUsers();
    });
  },
};
</script>
