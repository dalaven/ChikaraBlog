
//_______COMPONENTES PADRES______________
app.component('nav_bar', {
    template: //html
    `
    <div class="container-fluid">
    <!--  BARRA DE  MENUS -->
    
      <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">

                <logo></logo> 
                <span class="navbar-text">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-awesome fas fa-bars"></span>
                    <span class="navbar-toggler-awesome fas fa-times"></span>
                </button>
                <menu_nav></menu_nav>
                </span>
            </div>
        </nav>
        <!--  BANNER -->
        <seccion_banner></seccion_banner>
        <!--  NOSOTROS -->
        <seccion_nosotros></seccion_nosotros>
        <!--  FORMULARIO -->
        <seccion_formulario></seccion_formulario>
        <!--  CONTACTENOS -->
        <seccion_contactenos></seccion_contactenos>
        <!--  COPYRIGHT -->
        <seccion_copyright></seccion_copyright>
        



    </div>
  `,
  data(){
      return {
        
      }
  },
  
    
  })
