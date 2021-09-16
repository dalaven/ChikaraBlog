define([
	"text!./index.html"
], (html) => {
	return {
		template: html,
		data() {
			return {	
                welcomeJ:"ようこそ",
                welcomeE:"Bienvenidos!"
			};
		},
		computed: {
		
		},
		methods: {
		
		},
		created() {

		},
	};
});