//_____________________componentes_hijos______________



app.component('modal_form', {
  template: //html
      `<!-- Modal -->
      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" ref="vuemodal">
        <div class="modal-dialog">
          <div class="modal-content" >
            <div :class="['modal-header',typeAlert,'modal_head']" v-if="alertaHijo.alert">
              <h5 class="modal-title" id="exampleModalLabel">{{alertaHijo.title}}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="closeModal"></button>
            </div>
            <div class="modal-body" v-if="alertaHijo.alert">
              {{alertaHijo.msg}}
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="closeModal">Close</button>
            </div>
          </div>
        </div>
      </div>
      `
      ,
  props:[
    'alertaPadre'
  ],
  data() {
      return {
        alertaHijo:[
          {title:'',msg:'',state:'',alert: false}
        ],
        typeAlert:'',

      }
  },
  
  updated(){
    this.alertaHijo=this.alertaPadre,
    this.typeAlert ='bg-'+this.alertaPadre.state

  },
  methods:{
    closeModal(){
      this.alertaHijo.alert = false
      
    }
  }
  

})


app.component('seccion_copyright', {
  template: //html
      `<div class="row seccion_copyright text-center">
        <div class="text-center">
        
          &copy; Copyright 2021 by <a :href="[url]" target="_blank">{{name}}</a>.
          </div>
      </div>
      `
      ,
  data() {
      return {
        url:"https://www.douso.co",
        name: "douso SAS"
         
        
      }
    }
})


app.component('seccion_contactenos', {
  template: //html
      `<div class="row " id="Contactenos">
      <div class="contact p-4">
		   <div class="contact_title text-center">
			CONTACTENOS
		  </div>
		  <div class="contact_body col-12">
			<div class="row">
				<div class="col col-sm-6 offset-sm-0 col-md-6 offset-md-0">
					<form @submit.prevent="pqr" class=" " action="/pqr" method="post">
						<div class="form-group">
							<label for="firstname">NOMBRE</label>
							<input type="text" class="form-control" name="USER_name" id="USER_name" v-model="name">
						</div>
						<div class="form-group">
							<label for="firstname">CORREO ELECTRONICO</label>
							<input type="textarea" class="form-control" name="USER_email" id="USER_email"
								v-model="email">
						</div>
						<div class="form-group">
							<label for="firstname">TEXTO</label>
							<textarea type="text" class="form-control" name="USER_address" id="USER_address"
								v-model="text"></textarea>
						</div>
						<div class="col-12 col-sm-4 p-2">
							<button type="submit" class="btn btn-primary">enviar</button>
						</div>
					</form>
				</div>
				<div class=" col-sm-4 offset-sm-0 col-md-4 offset-md-0">
					<div class="row" v-for="red of redes">
            <div class="col-8 p-1">
						<a :href=[red.url]><img :src="[red.icon]" class="icon_redes"
								lighting-color="white"target="_blank"> </a> {{red.nombre}}
                </div>
					</div>
        </div>
        <div class="col-sm-2 offset-sm-0 col-md-2 offset-md-0">
        <div class="row">
            <div class="col-8 p-1">
            
            </div>
					</div>
        </div>
			</div>
      </div>
		</div>
    </div>

      
      `
      ,
  data() {
      return {
        name:'',
        email:'',
        text:'',
        redes:[
          {nombre:'Facebook',icon:'http://localhost/chikarablog/public/assets/icons/facebook_negative.png',url:'https://www.facebook.com/ChikaraOficial'},
          {nombre:'Instagram',icon:'http://localhost/chikarablog/public/assets/icons/instagram_negative.png',url:'https://www.instagram.com/chikaraoficial/'},
          {nombre:'Youtube',icon:'http://localhost/chikarablog/public/assets/icons/youtube_negative.png',url:'https://www.youtube.com/channel/UCG51dRdn45UfK58rMeqrCPw'},
          {nombre:'Twitch',icon:'http://localhost/chikarablog/public/assets/icons/twitch_negative.png',url:'https://www.twitch.tv/chikaraoficial'},
          {nombre:'chikara.organizacion@chikaralife.org',icon:'http://localhost/chikarablog/public/assets/icons/correo-electronico.png',url:''}
        ]
          
      }
    }
})

app.component('seccion_formulario', {
  template: //html
      `<div id="Inscripcion">
        <modal_form :alertaPadre="alerta"></modal_form>
          <div class="container-fluid pt-2">
            <div class="card">
            <div class="card-header text-center">
            INSCRICION
          </div>
            <div class="card-body">
            <h5 class="card-title text-center">formulario 2021-2</h5>
            <div class="row">
              <div class="col-sm-5 offset-sm-2 col-md-6 offset-md-0 text-center">
                  <img :src="[imagen_formulario]" class="img-fluid imagen_form"  lighting-color="white">
              </div>
              <div class="ccol-sm-5 offset-sm-2 col-md-6 offset-md-0">
              <form @submit.prevent="registrar" class=" " action="/registrar" method="post">
                <div class="form-group">
                  <label for="firstname">NOMBRE</label>
                  <input type="text" class="form-control" name="USER_names" id="USER_names" v-model="USER_names">
              </div>
              <div class="form-group">
                  <label for="firstname">APELLIDO</label>
                  <input type="text" class="form-control" name="USER_lastnames" id="USER_lastnames" v-model="USER_lastnames">
              </div>
              <div class="form-group">
                <label for="firstname">CORREO ELECTRONICO</label>
                <input type="text" class="form-control" name="USER_email" id="USER_email" v-model="USER_email">
              </div>
              <div class="form-group">
                  <label for="firstname">PAIS</label>
                  <input type="text" class="form-control" name="USER_address" id="USER_address" v-model="USER_address">
              </div>
                <div class="form-group">
                  <label for="firstname">TELEFONO</label>
                  <input type="text" class="form-control" name="USER_telephone" id="USER_telephone" v-model="USER_telephone">
                </div>
              <div class="col-12 col-sm-4">
                  <button type="submit" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Registrar</button>
              </div>
              </form>
              </div>
              </div>
            </div>
            </div>
          </div>
    </div>
      `
      ,
  data() {
      return {
        USER_names:'',
        USER_lastnames:'',
        USER_email:'',
        USER_username:'',
        USER_address:'',	
        USER_telephone:'',
        imagen_formulario:'http://localhost/chikarablog/public/assets/images/formulario_imagen.png',
        alerta:[{title:'',msg:'',state:'',alert: false}],
        modal:'#exampleModal'
        
      }
    },
    methods:{
      registrar() {
        axios.post('/chikarablog/index.php/home/registrar', {
          USER_names:this.USER_names,
           USER_lastnames:this.USER_lastnames,
           USER_email:this.USER_email,
           USER_username:this.USER_username,
           USER_address:this.USER_address,	
           USER_telephone:this.USER_telephone,
          })
        .then(response=> {
          this.alerta = response.data;
          console.log(this.alerta);
          this.USER_names='',
           this.USER_lastnames='',
           this.USER_email='',
           this.USER_username='',
           this.USER_address='',	
           this.USER_telephone=''
        })
        .catch(error => {
          myModal = document.getElementById('myModal');
          console.log(error);
          
        })  
      },
      
      
    },
    
    
})

app.component('seccion_nosotros', {
  template: //html
      `<div id="Nosotros">
      <div class="container-fluid pt-2">
        <div class="card">
        <div class="card-header text-center">
        NOSOTROS
      </div>
      <div class="card-body text-center">
          <div class="row ">
            <div class="col-sm-5 offset-sm-2 col-md-6 offset-md-0 align-items-center">
              <iframe width='100%' height='400' class="align-items-center"
                :src="[video]" title="YouTube video player" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe>
            </div>
            <div class="col-sm-5 offset-sm-2 col-md-6 offset-md-0">
            <div class="row">
                <span class="row">
                  <h1>¿QUIENES SOMOS?</h1>
                </span>
                <div class="row">
                Chikara 力 es una organización sin animo de lucro dedicada a la divulgación de información, principalmente a jóvenes, y a todas aquellas personas que tengan interés en conocer y aprender sobre Japón a nivel académico y cultural.
                .
                </div>
              </div>
              <div class="row">
                <span class="row">
                  <h1>CULTURA</h1>
                </span>
                <div class="row">
                En Chikara 力 se divulga la cultura japonesa a través de diversas actividades como eventos, cursos, exposiciones, talleres, estudios y etc.
                </div>
              </div>
              <div class="row">
                <span class="row">
                  <h1>LENGUA JAPONESA</h1>
                </span>
                <div class="row">
                En Chikara 力  difundimos el idioma 	japonés a través de la experiencia propia y conocimientos adquiridos por autoaprendizaje.
                </div>
              </div>
             </div>
             </div>
          </div>
        </div>
      </div>
    </div>
      `
      ,
  data() {
      return {
        video:'https://www.youtube.com/embed/wd4nJPfnq34',
      }
    }
})

app.component('seccion_banner', {
  template: //html
      `<div id="Inicio">
        <div class="row banner text-center  align-items-center">
        <div class="col-12">
        <h1 class="banner_text">CONOCIENDO A JAPON CONTIGO</h1>
        </div>
        </div>
      </div>
      `
      ,
  data() {
      return {
        imagen:'http://localhost/chikarablog/public/assets/images/tori-tori-torio.jpg',
      }
    }
})

app.component('menu_nav', {
  template: //html
      `<div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item " v-for="menu of menus">
            <a class="nav-link page-scroll" aria-current="page" :href="[menu.link]">{{menu.nombre}}</a>
          </li>
        </ul>
      </div>
      `
      ,
  data() {
      return {
        menus:[
          {nombre:'Inicio', link:'#Inicio'},
          {nombre:'Nosotros', link:'#Nosotros'},
          {nombre:'Inscripcion', link:'#Inscripcion'},
          {nombre:'Contactenos', link:'#Contactenos'},
        ]
        
      }
    }
})

app.component('logo', {
    template: //html
        `<div class="">
            <a class="navbar-brand" href="#">
            <h3>
            <img :src="[logo]" class="logo_nav" lighting-color="white">
            {{text}}</h3>
            </a>
            </div>
        
  `,
  data() {
    return {
      logo:"http://localhost/chikarablog/public/assets/images/chikara_logo_2.png",
      text:'Chikara'
    }
  },
}).mount('#app')
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

