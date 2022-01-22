define([
	"text!./index.html",
	"api",
	"cities",
	"components/menu"
], (html, api, cities,  Menu) => {
	return {
		template: html,
		components: {
			Menu
		},
		data() {
			return {
				countries: [],
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
				await api.post('register', this.data)
			},
			setIndicative(){
				this.data.USER_indicative =  "+" + cities.find( c => c.iso2 === this.data.USER_country)?.dialCode?? ""
			}
		},
		created() {
			cities.map( (c, index) => {
				this.countries.push({name: c.name, value: c.iso2, index})
			})
		},
	};
});