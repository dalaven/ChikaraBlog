define([
	"text!./course.html",
	"api",
	"components/menu",
	"components/loader"
], (html, api,  Menu, Loader) => {
	return {
		template: html,
		components: {
			Menu,
			Loader
		},
		data() {
			return {
				showLoader: false,
				data:{
					USER_identification: "",
					USER_email: "",
					USER_valid: false
				},
				courses:[ ]
				
			};
		},
		computed: {
		},
		methods: {
			async sendData(){
				this.showLoader= true;

				if(!this.data.USER_valid){
					await api.post('searchUser', this.data).then(result => {				
						this.data.USER_valid = result;
						if(!this.data.USER_valid){
							alert("Los datos ingresados no coinciden con algún pre registro")
						}
						this.showLoader = false;
					}).catch(error => {
						this.showLoader = false;
					})
				} else {
					await api.post('saveCourse', this.data).then(result => {				
						this.showLoader = false;
						alert("Tu inscripción a nuestros cursos a sido satisfactoria.")
					}).catch(error => {
						this.showLoader = false; this.resetForm()
					})
				}

			},
			resetForm(){
				this.data.USER_identification = ""
				this.data.USER_email =  ""
				this.data.USER_valid =  false
			}
		},
		async created() {
			this.courses = await api.get('getCourses')

		},
	};
});