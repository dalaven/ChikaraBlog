
//_______COMPONENTES______________
app.component('demostorepadre', {
  template: //html
      `<div>
          <demostorehijo></demostorehijo> 
          <h2>numero ={{numero}}</h2>              
      </div>
`,
  computed:{
    ...Vuex.mapState(['numero'])

  }
})