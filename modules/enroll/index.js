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
				data:{
					USER_identification: "",
					USER_type_identification: "",
					USER_name: "",
					USER_lastname: "",
					USER_indicative: "",
					USER_phone: "",
					USER_country: "",
					USER_birthday: "",
					USER_email: ""
				}
			};
		},
		computed: {
		},
		methods: {
			async sendRegister(){
				this.showLoader= true;
				
				await api.post('register', this.data).then(result => {
					if (!result.errors) {
						alert('Registro Exitoso, llegará un correo con la confirmación')
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
				this.data.USER_identification = ""
				this.data.USER_type_identification = ""
				this.data.USER_name = ""
				this.data.USER_lastname = ""
				this.data.USER_indicative = ""
				this.data.USER_phone = ""
				this.data.USER_country = ""
				this.data.USER_birthday = ""
				this.data.USER_email =  ""
			}
		},
		created() {
			cities.map( (c, index) => {
				this.countries.push({name: c.name, value: c.iso2, index})
			})
		},
	};
});