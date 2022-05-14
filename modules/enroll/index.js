define([
	"text!./index.html",
	"api",
	"cities",
	"components/menu",
	"components/loader"
], (html, api, cities,  Menu, Loader) => {
	return {
		template: html,
		components: {
			Menu,
			Loader
		},
		data() {
			return {
				countries: [],
				showLoader: false,
				isOld: false,
				data:{
					USER_identification: null,
					USER_type_identification: null,
					USER_name: null,
					USER_lastname: null,
					USER_indicative: null,
					USER_phone: null,
					USER_country: null,
					USER_birthday: null,
					USER_email: null,
					USER_course: null,
				},

				step: 1,
				STEP_REGISTER: 1,
				STEP_COURSE: 2,

				USER_valid:false
			};
		},
		computed: {
		},
		methods: {
			async next(){
				switch (this.step) {
					case this.STEP_REGISTER:
						this.validRegister();
						if(this.USER_valid){
							this.step =  this.STEP_COURSE
						}
						break;
					case this.STEP_COURSE:
						await this.saveCourse();
						break;
				}
			},
			async saveCourse(){
				this.showLoader= true;
				await api.post('saveCourse', this.data).then(result => {
					if(result.error){
						alert("Ha ocurrido un error, contacta a algún administrativo")
					} else  if(result.noRegister){
						alert("Ya existe un registro para este usuario.")
					} else {
						alert("Tu inscripción al curso a sido satisfactoria, por favor revisa tu correo.")
					}
					this.showLoader = false;
					this.resetForm()
				}).catch(error => {
					this.showLoader = false; this.resetForm()
				})
			},
			async sendRegister(){
				this.showLoader= true;
				
				await api.post('register', this.data).then(result => {
					if (!result.errors ) {
						//alert('Registro Exitoso, llegará un correo con la confirmación. Por favor revisar el spam')
						this.data.USER_PK = result
						this.USER_valid = true
					} else {
						if(result.errors.USER_email){
							alert('El correo ya se encuentra inscrito')
						} else if(result.errors.USER_identification){
							alert("El número de identificación ya existe")
						} else {
							alert("Se ha presentado un error, intente nuevamente")
						}
					}
					this.showLoader = false;
					this.resetForm()
				}).catch(error => {
					this.showLoader = false; this.resetForm()
				})
				
			},
			setIndicative(){
				this.data.USER_indicative =  "+" + cities.find( c => c.iso2 === this.data.USER_country)?.dialCode?? ""
			},
			resetForm(){
				this.data.USER_PK = null
				this.data.USER_course = null
				this.data.USER_identification = null
				this.data.USER_type_identification = null
				this.data.USER_name = null
				this.data.USER_lastname = null
				this.data.USER_indicative = null
				this.data.USER_phone = null
				this.data.USER_country = null
				this.data.USER_birthday = null
				this.data.USER_email =  null

				this.step = this.STEP_REGISTER

			},
			async validRegister(){
				this.showLoader= true;
				await api.post('searchUser', this.data).then(result => {				
					this.USER_valid = true;
					this.data.USER_PK = result
					this.showLoader = false;
				}).catch(error => {
					this.showLoader = false;
				})
				
				if(this.isOld){
					if (!this.USER_valid) { //si es viejo y el usuario no se encontro
						alert("Los datos ingresados no coinciden con algún pre registro")
					} else { //si es viejo y el usuario si esta
						// solo debe seguir de largo si es valido
					}
				} else {
					if (!this.USER_valid) { //si es nuevo y el usuario no esta
						await this.sendRegister()
						// el registro validará el usuario
					} else { //si es nuevo y el usuario ya existe
						alert("Los datos ingresados ya existen")
						this.USER_valid = false;
					}
				}
			}
		},
		async created() {
			cities.map( (c, index) => {
				this.countries.push({name: c.name, value: c.iso2, index})
			});
			this.courses = await api.get('getCourses')
		},
	};
});