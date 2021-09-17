
app.component('demostorehijo', {
  template: //html
      `<div>
          
          <h2>numero ={{numero}}</h2>
          <button @click="sum"> + </button>
          <button @click="min(2)"> - </button>
          <button @click="showModal"> ver modal</button>
          
          <h2>lista de usuario</h2>
          <h2 >{{alerta}}</h2>
          <ul v-for = "listas of lista">
            <li>{{listas.idUSERS}}</li>
          </ul>
          
          <form @submit.prevent="registrar" class="" action="/registrar" method="post">
            <div class="form-group">
              <label for="firstname">ID</label>
              <input type="text" class="form-control" name="id" id="id" v-model="id">
          </div>
          <div class="col-12 col-sm-4">
              <button type="submit" class="btn btn-primary">Registrar</button>
          </div>
          </form>
          <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="false">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                ...
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Understood</button>
              </div>
            </div>
          </div>
    </div>
      </div>
`,
data() {
  return {
    id:"",
    lista:[],
    alerta:'',
  }
},
computed:{
  ...Vuex.mapState(['numero','cursos']),
  ...Vuex.mapActions(['obtenerDatos']),
  listar(){
    axios.get('http://localhost/chikarablog/index.php/home/users',{params:{}})
    .then(response => {
       this.lista = response.data
    })
    .catch(error => {
      console.log(error)
      
    })
    .finally(() => this.loading = false)
  }
  
},

methods:{
  ...Vuex.mapMutations(['sum','min']),
  ...Vuex.mapActions(['showModal']),
  registrar() {
    axios.post('http://localhost/chikarablog/index.php/home/registrar', {
       id: this.id
      })
    .then(response=> {
      this.alerta = response.data.msg;
      this.id='';
    })
    .catch(error => {
      console.log(error);
    })  
  },
  
},


}).mount('#app')



axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';