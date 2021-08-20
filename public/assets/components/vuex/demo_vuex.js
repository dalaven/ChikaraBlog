//______STORE_VUEX_______
const store = Vuex.createStore({
  state: {
      numero: 10,
      cursos:[],
      data:[],
      myModal:''
  },
  mutations: {
      sum(state){
          state.numero ++
      },
      min(state,n){
        state.numero -= n
    },
    llamarDatos(state,cursosAccion){
      state.cursos = cursosAccion
    },
    showModals(state,cursosAccion){
      state.myModal= document.getElementById('staticBackdrop');
      state.myModal.modal('show')
    }

  },
  actions: {
    obtenerDatos: async function({commit}){
      const data =await fetch('http://localhost/chikarablog/index.php/home/users');
      const cursos = await data.json();
      commit('llamarDatos', cursos)
    },
    showModal: function({commit}){
      commit('showModals')
    }
    
  }

});
  
  
 